<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBArticleHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\Helper\DBCategoryHelper;
use Gate\Package\Helper\DBArticleDetailHelper;
use Gate\Package\Helper\DBArticleDraftHelper;
use Gate\Package\Helper\DBTopicArticleHelper;
use Gate\Package\Helper\DBTagUserHelper;
use Gate\Package\Helper\DBTagArticleHelper;
use Gate\Package\Helper\DBHotArticleHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Package\Helper\DBTopicCategoryHelper;
use Gate\Package\Helper\DBUserGradeHelper;
use Gate\Package\Helper\DBHotTagHelper;
use Gate\Package\Helper\DBBrandsHelper;
use Gate\Package\Helper\DBTagHelper;

use Gate\Package\Helper\DBHotUserArticleHelper;
use Gate\Package\Helper\DBHotUserCategoryHelper;
use Gate\Package\Helper\DBHotUserTagHelper;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Topic;
use Gate\Package\User\Follow;
use Gate\Package\Article\Like;
use Gate\Package\User\Login;
use Gate\Package\Helper\DBLikeHelper;
use Gate\Libs\Utilities;


class Article{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getBrands(){
		return DBBrandsHelper::getConn()->limit(18)->fetchAll();
	}


	public function upArticle($id,$title,$content,$category_id, $description_pic=''){
		$data = array('title'=>$title,'category_id'=>$category_id,'update_time'=>date('Y-m-d H:i:s'),time());
		$data['description_pic'] = $description_pic;

		$isUp = DBArticleHelper::getConn()->update($data,"article_id=:id",array('id'=>$id));
		if($isUp){
			$isUp = DBArticleDetailHelper::getConn()->update(array('content'=>$content),'article_id=:id',array('id'=>$id));
			if($isUp){
				return true;
			}
		}
		return false;
	}


	/*
	 * array $data
	 * array $detailData
	 * array $tagIds
	 */
	public function addArticle($data, $detailData, $tagIds=array()){
		$data['update_time'] = time();
		$newId = DBArticleHelper::getConn()->insert($data);
		if($newId && isset($data['article_id'])){
			$newId = $data['article_id'];
		}
		if($newId){
			// detail表
			$detailData['article_id'] = $newId;
			DBArticleDetailHelper::getConn()->insert($detailData);

			// statistic 表
			$statisticData = array('article_id' => $newId);
			DBArticleStatisticHelper::getConn()->insert($statisticData);

			// tag article
			if(!empty($tagIds)){
				foreach($tagIds as $v){
					$tagData = array('article_id'=>$newId, 'tag_id'=>$v);
					DBTagArticleHelper::getConn()->insert($tagData);
					DBTagHelper::getConn()->increment('article_num', array('tag_id'=>$v));
				}
			}

			// 增量
			DBCategoryHelper::getConn()->increment(array('article_num', 'article_num_check'), array('id'=>$data['category_id']));
			DBUserStatisticHelper::getConn()->increment(array('article_num','article_num_check'), array('user_id'=>$data['user_id']));
		}
		return $newId;
	}

	/*
	 * 更改文章
	 */
	public function editArticleById($articleId, $data=array(), $detailData=array()){
		if(!empty($data)){
			$row = DBArticleHelper::getConn()->field('category_id,is_check')->where('article_id=:article_id', array('article_id'=>$articleId))->fetch();
			DBArticleHelper::getConn()->update($data, 'article_id=:article_id', array('article_id'=>$articleId));
			// 分类是否更改
			if(isset($data['category_id']) && $data['category_id']!=$row->category_id){
				$decrementArticle = array('article_num','article_num_check');
				if($row->is_check==2){
					$decrementArticle[] = 'article_num_in';
				}
				// 原来的减量
				DBCategoryHelper::getConn()->decrement($decrementArticle, array('id'=>$row->category_id));
				// 更改的增量
				DBCategoryHelper::getConn()->increment($decrementArticle, array('id'=>$data['category_id']));
			}
		}
		if(!empty($detailData)){
			DBArticleDetailHelper::getConn()->update($detailData, 'article_id=:article_id', array('article_id'=>$articleId));
		}
		return 1;
	}

	/*
	 * 
	 */
	public function deleteArticle($articleId, $userId){
		$row = DBArticleHelper::getConn()->field('category_id,user_id,is_check,is_delete,forward_article_id')->where('article_id=:article_id AND user_id=:uid', array('article_id'=>$articleId, 'uid'=>$userId))->fetch();

		if(empty($row) || $row->is_delete==1){
			return true;
		}

		$isD = DBArticleHelper::getConn()->update(array('is_delete'=>1), 'article_id=:article_id AND user_id=:user_id', array('article_id'=>$articleId,'user_id'=>$userId));
		if($isD){
			// ----- 分类，用户的文章统计
			$decrementArticle = array('article_num','article_num_check');
			if($row->is_check){
				$decrementArticle[] = 'article_num_in';
			}

			DBCategoryHelper::getConn()->decrement($decrementArticle, array('category_id'=>$row->category_id));
			DBUserStatisticHelper::getConn()->decrement($decrementArticle, array('user_id'=>$row->user_id));

			//------- tag
			$tagArticleList = DBTagArticleHelper::getConn()->field('id, tag_id')->where('article_id=:article_id', array('article_id'=>$articleId))->fetchAll();
			if(!empty($tagArticleList)){
				$ids	= array();
				$tagIds = array();
				foreach($tagArticleList as $v){
					$ids[]		= $v->id;
					$tagIds[]	= $v->tag_id;
				}

				DBTagArticleHelper::getConn()->delete('id IN(:id)', array('id'=>$ids));
				// article num
				$sql = "UPDATE beauty_tag SET article_num=article_num-1, article_num_in-1 WHERE tag_id IN(:tag_id)";
				DBTagHelper::getConn()->write($sql, array('tag_id'=>$tagIds));
			}

			//-------- 话题
			$topicArticleList = DBTopicArticleHelper::getConn()->where('article_id=:article_id', array('article_id'=>$articleId))->fetchAll();
			if(!empty($topicArticleList)){
				$topicIds = array();
				$ids = array();
				foreach($topicArticleList as $v){
					$ids[] = $v->id;
					$topicIds[] = $v->topic_id;
				}
				DBTopicArticleHelper::getConn()->delete('article_id=:article_id', array('article_id'=>$articleId));

				// article num
				$sql = "UPDATE beauty_topic SET article_num=article_num-1 WHERE topic_id IN(:topic_id)";
				DBTagHelper::getConn()->write($sql, array('topic_id'=>$topicIds));
			}

			if($row->forward_article_id){
				DBArticleStatisticHelper::getConn()->decrement('forward_num' ,array('article_id'=>$row->forward_article_id));
			}
		}
		return $isD;
	}



	/*
	 * id集合文章
	 */
	public function getArticleByIds($ids, $fields='', $offset, $length){
		if(empty($ids)){
			return array();
		}
		$articleList =  $this->getArticle($fields, 'article_id IN(:id)', array('id'=>$ids), $offset, $length);
		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[]		= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$topicList		= Topic::getInstance()->getTopicListByAids($ids, 'topic_id,title');
		$data			= array();
		foreach($articleList as $v){
			$v->user	= $userList[$v->user_id];
			$v->category= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;
			$v->topic	= isset($topicList[$v->article_id] ) ? $topicList[$v->article_id] : '';
			$data[] = $v;
		}
		return $data;

	}

	public function getArticleNumByUid($userId){
		$row = DBUserStatisticHelper::getConn()->field('article_num')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		if(!$row){ // 容错
			return 0;
		}
		return (int)$row->article_num;
	}

	/*
	 * 标签文章
	 */
	public function getArticleByTag($categoryId, $tagId, $offset, $length){
		//$where .= ' AND tag_id=:tag_id';
		//$whereParams['tag_id'] = $tagId;
		$sql = "
				SELECT t.article_id
				FROM beauty_article AS a
				Inner JOIN  `beauty_tag_article` AS t ON a.article_id = t.article_id
				WHERE a.is_delete =0 AND a.is_check=2  AND t.tag_id =:tag_id
				ORDER BY t.article_id DESC
				LIMIT {$offset},{$length} 
			";

		$articleIds = DBTagArticleHelper::getConn()->fetchCol($sql, array('tag_id'=>$tagId));
		$loginUserId	= Login::getInstance()->getLoginUserId();
		$articleList = $this->getArticleListRelation('', 'article_id IN(:aid)', array('aid'=>$articleIds), 0, $length, $loginUserId);
		return $articleList;
	}

	/*
	 * 分类文章
	 */
	public function getArticleByCid($categoryId, $offset, $length){
		$categoryList = Category::getInstance()->getChildren($categoryId);
		$cids = array();
		foreach($categoryList as $v){
			$cids[] = $v->id;
		}
		$cids[] = $categoryId;

		$where = 'category_id In (:category_id) AND is_check=2';
		$whereParams['category_id'] = $cids;

		$loginUserId	= Login::getInstance()->getLoginUserId();
		$articleList	= $this->getArticleListRelation('', $where, $whereParams, $offset, $length, $loginUserId);

		return $articleList;
	}

	/*
	 *
	 */
	public function getArticleList($fields='', $offset,$length){
		$loginUserId = Login::getInstance()->getLoginUserId();
		$articleList = $this->getArticleListRelation($fields, 'is_check=2', array(), $offset,$length, $loginUserId);
		return $articleList;
	}

	/*
	 * 封装公用文章数据
	 */
	public function getArticle($fields='', $where, $params,  $offset, $length){
		$fields == '' && $fields = 'article_id, title, category_id, user_id, description, description_pic, update_time, like_num, forward_num, comment_num, by_forward_user_id, forward_article_id, forward_description'; // 不能换行，否则换行第一个元素找不到，原因暂时不明
		$pkId			= 'article_id';
		$dbHelper		= array('DBArticleHelper','DBArticleStatisticHelper', 'DBArticleDetailHelper');
		$namespace		= '\Gate\Package\Helper\\';
		foreach($dbHelper as &$vh){
			$vh = $namespace . $vh;
		}

		// where
		strpos($where, 'is_delete')===false &&  $where .= ' AND is_delete=0';
		//strpos($where, 'is_check')===false &&  $where .= ' AND is_check!=1';

		$arrFields		= explode(',', str_replace(' ', '', $fields));
		$data			= array();
		// 第一个表数据，决定排序
		$dbFields	= explode(',', str_replace(' ', '', $dbHelper[0]::_FIELDS_));
		$dbFields	= array_intersect($dbFields, $arrFields);
		$data[0]	= $dbHelper[0]::getConn()->field($pkId . ',' . implode(',', $dbFields) )->where($where, $params)->order($pkId . ' DESC')->limit($offset, $length)->fetchArrAll();
		unset($dbHelper[0]);
		if(empty($data[0])){
			return array();
		}

		$ids = array();
		foreach($data[0] as $v){
			$ids[] = $v[$pkId];
		}
		foreach($dbHelper as $k=>$vh){
			$dbFields	= explode(',', str_replace(' ', '', $vh::_FIELDS_));
			$dbFields	= array_intersect($dbFields, $arrFields);
			if( !empty($dbFields)){
				$data[]		= $vh::getConn()->field($pkId . ',' . implode(',', $dbFields) )->where($pkId.' IN(:id) ', array('id'=>$ids))->fetchArrAll();
			}
		}

		$list = array();
		// 合并数组
		foreach($data as $vFieldList){
			foreach($vFieldList as $v){
				$list[$v[$pkId]] = isset($list[$v[$pkId]]) ? $list[$v[$pkId]] + $v : $v;
			}
		}

		// 转换为对象模式
		$result = array();
		$forwardIds = array();
		foreach($list as $v){
			$v = (object)$v;
			isset($v-> description_pic) && $v-> description_pic = $v->description_pic!='' ? explode(',', $v->description_pic) : array();  // 缩略图
			isset($v-> description) && $v-> description		= Utilities::htmlStripTags($v->description);
			isset($v->content)		&& $v-> content			= Utilities::htmlSpDecode($v->content);
			isset($v->update_time) && $v->update_time		= Utilities::getTimeGapMsg($v->update_time);

			//获取转发文章的id和原文id
			//
			if(!empty($v->forward_article_id)){
				$forwardIds[$v->article_id] = $v->forward_article_id;
			}
			$v->is_like	= 0;
			$result[] = $v;
		}
		// 记录转发文章的id和原文id
		// 获取原文id的喜欢,评论,转发数量
		// 再对转发文章的信息赋值
		if(!empty($forwardIds)){
			$forwardArticleInfo = $this->getArticleByIds($forwardIds,'',0,count($forwardIds));
			foreach($forwardArticleInfo as $v){
				foreach($result as $vv){
					if($v->article_id == $vv->forward_article_id){
						$vv->forward_info = $v;
					}
				}
			}
		}
		return $result;
	}

	/*
	 * 通过id 返回一条数据
	 */
	public function getArticleById($id, $fields){
		$pkId				= 'article_id';

		$arrFields			= explode(',', str_replace(' ', '', $fields));
		$itemFields			= explode(',', str_replace(' ', '', DBArticleHelper::_FIELDS_));
		$itemDetailFields	= explode(',', str_replace(' ', '', DBArticleDetailHelper::_FIELDS_));
		$itemStatisticFields= explode(',', str_replace(' ', '', DBArticleStatisticHelper::_FIELDS_));
		$itemFields			= array_intersect($itemFields, $arrFields);
		$itemDetailFields	= array_intersect($itemDetailFields, $arrFields);
		$itemStatisticFields	= array_intersect($itemStatisticFields, $arrFields);

		$row	= array();
		if( !empty($itemFields) ){ 
			// is_delete 在这里判断, 维护显示数据一致性
			$row = DBArticleHelper::getConn()->field($pkId.','.implode(',', $itemFields) )->where($pkId.'=:id AND is_delete=0', array('id'=>$id))->fetchArr();
			if( empty($row) ) {return false;}  
		}

		if( !empty($itemDetailFields)) {
			$row += DBArticleDetailHelper::getConn()->field($pkId.','.implode(',', $itemDetailFields) )->where($pkId.'=:id', array('id'=>$id))->fetchArr();
		}
		if( !empty($itemStatisticFields)) {
			$row += DBArticleStatisticHelper::getConn()->field($pkId.','.implode(',', $itemStatisticFields) )->where($pkId.'=:id', array('id'=>$id))->fetchArr();
		}
		$row = (object)$row;
		isset($row->update_time) && $row->update_time		= Utilities::getTimeGapMsg($row->update_time);
		isset($row->content)	&& $row-> content			= Utilities::htmlSpDecode($row->content);

		$loginUserId	= Login::getInstance()->getLoginUserId();

		// is_like
		$row->is_like = false;
		if($loginUserId){
			$likeArticleIds	= Like::getInstance()->checkUserLikeArticle_2($loginUserId , array($id));
			$row->is_like = empty($likeArticleIds) ? false : true;
		}

		// is_follow
		$row->is_follow = false;
		if($loginUserId){
			$viewUserIds = Follow::getInstance()->getFollowViewUserIds($loginUserId, array($id));
			$row->is_follow = empty($viewUserIds) ? false  : true;
		}

		// 话题信息
		$row->topic = Topic::getInstance()->getTopicByAid($id);
		return $row;
	}


	/*
	 * 按标签获取热门文章
	 * 随机
	 * @wanghaihong
	 */
	public function getHotTagArticle($tagName, $length){
		$where = '1=1';
		$params = array();
		if($tagName!=''){
			$where .= ' AND name=:name';
			$params['name'] = $tagName;
		}
		$articleIds = DBHotTagHelper::getConn()->field('article_id')->where($where, $params)->fetchCol();
		if(empty($articleIds)){
			return array();
		}
		// 随机
		if(count($articleIds) > $length){
			$articleIds = Utilities::array_random($articleIds, $length);
		}
		
		$loginUserId	= Login::getInstance()->getLoginUserId();
		$articleList	= $this->getArticleListRelation('', 'article_id IN(:aid)', array('aid'=>$articleIds), 0, $length, $loginUserId, false);
		return $articleList;
	}

	/*
	 * 广场热门标签
	 */
	public function getHotTag(){
		return DBHotTagHelper::getConn()->field('distinct name')->fetchCol();
	}

	/*
	 * 热门文章
	 * 
	 * @wanghaihong
	 */
	public function getHotArticle($categoryId=0, $topicId=0,$offset=0,$length=8){
		if($categoryId==0 && $topicId!=0){
			$tcRow = DBTopicCategoryHelper::getConn()->field('category_id')->where('topic_id=:topic_id', array('topic_id'=>$topicId))->limit(1)->fetch();
			$categoryId = $tcRow->category_id;
		}

		$where		 = '1=1';
		$whereParams = array();
		if($categoryId){
			$categoryList = Category::getInstance()->getChildren($categoryId);
			$cids = array();
			foreach($categoryList as $v){
				$cids[] = $v->id;
			}
			$cids[] = $categoryId;
			$where .= ' AND category_id In (:category_id)';
			$whereParams['category_id'] = $cids;
		}

		$articleIds		= DBHotArticleHelper::getConn()->field('article_id')->where($where, $whereParams)->limit($offset, $length)->fetchCol();
		if(empty($articleIds)){
			return array();
		}

		$loginUserId	= Login::getInstance()->getLoginUserId();
		//$fields			= 'article_id, title, category_id, user_id, description, description_pic, like_num, forward_num, comment_num, by_forward_user_id, forward_article_id, forward_description'; // 不能换行，否则换行第一个元素找不到，原因暂时不明
		$fields			= 'article_id, title, category_id, user_id, description_pic, by_forward_user_id, forward_article_id'; // 不能换行，否则换行第一个元素找不到，原因暂时不明
		$articleList	= $this->getArticleListRelation($fields, 'article_id IN(:aid)', array('aid'=>$articleIds), 0, $length, $loginUserId, false);
		return $articleList;
	}

	/*
	 * 话题文章
	 */
	public function getArticeByTopic($topicId, $offset, $length, $isHot=false, $isPage=false){
		if(empty($topicId)){
			return array();
		}

		// 最热话题
		if($isHot){
			$topicArticleIds		= DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id =:topic_id', array('topic_id'=>$topicId))->fetchCol();
			if(empty($topicArticleIds)){
				return array();
			}
			$order			= 'comment_num DESC';
			$articleIds	= DBArticleStatisticHelper::getConn()->field('article_id')->where('article_id IN(:aid)', array('aid'=>$topicArticleIds))->order($order)->limit($offset, $length)->fetchCol();

		// 全部
		}else{
			// 时间排序
			if(!$isPage){
				$articleIds		= DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id =:topic_id', array('topic_id'=>$topicId))->limit($offset, $length)->order('id DESC')->fetchCol();
			}else{
				$articleIds		= DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id =:topic_id', array('topic_id'=>$topicId))->order('id DESC')->fetchCol();
			}
			if(empty($articleIds)){
				return array();
			}
		}
		$loginUserId	= Login::getInstance()->getLoginUserId();

		$conditionOffset = !$isPage ? 0 : $offset;
		$articleList	= $this->getArticleListRelation('', 'article_id IN(:aid)', array('aid'=>$articleIds), $conditionOffset, $length, $loginUserId, false);

		return $articleList;
	}

	/*
	 * 关注的人的, 和关注的话题的文章
	 * 各取出小于offsetArticleId 的length个文章id，然后合并取最大的length 个
	 *
	 */
	public function getFollowArticleList($userId, $offsetArticleId, $length){
		// ------- 关注的人的文章
		$userIds = Follow::getInstance()->getFollowUserIds($userId);
		$userIds[] = $userId; // 同时显示自己发布的文章

		//第一页
		$where = 'is_delete=0 AND is_check!=1 AND user_id IN(:user_id) ';
		$param = array('user_id'=>$userIds);

		// 第二页之后
		if( $offsetArticleId>0 ){ // 分页用的id,上一页的最后一个文章id 
			$where .= ' AND article_id<=:article_id';
			$param['article_id'] = $offsetArticleId;
		}
		$followUserAids = DBArticleHelper::getConn()->field('article_id')->where($where, $param)->order('article_id DESC')->limit(0, $length)->fetchCol();
		
		// -------- 关注的话题的文章
		$topicArticleIds = Topic::getInstance()->getArticleIdsByTopicUid($userId, $offsetArticleId, $length);

		// 合并id，取length 个
		$articleIds = array_merge($followUserAids, $topicArticleIds);
		$articleIds = array_unique($articleIds);
		rsort($articleIds);
		if( count($articleIds) > $length){
			$articleIds = array_slice($articleIds, 0 , $length);
		}

		//$articleList = $this->getArticle('', 'user_id IN(:id)', array('id'=>$userIds), $offset, $length);
		$articleList = $this->getArticleListRelation('', 'article_id IN(:aid)', array('aid'=>$articleIds), 0, $length, $userId);
		return $articleList;
	}
   
	/*
	 * 个人主页的文章分页统计
	 * 默认只显示10页
	 */
	public function getFollowArticleCount($userId, $offsetArticleId, $length, $totalNum=200){
		// ------- 关注的人的文章,关注人数有上限
		$userIds = Follow::getInstance()->getFollowUserIds($userId);
		$userIds[] = $userId; // 同时显示自己发布的文章

		//第一页
		$where = 'is_delete=0 AND is_check!=1 AND user_id IN(:user_id) ';
		$param = array('user_id'=>$userIds);

		// 第二页之后
		if( $offsetArticleId>0 ){ // 分页用的id,上一页的最后一个文章id 
			$where .= ' AND article_id<=:article_id';
			$param['article_id'] = $offsetArticleId;
		}
		$followUserAids = DBArticleHelper::getConn()->field('article_id')->where($where, $param)->order('article_id DESC')->limit(0, $totalNum)->fetchCol();
		// -------- 关注的话题的文章
		$topicArticleIds = Topic::getInstance()->getArticleIdsByTopicUid($userId, $offsetArticleId, $totalNum);

		// 合并id，取length 个
		$articleIds = array_merge($followUserAids, $topicArticleIds);
		$articleIds = array_unique($articleIds);
		if( count($articleIds) > $totalNum){
			$articleIds = array_slice($articleIds, 0 , $totalNum);
		}

		$data['num'] = count($articleIds);

		// 分页
		foreach($articleIds as $k=>$v){
			if($k%$length==0){
				$data['endIds'][] = $v;
			}
		}
		return $data;
	}
	/*
	 * 当前登录用户发布的文章
	 */
	public function getArticleListByLoginUid($userId, $offset, $length){
		return $this->getArticleListByUid($userId, $offset, $length, $userId);
	}

	/*
	 * 某个用户发布的文章
	 */
	public function getArticleListByUid($userId, $offset, $length,$loginUserId){
		$articleList = $this->getArticleListRelation('', 'user_id =:id', array('id'=>$userId), $offset, $length, $loginUserId);
		return $articleList;

	}

	public function getArticleListByUIds($userIds){
		return $this->getArticle('','user_id IN(:uid)',array('uid'=>$userIds),0,4);
	}

	public function getArticleListByLoginUidNum($userId){
		return DBArticleHelper::getConn()->where('user_id=:id AND is_delete=0',array('id'=>$userId))->fetchCount();
	}

	public function getSkinCareArticleList($name){
		$hotUserCategoryId		= DBHotUserCategoryHelper::getConn()->field('id')->where('name =:name',array('name'=>$name))->fetchCol();
		$articleIds				= DBHotUserArticleHelper::getConn()->field('article_id')->where('hot_user_category_id=:id',array('id'=>$hotUserCategoryId[0]))->fetchCol();
		$length = count($articleIds);
		$articleList			= $this->getArticleByIds($articleIds,'',0,$length);
		$userIds = array();
		foreach($articleList as $al){
			$userIds[] = $al->user_id;
		}
		$userInfo				= Userinfo::getInstance()->getUserByIds($userIds,'',0,$length);
		foreach($articleList as $al){
			foreach($userInfo as $ul){
				if($al->user_id == $ul->user_id){
					$al->userInfo = $ul;
				}
			}
		}
		return $articleList;
	}
	//暂时用
	public function getHotUserArticleList(){
		$hotUserArticle			= DBHotUserArticleHelper::getConn()->fetchAll();

		$hotUserTagIds			= array();
		$hotUserCategoryIds		= array();
		foreach($hotUserArticle as $hua){
			$hotUserCategoryIds[]	= $hua->hot_user_category_id;
			$hotUserTagIds[]	= $hua->hot_user_tag_id;
		}
		$hotUserCategory		= DBHotUserCategoryHelper::getConn()->where('id IN(:ids)',array('ids'=>$hotUserCategoryIds))->fetchAll();
		$hotUserTag				= DBHotUserTagHelper::getConn()->where('tag_id IN(:ids)',array('ids'=>$hotUserTagIds))->fetchAll();

		foreach($hotUserArticle as $hua){
			foreach($hotUserCategory as $huc){
				if($hua->hot_user_category_id == $huc->id){
					$hua->hotUserCategoryInfo = $huc;
				}
			}
			foreach($hotUserTag as $hut){
				if($hua->hot_user_tag_id == $hut->tag_id){
					$hua->hotUserTagInfo = $hut;
				}
			}
		}
		$articleIds = array();
		foreach($hotUserArticle as $hua){
			$articleIds[]	= $hua->article_id;
		}
		$length = count($articleIds);
		$articleList		=  $this->getArticleByIds($articleIds,'',0,$length);
		foreach($articleList as $al){
			foreach($hotUserArticle as $hua){
				if($hua->article_id == $al->article_id){
					$hua->articleInfo = $al;
				}
			}
		}
		$userIds = array();
		foreach($hotUserArticle as $hua){
			if(isset($hua->articleInfo)){
				$userIds[] = $hua->articleInfo->user_id;
			}
		}
		$length = count($userIds);
		$userInfo				= Userinfo::getInstance()->getUserByIds($userIds,'',0,$length);
		foreach($hotUserArticle as $hua){
			foreach($userInfo as $ul){
				if(isset($hua->articleInfo)){
					if($hua->articleInfo->user_id == $ul->user_id){
						$hua->userInfo = $ul;
					}
				}
			}
		}
		return $hotUserArticle;
	}
	

	public function getHotCategorySort(){
		return  DBHotUserCategoryHelper::getConn()->ORDER('sort DESC')->LIMIT(3)->fetchAll();
	}


	public function getHotUserArticleListTop($categoryId,$tagId,$loginUserId){
			$where							= '1=1';
			$params							= array();
			if($categoryId){
				$where						.= ' AND hot_user_category_id=:category_id';
				$params['category_id']		= $categoryId;
			}
			if($tagId){
				$where						.= ' AND hot_user_tag_id=:tag_id';
				$params['tag_id']		= $tagId;
			}
			$category				= DBHotUserCategoryHelper::getConn()->where('id=:id',array('id'=>$categoryId))->fetch();
			$hotUserArticle			= DBHotUserArticleHelper::getConn()->where($where,$params)->fetchAll();

			if(!$hotUserArticle){
				return false;
			}

			$hotUserTagIds			= array();
			foreach($hotUserArticle as $hua){
				$hotUserTagIds[]	= $hua->hot_user_tag_id;
			}
			$hotUserTag				= DBHotUserTagHelper::getConn()->where('tag_id IN(:ids)',array('ids'=>$hotUserTagIds))->fetchAll();

			foreach($hotUserArticle as $hua){
				$hua->hotUserCategoryInfo = $category->name;
				foreach($hotUserTag as $hut){
					if($hua->hot_user_tag_id == $hut->tag_id){
						$hua->hotUserTagInfo = $hut;
					}
				}
			}
			$articleIds = array();
			foreach($hotUserArticle as $hua){
				$articleIds[]	= $hua->article_id;
			}
			$length = count($articleIds);
			//$loginUserId	= Login::getInstance()->getLoginUserId();

			$articleList	= $this->getArticleListRelation('', 'article_id IN(:aid)', array('aid'=>$articleIds), 0, $length, $loginUserId, false);

			foreach($articleList as $al){
				foreach($hotUserArticle as $hua){
					if($hua->article_id == $al->article_id){
						$hua->articleInfo = $al;
					}
				}
			}
			$userIds = array();
			foreach($hotUserArticle as $hua){
				if(isset($hua->articleInfo)){
					$userIds[] = $hua->articleInfo->user_id;
				}
			}
			$length = count($userIds);
			$userInfo				= Userinfo::getInstance()->getUserByIds($userIds,'',0,$length);
			foreach($hotUserArticle as $hua){
				foreach($userInfo as $ul){
					if(isset($hua->articleInfo)){
						if($hua->articleInfo->user_id == $ul->user_id){
							$hua->userInfo = $ul;
						}
					}
				}
			}
		return $hotUserArticle;
	}

	public function getHotUserTagList($id){
		$num = 6;
		$houUserTag =  DBHotUserTagHelper::getConn()->WHERE('hot_user_category_id=:id',array('id'=>$id))->LIMIT(5)->fetchAll();
		if(count($houUserTag) > $num)
			return Utilities::array_random($houUserTag,$num);
		else
			return $houUserTag;
	}

	/*
	 * 达人引导的文章
	 */
	public function getGuideDarenArticleList($userIds, $length){
		// 每个达人的一篇文章
		$strDarenIds = implode(',', $userIds);
		$sql = "
			SELECT user_id AS kid, article_id, user_id,  description_pic
			FROM  `beauty_article` 
			WHERE  `user_id` 
			IN ( {$strDarenIds} ) 
			AND description_pic!=''
			GROUP BY user_id
			ORDER BY article_id DESC 
			LIMIT {$length} ";
		return DBArticleHelper::getConn()->fetchAssocAll($sql, array());
	}

	public function getArticleContentById($articleId){
		$content = DBArticleDetailHelper::getConn()->field('content')->where('article_id=:article_id',array('article_id'=>$articleId))->fetch();
		return $content;
	}

	public function getForwardUserIds($userId,$offset,$length){
		$forwardIds = DBArticleHelper::getConn()->field('by_forward_user_id')->where('by_forward_user_id=:user_id',array('user_id'=>$userId))->limit($offset,$length)->fetchCol();
		return $forwardIds;
	}

	/*
	 *	获得用户被喜欢的用户,文章列表
	 */
	public function getFavouriteArticleByUid($userId,$offset,$length){
		$userIds = array();
		$articleIds = array();
		$favouritelist = DBLikeHelper::getConn()->field('user_id,article_id,create_time')->where('article_user_id=:user_id',array('user_id'=>$userId))->order('like_id DESC')->limit($offset,$length)->fetchAll();
		if(!$favouritelist){
			return array();
		}
		foreach($favouritelist as $v){
			$userIds[] = $v->user_id;
			$articleIds[] = $v->article_id;
		}
		$userList = Userinfo::getInstance()->getUserByIds($userIds,'',0,count($userIds));
		$articleList = $this->getArticleByIds($articleIds,'user_id,title,article_id,category_id, create_time',0,count($articleIds));
		foreach($favouritelist as $fv){
			foreach($userList as $uv){
				if($fv->user_id == $uv->user_id){
					$fv->userinfo = $uv;
					break;
				}
			}
			foreach($articleList as $av){
				if($fv->article_id == $av->article_id){
					$fv->articleinfo = $av;
					break;
				}
			}
		}
		return $favouritelist;
	}

	/*
	 * 通过文章ID获得作者ID
	 */
	public function getUserIdByArticleId($articleId){
		return DBArticleHelper::getConn()->field('user_id')->where('article_id=:articleId',array('articleId'=>$articleId))->fetch();
	}


	/*
	 *获取收到文章的喜欢
	 */
	public function getFavouriteArticleByUidCount($userId){
		return DBLikeHelper::getConn()->where('article_user_id=:userId',array('userId'=>$userId))->fetchCount();
	}

	/*
	 *  存入草稿箱
	 */
	public function addArticleDraft($data){
		return DBArticleDraftHelper::getConn()->insertUpdate($data,$data);
	}

	/*
	 * 取出草稿内容
	 */
	public function getArticleDraft($user_id , $draft_id=0){
		if($draft_id == 0){
			$categoryIds = array();
			$draft_list = DBArticleDraftHelper::getConn()->where('user_id=:user_id',array('user_id'=>$user_id))->fetchAll();
			foreach($draft_list as $v){
				if($v->category_id != 0){
					$categoryIds[] = $v->category_id;
				}
			}
			$categoryList		= Category::getInstance()->getCategoryByCids($categoryIds, 'id,name');
			foreach($draft_list as $v){
				if($v->category_id != 0){
					$v->category_name = $categoryList[$v->category_id]->name;
				}else{
					$v->category_name = '';
				}
			}
			return $draft_list;
		}
		return DBArticleDraftHelper::getConn()->where('user_id=:user_id AND id=:draft_id' ,array('user_id'=>$user_id,'draft_id'=>$draft_id))->fetch();
	}


	/*
	 * 删除草稿箱内容
	 */
	public function delArticleDraft($draft_id , $user_id){
		return DBArticleDraftHelper::getConn()->delete('id=:draft_id AND user_id=:user_id',array('draft_id'=>$draft_id,'user_id'=>$user_id));
	}



	public function getArticle_2($fields='', $where, $params,  $offset, $length){
		$fields == '' && $fields = 'title, category_id, user_id, description, description_pic, update_time, create_time, like_num, forward_num, comment_num, by_forward_user_id, forward_article_id, forward_description'; // 不能换行，否则换行第一个元素找不到，原因暂时不明
		$pkId			= 'article_id';
		$dbHelper		= array('DBArticleHelper','DBArticleStatisticHelper', 'DBArticleDetailHelper');
		$namespace		= '\Gate\Package\Helper\\';
		foreach($dbHelper as &$vh){
			$vh = $namespace . $vh;
		}

		// where
		strpos($where, 'is_delete')===false &&  $where .= ' AND is_delete=0';
		//strpos($where, 'is_check')===false &&  $where .= ' AND is_check!=1';

		$arrFields		= explode(',', str_replace(' ', '', $fields));
		$data			= array();
		// 第一个表数据，决定排序
		$dbFields	= explode(',', str_replace(' ', '', $dbHelper[0]::_FIELDS_));
		$dbFields	= array_intersect($dbFields, $arrFields);
		if(isset($params['aid'])){
			$params['aid'] = array_unique($params['aid']);
		}
		$data[0]	= $dbHelper[0]::getConn()->field($pkId . ',' . implode(',', $dbFields) )->where($where, $params)->order($pkId . ' DESC')->limit($offset, $length)->fetchArrAll();
		unset($dbHelper[0]);
		if(empty($data[0])){
			return array();
		}

		$ids = array();
		foreach($data[0] as $v){
			$ids[] = $v[$pkId];
		}
		foreach($dbHelper as $k=>$vh){
			$dbFields	= explode(',', str_replace(' ', '', $vh::_FIELDS_));
			$dbFields	= array_intersect($dbFields, $arrFields);
			if( !empty($dbFields)){
				$data[]		= $vh::getConn()->field($pkId . ',' . implode(',', $dbFields) )->where($pkId.' IN(:id) ', array('id'=>$ids))->fetchArrAll();
			}
		}

		$list = array();
		// 合并数组
		foreach($data as $vFieldList){
			foreach($vFieldList as $v){
				$list[$v[$pkId]] = isset($list[$v[$pkId]]) ? $list[$v[$pkId]] + $v : $v;
			}
		}

		// 转换为对象模式
		$result = array();
		$forwardIds = array();
		foreach($list as $v){
			$v = (object)$v;
			isset($v-> description_pic) && $v-> description_pic = $v->description_pic!='' ? explode(',', $v->description_pic) : array();  // 缩略图
			isset($v-> description) && $v-> description		= Utilities::htmlStripTags($v->description);
			isset($v->content)		&& $v-> content			= Utilities::htmlSpDecode($v->content);
			isset($v->update_time) && $v->update_time		= Utilities::getTimeGapMsg($v->update_time);
			isset($v->create_time) && $v->create_time       = Utilities::getTimeGapMsg(strtotime($v->create_time));
			$result[] = $v;
		}
		return $result;
	}

	/*
	 * 文章相关联数据
	 */
	public function getArticleListRelation($fields, $where, $params, $offset, $length, $loginUserId=0, $hasTopic=true){
		$userFields			= 'user_id, nickname, realname, grade, avatar_c, article_num, follow_num';
		$categoryFields		= 'id AS k, id, name';
		$topicFields		= 'topic_id,title';

		$articleList		= $this->getArticle_2($fields, $where, $params, $offset,$length);
		if(empty($articleList)){ return array();}

		$userIds			= array();
		$categoryIds		= array();
		$articleIds			= array();
		$forwardArticleIds	= array();
		$forwardArticleList = array();
		$likeArticleIds		= array();

		foreach($articleList as $v){
			$userIds[$v->user_id]			= $v->user_id;
			$categoryIds[$v->category_id]	= $v->category_id;
			$articleIds[]					= $v->article_id;
			if($v->forward_article_id){
				$forwardArticleIds[$v->forward_article_id]	= $v->forward_article_id;
				$userIds[$v->by_forward_user_id]			= $v->by_forward_user_id;
			}
		}

		// forward
		if(!empty($forwardArticleIds)){
			$forwardfields = 'article_id, title, category_id, user_id, description, description_pic, update_time, like_num, forward_num, comment_num';
			$articleIds = array_merge($articleIds, $forwardArticleIds);
			$forwardArticleList = $this->getArticle($forwardfields, 'article_id IN (:aid)', array('aid'=>$forwardArticleIds), 0, count($forwardArticleIds));
		}

		$userList			= Userinfo::getInstance()->getUserByIds($userIds, $userFields, 0, $length);
		$categoryList		= Category::getInstance()->getCategoryByCids($categoryIds, $categoryFields);
		$topicList			= $hasTopic ? Topic::getInstance()->getTopicListByAids($articleIds, $topicFields) : array();

		// is_like
		if($loginUserId){
			$likeArticleIds	= Like::getInstance()->checkUserLikeArticle_2($loginUserId , $articleIds);
		}

		// forward relation
		foreach($forwardArticleList as $v){
			$v->is_like			= in_array($v->article_id, $likeArticleIds) ? true : false;
			$v->category		= isset($categoryList[$v->category_id])		? $categoryList[$v->category_id] : false;
			$v->user			= isset($userList[$v->user_id])				? $userList[$v->user_id] : false;
		}

		foreach($articleList as $v){
			$v->is_like			= in_array($v->article_id, $likeArticleIds) ? true : false;
			$v->category		= isset($categoryList[$v->category_id]) ? $categoryList[$v->category_id] : false;
			$v->user			= isset($userList[$v->user_id])			? $userList[$v->user_id] : false;
			$v->topic			= isset($topicList[$v->article_id] )	? $topicList[$v->article_id] : '';

			// forward 
			if($v->forward_article_id){
				foreach($forwardArticleList as $vf){
					if($vf->article_id==$v->forward_article_id){
						$v->forward_info	= $vf;
						break;
					}
				}
				// 转发的文章未找到 - 已经删除
				if( !isset($v->forward_info)){
					$v->forward_info = false;
				}
			}
		}

		return $articleList;
	}

	
	public function getArticleTagById($articleId){
		 return DBTagArticleHelper::getConn()->field('article_id, tag_id')->where('article_id =:article_id', array('article_id'=>$articleId))->fetchAll();
	}

	/*
	 *更新文章标签
	 *$id 文章id
	 *$tag array
	 */
	public function upTagArticle($id,$tag){
		if(empty($tag)){
			return true;
		}
		DBTagArticleHelper::getConn()->delete('article_id=:id',array('id'=>$id));
		foreach($tag as $t=>$v){
			if(!DBTagArticleHelper::getConn()->insert(array('tag_id'=>$v,'article_id'=>$id))){
				return false;
			}
		}
		return true;
	}
}
