<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Activity;
use Gate\Package\Helper\DBArticleHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\Helper\DBCategoryHelper;
use Gate\Package\Helper\DBArticleDetailHelper;
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
		$data = array('title'=>$title,'category_id'=>$category_id);
		if($description_pic!=''){
			$data['description_pic'] = $description_pic;
		}
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
			DBCategoryHelper::getConn()->increment('article_num', array('id'=>$data['category_id']));
			DBCategoryHelper::getConn()->increment('article_num_check', array('id'=>$data['category_id']));
			DBUserStatisticHelper::getConn()->increment('article_num', array('user_id'=>$data['user_id']));
			DBUserStatisticHelper::getConn()->increment('article_num_check', array('user_id'=>$data['user_id']));
		}
		return $newId;
	}

	/*
	 * 更改文章
	 */
	public function editArticleById($articleId, $data=array(), $detailData=array()){
		if(!empty($data)){
			$row = DBArticleHelper::getConn()->field('category_id')->where('article_id=:article_id', array('article_id'=>$articleId))->fetch();
			DBArticleHelper::getConn()->update($data, 'article_id=:article_id', array('article_id'=>$articleId));
			// 分类是否更改
			if(isset($data['category_id']) && $data['category_id']!=$row->category_id){
				// 原来的减量
				DBCategoryHelper::getConn()->decrement('article_num', array('id'=>$row->category_id));
				DBCategoryHelper::getConn()->decrement('article_num_check', array('id'=>$row->category_id));
				// 更改的增量
				DBCategoryHelper::getConn()->increment('article_num', array('id'=>$data['category_id']));
				DBCategoryHelper::getConn()->increment('article_num_check', array('id'=>$data['category_id']));
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
		$row = DBArticleHelper::getConn()->field('category_id,user_id')->where('article_id=:article_id', array('article_id'=>$articleId))->fetch();
		if($row->user_id != $userId){
			return false;
		}
		$isD = DBArticleHelper::getConn()->update(array('is_delete'=>1), 'article_id=:article_id AND user_id=:user_id', array('article_id'=>$articleId,'user_id'=>$userId));
		if($isD){
			// ----- 分类，用户的文章统计
			DBCategoryHelper::getConn()->decrement('article_num', array('category_id'=>$row->category_id));
			DBCategoryHelper::getConn()->decrement('article_num_check', array('category_id'=>$row->category_id));
			DBCategoryHelper::getConn()->decrement('article_num_in', array('category_id'=>$row->category_id));
			DBUserStatisticHelper::getConn()->decrement('article_num', array('user_id'=>$row->user_id));
			DBUserStatisticHelper::getConn()->decrement('article_num_in', array('user_id'=>$row->user_id));
			DBUserStatisticHelper::getConn()->decrement('article_num_check', array('user_id'=>$row->user_id));

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
		return $this->getArticle($fields, 'article_id IN(:id)', array('id'=>$ids), $offset, $length);
	}

	public function getArticleByIdNum($id){
		return DBArticleHelper::getConn()->where('user_id=:id',array('id'=>$id))->fetchCount();
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
		$articleList = $this->getArticleByIds($articleIds, '', 0, $length);

		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[]	= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c, article_num, follow_num', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$data = array();
		foreach($articleList as $v){
			$v->user = $userList[$v->user_id];
			$v->category= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;
			$data[] = $v;
		}

		return $data;
	}

	/*
	 * 分类文章
	 */
	public function getArticleByCid($categoryId, $offset, $length){
		$offset = (int)$offset;
		$length = (int)$length;
		if(empty($categoryId)){
			return array();
		}

		$categoryList = Category::getInstance()->getChildren($categoryId);
		$cids = array();
		foreach($categoryList as $v){
			$cids[] = $v->id;
		}
		$cids[] = $categoryId;

		$where = 'category_id In (:category_id) AND is_check=2';
		$whereParams['category_id'] = $cids;

		$articleList	= $this->getArticle('', $where, $whereParams, $offset, $length);

		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[]	= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c, article_num, follow_num', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$data = array();
		foreach($articleList as $v){
			$v->user = $userList[$v->user_id];
			$v->category= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;
			$data[] = $v;
		}

		return $data;
	}

	/*
	 *
	 */
	public function getArticleList($fields='', $offset,$length){
		$articleList = $this->getArticle($fields, 'is_check=2', array(), $offset,$length);
		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[]	= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c, article_num, follow_num', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$data = array();
		foreach($articleList as $v){
			$v->user = $userList[$v->user_id];
			$v->category= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;
			$data[] = $v;
		}

		return $data;

	}

	/*
	 * 封装公用文章数据
	 */
	public function getArticle($fields='', $where, $params,  $offset, $length){
		$fields == '' && $fields = 'article_id,title, category_id,user_id,description,description_pic,update_time, like_num,forward_num,comment_num,by_forward_user_id,forward_description';  // 不能换行，否则换行第一个元素找不到，原因暂时不明
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
		foreach($list as $v){
			$v =  (object)$v;
			isset($v-> description_pic) && $v-> description_pic = $v->description_pic!='' ? explode(',', $v->description_pic) : array();  // 缩略图
			isset($v-> description) && $v-> description		= Utilities::htmlStripTags($v->description);
			isset($v->content)		&& $v-> content			= Utilities::htmlSpDecode($v->content);
			isset($v->update_time) && $v->update_time		= Utilities::getTimeGapMsg($v->update_time);

			if(strpos($fields, 'like_num') && !isset($v->like_num)){
				$v->like_num = 0;
			}
			if(strpos($fields, 'forward_num') && !isset($v->forward_num)){
				$v->forward_num = 0;
			}
			if(strpos($fields, 'comment_num') && !isset($v->comment_num)){
				$v->comment_num = 0;
			}
			$v->is_like	= 0;
			$result[] = $v;
		}
		return $result;
	}


	/*
	 * 按统计排序文章
	 * statistic 表的字段排序
	 */
	public function getArticleOrderBySt($fields='', $where, $params,  $offset, $length, $order=''){
		$fields == '' && $fields = 'article_id,title, category_id,user_id,description,description_pic,like_num,forward_num,comment_num';

		$pkId				= 'article_id';
		$arrFields			= explode(',', str_replace(' ', '', $fields));
		$itemFields			= explode(',', str_replace(' ', '', DBArticleHelper::_FIELDS_));
		$itemDetailFields	= explode(',', str_replace(' ', '', DBArticleDetailHelper::_FIELDS_));
		$itemStatisticFields= explode(',', str_replace(' ', '', DBArticleStatisticHelper::_FIELDS_));
		$itemFields			= array_intersect($itemFields, $arrFields);
		$itemDetailFields	= array_intersect($itemDetailFields, $arrFields);
		$itemStatisticFields= array_intersect($itemStatisticFields, $arrFields);
		$data				= array();


		// 首表数据
		if( !empty($itemStatisticFields)){
			$data[] = DBArticleStatisticHelper::getConn()->field($pkId . ',' . implode(',', $itemStatisticFields) )->where($where.' AND is_check!=1 AND is_delete=0', $params)->order($order)->limit($offset, $length)->fetchArrAll();
		}
		if(empty($data[0])){
			return array();
		}
		$ids = array();
		foreach($data[0] as $v){
			$ids[] = $v[$pkId];
		}
		if( !empty($itemFields)) {
			$data[] = DBArticleHelper::getConn()->field( $pkId.','. implode(',', $itemFields) )->where($pkId.' IN(:id) ', array('id'=>$ids))->fetchArrAll();
		}

		if( !empty($itemDetailFields)){
			$data[] = DBArticleDetailHelper::getConn()->field($pkId . ',' . implode(',', $itemDetailFields) )->where($pkId.' IN(:id) ', array('id'=>$ids))->fetchArrAll();
		}
		$list = array();
		// 合并数组
		foreach($data as $vFieldList){
			foreach($vFieldList as $k=>$v){
				$list[$v[$pkId]] = isset($list[$v[$pkId]]) ? $list[$v[$pkId]] + $v : $v;
			}
		}

		// 转换为对象模式
		$result = array();
		foreach($list as &$v){
			$v =  (object)$v;
			$v->description_pic = explode(',', $v->description_pic);  // 缩略图
			if(strpos($fields, 'like_num') && !isset($v->like_num)){
				$v->like_num = 0;
			}
			if(strpos($fields, 'forward_num') && !isset($v->forward_num)){
				$v->forward_num = 0;
			}
			if(strpos($fields, 'comment_num') && !isset($v->comment_num)){
				$v->comment_num = 0;
			}
			$result[] = $v;
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
		isset($row->update_time) && $row->update_time = Utilities::getTimeGapMsg($row->update_time);
		isset($row->content)	&& $row-> content			= Utilities::htmlSpDecode($row->content);

		return $row;
	}


	/*
	 * 按标签获取热门文章
	 * 随机
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

		$articleList	= $this->getArticleByIds($articleIds, '', 0, $length);
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[]		= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$data = array();
		foreach($articleList as $v){
			$v->user	= $userList[$v->user_id];
			$v->category= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;
			$data[] = $v;
		}
		return $data;
	}

	/*
	 * 广场热门标签
	 */
	public function getHotTag(){
		return DBHotTagHelper::getConn()->field('distinct name')->fetchCol();
	}

	/*
	 * 热门文章
	 */
	public function getHotArticle($categoryId=0, $topicId=0){
		$offset = 0;
		$length = 8;
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
		$articleList	= $this->getArticleByIds($articleIds, '', 0, $length);
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[]		= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$data = array();
		foreach($articleList as $v){
			$v->user	= $userList[$v->user_id];
			$v->category= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;
			$data[] = $v;
		}

		return $data;
	}

	public function getNewArticleByIds($articleIds,$fields,$offset,$length){
		return DBArticleHelper::getConn()->field('*')->where('article_id IN (:aid)',array('aid'=>$articleIds))->order('create_time DESC')->limit(0,$length)->fetchAll();
	}

	public function getAllArticleByTopic(){
		$userIds = array();
		$arNum = array();
		$articleIds = DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id =34 OR topic_id = 35 ',array())->fetchCol();
		$strArticleIds = implode(',',$articleIds);
		$sql = "
			SELECT count(*) AS num , user_id
			FROM `beauty_article`
			WHERE `article_id`
			IN ( {$strArticleIds} )
			GROUP BY user_id
			ORDER BY num DESC
			LIMIT 0,4
			";
		$usertotal = DBArticleHelper::getConn()->fetchAll($sql,array());
		if(empty($usertotal)){
			return array();
		}
		foreach($usertotal as $v){
			$userIds[] = $v->user_id;
			$arNum[$v->user_id] = $v->num;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c ,description', 0, 4);
		foreach($userList as $v){
			$v->article_num = $arNum[$v->user_id];
		}
		return $userList;
	}

	public function getMostArticeByTopic($fields,$length){
		$userIds = array();
		$articleIds		= DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id = 34 OR topic_id = 35', array())->fetchCol();
		$num = DBArticleStatisticHelper::getConn()->field('article_id')->where('article_id IN (:article_id)',array('article_id'=>$articleIds))->order($fields.' DESC')->limit(0,10)->fetchCol();
		$articleList = $this->getArticleByIds($num,'',0,$length);
		if(!$articleList){
			return array();
		}
		$arUserIds = array();
		foreach($articleList as $v){
			$arUserIds[] = $v->user_id;
		}
		$userList = Userinfo::getInstance()->getUserByIds($arUserIds,'user_id,realname,avatar_c',0,$length);
		foreach($articleList as $v){
			$v->user = $userList[$v->user_id];
		}
		return $articleList;
	}

	/*
	 * 话题文章
	 */
	public function getArticeByTopic($offset, $length, $isNew=false, $loginUserId=0){
		// article id
		$articleIds_for		= DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id = 34', array())->fetchCol();
		$articleIds		= DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id = 34 OR topic_id = 35', array())->fetchCol();
		if(empty($articleIds)){
			return array();
		}

		if($isNew){
			$articleIds		= DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id = 34 OR topic_id = 35', array())->order('id DESC')->limit(0, $length)->fetchCol();
			$articleList	= $this->getNewArticleByIds($articleIds, '', 0, $length);
			$likeList		= DBLikeHelper::getConn()->field('article_id')->where('article_id IN (:articleIds)',array('articleIds'=>$articleIds))->fetchCol();
		}else{
			if(count($articleIds)>15){
				$articleIds = Utilities::array_random($articleIds,$length);
			}
			$likeList		= DBLikeHelper::getConn()->field('article_id')->where('article_id IN (:articleIds) AND user_id=:user_id',array('articleIds'=>$articleIds, 'user_id'=>$loginUserId))->fetchCol();
			$articleList	= $this->getArticleByIds($articleIds, 'article_id,title,title_pic,category_id,user_id', 0, $length);
		}
		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[]	= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c,description', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$com_like_num = DBArticleStatisticHelper::getConn()->field('article_id,forward_num,like_num,comment_num')->where('article_id in (:article_id)',array('article_id'=>$articleIds))->fetchAll();
		$data = array();
		foreach($articleList as $v){
			$v->user = $userList[$v->user_id];
			$v->category= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;
			$data[] = $v;
		}
		$com_like_info = array();
		foreach($com_like_num as $v){
			$com_like_info[$v->article_id] = array('forward_num'=>$v->forward_num,'comment_num'=>$v->comment_num,'like_num'=>$v->like_num);
		}

		foreach($data as $v){
			if(in_array($v->article_id,$articleIds_for)){
				$v->topic = 2;
			}else{
				$v->topic = 1;
			}
			if(in_array($v->article_id,$likeList)){
				$v->is_like = 1;
			}else{
				$v->is_like = 0;
			}
			$v->comm_like_info = $com_like_info[$v->article_id];
		}

		return $data;
	}

	/*
	 * 关注的人的文章
	 */
	public function getFollowArticleList($userId, $offset, $length){
		// follow user
		$userIds = Follow::getInstance()->getFollowIds($userId, 0, $length);
		$userIds[] = $userId; // 同时显示自己发布的文章

		$articleList = $this->getArticle('', 'user_id IN(:id)', array('id'=>$userIds), $offset, $length);
		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		$articleIds	= array();
		foreach($articleList as $v){
			$userIds[]		= $v->user_id;
			$categoryIds[]	= $v->category_id;
			$articleIds[]   = $v->article_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, '', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$topicList		= Topic::getInstance()->getTopicListByAids($articleIds, 'topic_id,title');
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
   

	public function getFollowArticleNum($userId){
		$userIds = Follow::getInstance()->getFollowIds($userId, 0, 1000);
		return DBArticleHelper::getConn()->where('user_id IN(:id)', array('id'=>$userIds))->fetchCount();
	}
	/*
	 * 当前登录用户发布的文章
	 */
	public function getArticleListByLoginUid($userId, $offset, $length){
		//$articleList = $this->getArticle('', 'user_id =:id OR by_forward_user_id =:forward_user_id', array('id'=>$userId,'forward_user_id'=>$userId), $offset, $length);
		$articleList = $this->getArticle('', 'user_id =:id', array('id'=>$userId), $offset, $length);
		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		$articleIds	= array();
		foreach($articleList as $v){
			$userIds[]		= $v->user_id;
			$categoryIds[]	= $v->category_id;
			$articleIds[]   = $v->article_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c', 0, $length);
		$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$topicList		= Topic::getInstance()->getTopicListByAids($articleIds, 'topic_id,title');
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

	/*
	 * 某个用户发布的文章
	 */
	public function getArticleListByUid($userId, $offset, $length){
		return $this->getArticleListByLoginUid($userId, $offset, $length);
	}

	public function getArticleListByUIds($userIds){
		return $this->getArticle('','user_id IN(:uid)',array('uid'=>$userIds),0,4);
	}


	public function getArticleListByLoginUidNum($userId){
		return DBArticleHelper::getConn()->where('user_id=:id',array('id'=>$userId))->fetchCount();
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

	public function getHotUserTagList(){
		$num = 6;
		$houUserTag =  DBHotUserTagHelper::getConn()->fetchAll();
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
}
