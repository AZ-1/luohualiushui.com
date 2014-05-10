<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBArticleHelper;
use Gate\Package\Helper\DBHotUserArticleHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\Helper\DBArticleDetailHelper;
use Gate\Package\Helper\DBCategoryHelper;
use Gate\Package\Helper\DBTagHelper;
use Gate\Package\Helper\DBTagArticleHelper;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Category;
use Gate\Package\Helper\DBTopicArticleHelper;
use Gate\Package\Helper\DBTopicHelper;
use Gate\Package\Helper\DBHotArticleHelper;
use Gate\Package\Helper\DBHotCategoryHelper;
use Gate\Package\Helper\DBHotUserCategoryHelper;
use Gate\Package\Helper\DBHotUserTagHelper;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Libs\Utilities;

class Article{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	 *添加推荐达人分类
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$name  string
	 */
	
	public function addHotUserCategory($name){
		return DBHotUserCategoryHelper::getConn()->insert(array('name'=>$name));
	}

	/*
	 *推荐达人分类总数
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$name string	
	 */
	
	public function getHotUserCategoryNum($name){
		$where = '1=1';
		$params = array();
		if($name != ''){
			$where					.= 'name =:name';
			$params['name']			 = $name;
		}
		return DBHotUserCategoryHelper::getConn()->where($where,$params)->fetchCount();
	}

	/*
	 *通过ID获得推荐达人分类
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$id  分类ID
	 */
	public function getHotUserCategoryById($id){
		return DBHotUserCategoryHelper::getConn()->where('id=:id',array('id'=>$id))->limit(1)->fetch();
	}

	/*
	 *推荐达人分类列表
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		
	 */
	public function getHotUserCategory($name,$offset,$length){
		$where = '1=1';
		$params = array();
		if($name != ''){
			$where					.= 'name =:name';
			$params['name']			 = $name;
		}
		return DBHotUserCategoryHelper::getConn()->where($where,$params)->limit($offset,$length)->order('sort DESC')->fetchAll();
	}

	/*
	 * 推荐达人分类排序
	 * @author wuhui
	 * @time	2014/1/09
	 */
	public function editCategorySort($id , $sort){
		return DBHotUserCategoryHelper::getConn()->update(array('sort'=>$sort),'id=:id',array('id'=>$id));
	}



	/*
	 *推荐达人分类所有
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		
	 */
	public function getHotUserCategoryAll(){
		return DBHotUserCategoryHelper::getConn()->order('sort DESC')->fetchAll();
	}



	/*
	 *修改推荐达人分类
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$id推荐达人分类ID      $name
	 */
	public function upHotUserCategory($id,$name){
		return DBHotUserCategoryHelper::getConn()->update(array('name'=>$name),'id=:id',array('id'=>$id));
	}

	/*
	 *删除推荐达人分类
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$id推荐达人分类ID
	 */
	public function delHotUserCategory($id){
		return DBHotUserCategoryHelper::getConn()->delete('id=:id',array('id'=>$id));
	}

	
	/*
	 *添加推荐达人标签
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$name  string $categoryId  int
	 */
	
	public function addHotUserTag($name,$categoryId){
		return DBHotUserTagHelper::getConn()->insert(array('name'=>$name,'hot_user_category_id'=>$categoryId));
	}


	/*
	 *通过ID获得推荐达人标签
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$id  标签ID
	 */
	public function getHotUserTagById($id){
		return DBHotUserTagHelper::getConn()->where('tag_id=:id',array('id'=>$id))->limit(1)->fetch();
	}


	/*
	 *推荐达人标签列表
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		
	 */
	public function getHotUserTag($name,$offset,$length){
		$where = '1=1';
		$params = array();
		if($name != ''){
			$where					.= 'name =:name';
			$params['name']			 = $name;
		}
		$tagList =  DBHotUserTagHelper::getConn()->where($where,$params)->limit($offset,$length)->fetchAll();
		$categoryIds = array();
		foreach($tagList as $tl){
			$categoryIds[] = $tl->hot_user_category_id;
		}
		$categoryList = DBHotUserCategoryHelper::getConn()->where('id IN(:id)',array('id'=>$categoryIds))->fetchAll();
		foreach($tagList as $tl){
			foreach($categoryList as $cl){
				if($cl->id == $tl->hot_user_category_id){
					$tl->categoryName = $cl->name;
				}
			}
		}
		return $tagList;
	}
	

	/*
	 *推荐达人标签所有
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		
	 */
	public function getHotUserTagAll(){
		return DBHotUserTagHelper::getConn()->fetchAll();
	}


	/*
	 *推荐达人标签总数
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$name string
	 */
	public function getHotUserTagNum($name){
		$where = '1=1';
		$params = array();
		if($name != ''){
			$where					.= 'name =:name';
			$params['name']			 = $name;
		}
		return DBHotUserTagHelper::getConn()->where($where,$params)->fetchCount();
	}


	/*
	 *修改推荐达人标签
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$id推荐达人标签ID      $name
	 */
	public function upHotUserTag($id,$name,$categoryId){
		return DBHotUserTagHelper::getConn()->update(array('name'=>$name,'hot_user_category_id'=>$categoryId),'tag_id=:id',array('id'=>$id));
	}

	/*
	 *删除推荐达人标签
	 * @author		wuhui
	 * @time		2013/12/18
	 * @params		$id推荐达人标签ID
	 */
	public function delHotUserTag($id){
		return DBHotUserTagHelper::getConn()->delete('tag_id=:id',array('id'=>$id));
	}








	/*
	 *添加推荐达人文章
	 * @author		wuhui
	 * @time		2013/12/17
	 * @params		$articleIds array ,$name  string
	 */
	public function addUserArticle($articleIds,$hotUserCategoryId,$hotUserTagId){
		$status		= array('status'=>300,'message'=>'添加失败');
		$errorId	= '';
		foreach($articleIds as $a=>$v){
			$isAid	= DBArticleHelper::getConn()->where('article_id=:id',array('id'=>$v))->fetch();
			$isUAid	= DBHotUserArticleHelper::getConn()->where('article_id=:id',array('id'=>$v))->fetch();
			if($isAid && !$isUAid){
				$isInsert =  DBHotUserArticleHelper::getConn()->insert(array('article_id'=>$v,'hot_user_category_id'=>$hotUserCategoryId,'hot_user_tag_id'=>$hotUserTagId));
				if(!$isInsert){
					$errorId .= $v.' ';
				}
			}else{
				$errorId .= $v.' ';
			}
		}
		if($errorId == ''){
			$status['status']	= 200;
			$status['message']	= '添加成功';
			return $status;
		}else{
			$status['status']	= 300;
			$status['message']	= $errorId."添加失败";
			return $status;
		}
	}

	/*
	 *删除推荐达人文章
	 * @author			wuhui
	 * @time			2013/12/17
	 * @params			$id   User_article的id
	 */
	public function delUserArticle($id){
		return DBHotUserArticleHelper::getConn()->delete('id=:id',array('id'=>$id));
	}

	/*
	 *修改推荐达人文章
	 * @author		wuhui
	 * @time		2013/12/17
	 * @params		$id  User_article的id   $data array(['article_id'=>'xxx'][,'name'=>'xxx'])
	 */
	public function upUserArticle($id,$data){
		return DBHotUserArticleHelper::getConn()->update($data,'id=:id',array('id'=>$id));
	}

	/*
	 *推荐达人文章列表
	 * @authori			wuhui
	 * @time			2013/12/17
	 * @params			$name 推荐达人等级名称  $offset $length 分页	
	 */

	public function getUserArticleList($name,$offset,$length){
		$where = '1=1';
		$params = array();
		if($name){
			$where			.= ' AND name LIKE :name';
			$params['name'] = '%'.$name.'%';
		}
		$categoryId				= DBHotUserCategoryHelper::getConn()->field('id')->where($where,$params)->fetchAll();
		if($categoryId){
			foreach($categoryId as $ci){
			$categoryIds[] = $ci->id;
			}
			$hotUserArticle			= DBHotUserArticleHelper::getConn()->where('hot_user_category_id IN(:ids)' , array('ids'=>$categoryIds))->limit($offset,$length)->fetchAll();
		}else{
			$hotUserArticle			= DBHotUserArticleHelper::getConn()->limit($offset,$length)->fetchAll();
		}



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
		return $hotUserArticle;
	}

	/*
	 *得到推荐达人文章数
	 * @author			wuhui
	 * @time			2013/12/17
	 * @params			$name 推荐达人等级名称  	
	 */
	public function getUserArticleListNum($name){
		$where = '1=1';
		$params = array();
		if($name){
			$where			.= ' AND name LIKE :name';
			$params['name'] = '%'.$name.'%';
		}
		$categoryId				= DBHotUserCategoryHelper::getConn()->field('id')->where($where,$params)->fetchAll();
		if($categoryId){
			foreach($categoryId as $ci){
			$categoryIds[] = $ci->id;
			}
			return DBHotUserArticleHelper::getConn()->where('hot_user_category_id IN(:ids)' , array('ids'=>$categoryIds))->fetchCount();
		}else{
			return DBHotUserArticleHelper::getConn()->limit($offset,$length)->fetchAll();
		}

	}
	
	/*
	 *获取推荐达人文章的所有名字
	 *@author		wuhui
	 * @time		2013/12/17
	 */
	public function getUserArticleName(){
		return DBHotUserArticleHelper::getConn()->field('distinct name')->fetchAll();
	}



	/*
	 *Recycle回收站  更新文章is_delete为0
	 * @params		$id  文章ID
	 * @author		wuhui
	 * @time		2013/12/17
	 */	
	public function recycle($id){
		$row = DBArticleHelper::getConn()->field('category_id,user_id')->where('article_id=:article_id', array('article_id'=>$id))->fetch();
		$isD = DBArticleHelper::getConn()->update(array('is_delete'=>0),'article_id=:id',array('id'=>$id));
		if($isD){
			DBCategoryHelper::getConn()->increment('article_num', array('category_id'=>$row->category_id));
			DBUserStatisticHelper::getConn()->increment('article_num', array('user_id'=>$row->user_id));
		}
		return $isD;


	}

	/*
	 *添加热门文章
	 *array $data
	 */
	public function addHotArticle($data){
		return DBHotArticleHelper::getConn()->insert($data);
	}

	
	/*
	 *更新文章标签
	 *$id 文章id
	 *$tag array
	 */
	public function upTagArticle($id,$tag){
		DBTagArticleHelper::getConn()->delete('article_id=:id',array('id'=>$id));
		foreach($tag as $t=>$v){
			if(!DBTagArticleHelper::getConn()->insert(array('tag_id'=>$v,'article_id'=>$id))){
				return false;
			}
		}
		return true;
	}

	private function getDescription($content){
		$description = Utilities::htmlStripTags($content);
		$description = mb_substr($description, 0,140);
		return $description;
	}

	/*
	 *编辑文章
	 */
	public function upArticle($id,$title,$content,$category_id, $description_pic=''){
		$data = array('title'=>$title,'category_id'=>$category_id);
		$data['description']	= $this->getDescription($content);

		if($description_pic!=''){
			$data['description_pic'] = $description_pic;
		}
		$row = DBArticleHelper::getConn()->field('category_id')->where('article_id=:article_id', array('article_id'=>$id))->fetch();
		$isUp = DBArticleHelper::getConn()->update($data,"article_id=:id",array('id'=>$id));
		// 分类是否更改
		if($category_id!=$row->category_id){
			// 原来的减量
			DBCategoryHelper::getConn()->decrement(array('article_num','article_num_check'), array('id'=>$row->category_id));
			// 更改的增量
			DBCategoryHelper::getConn()->increment(array('article_num','article_num_check'),  array('id'=>$category_id));
		}

		if($isUp){
			$isUp = DBArticleDetailHelper::getConn()->update(array('content'=>$content),'article_id=:id',array('id'=>$id));
			if($isUp){
				return true;
			}
		}
		return false;
	}
/*
 * 
 */
	public function getTagTotalNum(){
		return DBTagHelper::getConn()->fetchCount();
	}
	public function getArticleTotalNum($search){
		$where = '1=1 AND forward_article_id=0';
		$params = array();
		//根据标签搜索
		if(isset($search['tag']) && $search['tag']!=0){
			$tagArticleIds  = DBTagArticleHelper::getConn()->field('article_id')->where('tag_id =:tag_id',array('tag_id'=>$search['tag']))->fetchCol();
			$where .= ' AND article_id IN(:article_id)';
			$params['article_id'] = $tagArticleIds;
		}

		//搜索文章ID
		if(isset($search['article_id']) && $search['article_id'] != ''){
			$where .= ' AND article_id =:article_id';
			$params['article_id'] = $search['article_id'];
		}
		if(isset($search['title']) && $search['title'] != ''){
			$where .= ' AND title LIKE :title';
			$params['title'] = '%'.$search['title'] . '%';
		}

		if(isset($search['realname']) && $search['realname'] != ''){
			$keyword = $search['realname'];
			$userIds =DBUserProfileHelper::getConn()->field('user_id')->where('realname like :realname', array('realname'=>"%$keyword%"))->fetchCol();
			$where .= ' AND user_id IN (:user_id)';
			$params['user_id'] = $userIds;
		}


		if(isset($search['start_time'])&& $search['start_time']!='' && isset($search['end_time']) && $search['end_time']!='' )
		{
			$start_time = $search['start_time'];
			$end_time = $search['end_time'];
			$where .=' AND create_time >:start_time AND create_time<:end_time ';
			$params['start_time'] = $start_time;
			$params['end_time'] = $end_time;
		}

		//搜索文章被删除  这个主要用来做回收站
		if(isset($search['is_delete']) && $search['is_delete'] !== ''){
			$where .= ' AND is_delete =:is_delete';
			$params['is_delete'] = $search['is_delete'];
		}

		if(isset($search['quality']) && $search['quality'] != 0){
			$keyword = $search['quality'];
			$where  .= ' AND quality =:quality';
			$params['quality'] = $keyword;
		}

		if(isset($search['is_check'])){
			$keyword = $search['is_check'];
			$where  .= ' AND is_check =:is_check';
			$params['is_check'] = $keyword;
		}

		if(isset($search['category']) && $search['category'] != 0){
			$keyword = $search['category'];
			$where  .= ' AND category_id =:category';
			$params['category'] = $keyword;
		}
		return DBArticleHelper::getConn()->where($where,$params)->fetchCount();
	}
	public function upCheck($id,$quality){
		$row = DBArticleHelper::getConn()->field('user_id, category_id, is_check, quality')->where('article_id=:id',array('id'=>$id))->fetch();
		if($row->quality==$quality){
			return true;
		}
		$is = DBArticleHelper::getConn()->update(array('is_check'=>2,'quality'=>$quality, 'no_pass_reason'=>''),'article_id=:id',array('id'=>$id));
		if($is && $row->is_check!=2){
			DBCategoryHelper::getConn()->increment('article_num_in', array('id'=>$row->category_id));
			DBUserStatisticHelper::getConn()->increment('article_num_in', array('user_id'=>$row->user_id));
			// 曾经审核未通过
			if($row->is_check==1){
				DBCategoryHelper::getConn()->increment('article_num_check', array('id'=>$row->category_id));
				DBUserStatisticHelper::getConn()->increment('article_num_check', array('user_id'=>$row->user_id));
			}
		}
	}

	public function upReason($id,$reason){
		$row = DBArticleHelper::getConn()->field('user_id, category_id, is_check')->where('article_id=:id',array('id'=>$id))->fetch();
		if($row->is_check==1){
			return true;
		}
		$is = DBArticleHelper::getConn()->update(array('is_check'=>1,'no_pass_reason'=>$reason),'article_id=:id',array('id'=>$id));
		if($is){
			DBCategoryHelper::getConn()->decrement('article_num_check', array('id'=>$row->category_id));
			DBUserStatisticHelper::getConn()->decrement('article_num_check', array('user_id'=>$row->user_id));
			// 曾经审核通过
			if($row->is_check==2){
				DBCategoryHelper::getConn()->decrement('article_num_in', array('id'=>$row->category_id));
				DBUserStatisticHelper::getConn()->decrement('article_num_in', array('user_id'=>$row->user_id));
			}
		}
	}

	/*
	 * 删除单个文章
	 */
	public function delArticle($articleId){
		$row = DBArticleHelper::getConn()->field('category_id,user_id,is_check,is_delete')->where('article_id=:article_id', array('article_id'=>$articleId))->fetch();
		if($row->is_delete==1){
			return false;
		}
		$userId = $row->user_id;
		$isD = DBArticleHelper::getConn()->update(array('is_delete'=>1), 'article_id=:article_id AND user_id=:user_id', array('article_id'=>$articleId,'user_id'=>$userId));
		if($isD){
			// ----- 喜欢 
			//$userIds = DBLikeHelper::getConn()->field('user_id')->where('article_id=:aid',array('aid'=>$articleId))->fetchCol();
			//DBLikeHelper::getConn()->delete('article_id=:aid',array('aid'=>$articleId));
			// ----- 分类，用户的文章统计
			$decrementArticle = array('article_num','article_num_check');
			if($row->is_check==2){
				$decrementArticle[] = 'article_num_in';
			}
			DBCategoryHelper::getConn()->decrement($decrementArticle, array('id'=>$row->category_id));
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
			//-------推荐达人文章列表
			$res = DBHotUserArticleHelper::getConn()->delete('article_id=:id',array('id'=>$articleId));
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

	/*
	 * 分类文章
	 */
	public function getArticleByCid($categoryId, $offset, $length){
		if(empty($categoryId)){
			return array();
		}
		$articleList	= $this->getArticle('', 'category_id IN(:id)', array('id'=>$categoryId), $offset, $length);
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[$v->user_id]	= $v->user_id;
			$categoryIds[$v->category_id]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c', 0, $length);
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
	 * 回收站
	 */
	public function getRecycleList($fields='', $where, $params,  $offset, $length){
		$fields == '' && $fields = 'user_id,article_id,title,category_id,description,is_check,create_time,no_pass_reason,quality,description_pic';

		$pkId				= 'article_id';
		$arrFields			= explode(',', str_replace(' ', '', $fields));
		$itemFields			= explode(',', str_replace(' ', '', DBArticleHelper::_FIELDS_));
		$itemStatisticFields= explode(',', str_replace(' ', '', DBArticleStatisticHelper::_FIELDS_));
		$itemFields			= array_intersect($itemFields, $arrFields);
		$itemStatisticFields= array_intersect($itemStatisticFields, $arrFields);
		$data				= array();
		// 首表数据
		if( !empty($itemFields)) {
			$data[] = DBArticleHelper::getConn()->field( $pkId.','. implode(',', $itemFields) )->where($where.' AND is_delete=1', $params)->order($pkId .' desc')->limit($offset, $length)->fetchArrAll();
		}
		if(empty($data[0]) || empty($data)){
			return array();
		}
		$ids = array();
		foreach($data[0] as $v){
			$ids[] = $v[$pkId];
		}

		if( !empty($itemStatisticFields)){
			$data[] = DBArticleStatisticHelper::getConn()->field($pkId . ',' . implode(',', $itemStatisticFields) )->where($pkId.' IN(:id) ', array('id'=>$ids))->order($pkId .' desc')->fetchArrAll();
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
			isset($v-> description) && $v-> description_pic = explode(',', $v->description_pic);  // 缩略图
			isset($v-> description) && $v-> description		= Utilities::htmlStripTags($v->description);
			isset($v->content)		&& $v-> content			= Utilities::htmlSpDecode($v->content);
			isset($v->update_time) && $v->update_time		= Utilities::getTimeGapMsg(strtotime($v->update_time));

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
	 * 封装公用文章数据
	 */
	public function getArticle($fields='', $where, $params,  $offset, $length){
		$fields == '' && $fields = 'user_id,article_id,title,category_id,description,is_check,create_time,no_pass_reason,quality,description_pic,like_num,forward_num,comment_num,by_forward_user_id,forward_article_id,forward_description';
		if(strpos($where, 'is_delete')===false){
			$where .= ' AND is_delete=0';
		}

		$pkId				= 'article_id';
		$arrFields			= explode(',', str_replace(' ', '', $fields));
		$itemFields			= explode(',', str_replace(' ', '', DBArticleHelper::_FIELDS_));
		$itemStatisticFields= explode(',', str_replace(' ', '', DBArticleStatisticHelper::_FIELDS_));
		$itemFields			= array_intersect($itemFields, $arrFields);
		$itemStatisticFields= array_intersect($itemStatisticFields, $arrFields);
		$data				= array();
		// 首表数据
		if( !empty($itemFields)) {
			$data[] = DBArticleHelper::getConn()->field( $pkId.','. implode(',', $itemFields) )->where($where, $params)->order("create_time DESC")->limit($offset, $length)->fetchArrAll();
		}
		if(empty($data[0]) || empty($data)){
			return array();
		}
		$ids = array();
		foreach($data[0] as $v){
			$ids[] = $v[$pkId];
		}

		if( !empty($itemStatisticFields)){
			$data[] = DBArticleStatisticHelper::getConn()->field($pkId . ',' . implode(',', $itemStatisticFields) )->where($pkId.' IN(:id) ', array('id'=>$ids))->order($pkId .' desc')->fetchArrAll();
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
			isset($v-> description) && $v-> description_pic = explode(',', $v->description_pic);  // 缩略图
			isset($v-> description) && $v-> description		= Utilities::htmlStripTags($v->description);
			isset($v->content)		&& $v-> content			= Utilities::htmlSpDecode($v->content);
			isset($v->update_time) && $v->update_time		= Utilities::getTimeGapMsg(strtotime($v->update_time));

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
	

	public function getArticleTagById($articleId){
		 return DBTagArticleHelper::getConn()->field('article_id, tag_id')->where('article_id =:article_id', array('article_id'=>$articleId))->fetchAll();
	}
	/*
	 * 通过id 返回一条数据
	 */
	public function getArticleById($id, $fields,$is_delete){
		$pkId				= 'article_id';

		$arrFields			= explode(',', str_replace(' ', '', $fields));
		$itemFields			= explode(',', str_replace(' ', '', DBArticleHelper::_FIELDS_));
		$itemStatisticFields= explode(',', str_replace(' ', '', DBArticleStatisticHelper::_FIELDS_));
		$itemDetailFields= explode(',', str_replace(' ', '', DBArticleDetailHelper::_FIELDS_));
		$itemFields			= array_intersect($itemFields, $arrFields);
		$itemStatisticFields	= array_intersect($itemStatisticFields, $arrFields);
		$itemDetailFields = array_intersect($itemDetailFields,$arrFields);

		$row	= array();
		if( !empty($itemFields) ){ 
			// is_delete 在这里判断, 维护显示数据一致性
			$row = DBArticleHelper::getConn()->field($pkId.','.implode(',', $itemFields) )->where($pkId.'=:id AND is_delete=:is_delete', array('id'=>$id,'is_delete'=>$is_delete))->fetchArr();
			if( empty($row) ) {return false;}  
		}
		if( !empty($itemStatisticFields)) {
			$row += DBArticleDetailHelper::getConn()->field($pkId.','.implode(',', $itemDetailFields) )->where($pkId.'=:id', array('id'=>$id))->fetchArr();
		}
		
		if( !empty($itemStatisticFields)) {
			$row += DBArticleStatisticHelper::getConn()->field($pkId.','.implode(',', $itemStatisticFields) )->where($pkId.'=:id', array('id'=>$id))->fetchArr();
		}

		$row = (object)$row;
		isset($row->update_time) && $row->update_time = Utilities::getTimeGapMsg(strtotime($row->update_time));
		isset($row->content) && $row-> content = Utilities::htmlSpDecode($row->content);
		return $row;
	}

	public function getHotArticleTotalNum(){
		return DBHotArticleHelper::getConn()->fetchCount();
	}
	/*
	 * 热门文章
	 */
	public function getHotArticleList($categoryId=0, $topicId=0, $offset=0, $length=30){
		// $categoryId 改为 hot_category 的 id, 直接读取article id，
		if($categoryId){
			$where = ' hot_category_id In (:hot_category_id)';
			$whereParams['hot_category_id'] = $categoryId;

		}else{
			// 话题对应的分类
			if($categoryId==0 && $topicId!=0){
				$tcRow = DBTopicCategoryHelper::getConn()->field('category_id')->where('topic_id=:topic_id', array('topic_id'=>$topicId))->limit(1)->fetch();
				$categoryId = $tcRow->category_id;
			}

			$where		 = '1=1';
			$whereParams = array();
			if($categoryId){
				$where .= ' AND category_id In (:category_id)';
				$whereParams['category_id'] = $categoryId;
			}
		}

		$hotArticleList		= DBHotArticleHelper::getConn()->field('article_id, hot_category_id')->where($where, $whereParams)->limit($offset, $length)->fetchAssocAll();
		$articleIds			= array_keys($hotArticleList);
		
		if(empty($articleIds)){
			return array();
		}
		$articleList		= $this->getArticleByIds($articleIds, '', $offset, $length);
		$userIds			= array();
		$categoryIds		= array();
		foreach($articleList as $v){
			$userIds[]		= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList			= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c', 0, $length);
		$categoryList		= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		// 广场热门文章的分类
		$hotCategoryList	= DBHotCategoryHelper::getConn()->field('id, name')->fetchAssocAll();
		
		$data = array();
		foreach($articleList as $v){
			$v->user		= $userList[$v->user_id];
			$v->category	= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;

			$hotCategoryId		 = $hotArticleList[$v->article_id]->hot_category_id;
			$v->hot_category     = $hotCategoryList[$hotCategoryId];
			$v->hot_category->id = $hotCategoryId;

			$data[] = $v;
		}

		return $data;
	}
	public function getHotArticle($id){
		$article = DBHotArticleHelper::getConn()->field('id,article_id,hot_category_id')->where('article_id=:id',array('id'=>$id))->fetch();
		$article->category = DBHotCategoryHelper::getConn()->field('id,name')->where('id=:id',array('id'=>$article->hot_category_id))->fetch();
		$article->article = DBArticleHelper::getConn()->field('article_id,title,description')->where('article_id=:id',array('id'=>$article->id))->fetch();
		return $article;
	}
	public function getHotCategoryList(){
		return DBHotCategoryHelper::getConn()->field('id,name')->fetchAll();
	}
	public function updateHotArticle($id,$hot_category_id){
		return DBHotArticleHelper::getConn()->update(array('hot_category_id'=>$hot_category_id),'id=:id',array('id'=>$id));
	}
	public function updateArticle($id){
		if(!empty($id)){
			$res = DBArticleHelper::getConn()->update(array('title'=>'china'), 'article_id=:article_id', array('article_id'=>$id));
		}
		return $res;
	}

  /****************文章---标签************************/

	public function getTagList($offset,$length,$fields="*"){
			 $res = DBTagHelper::getConn()->field('tag_id,name,category_id')->where('is_delete=:is_delete',array('is_delete'=>0))->order('tag_id DESC')->limit($offset,$length)->fetchAll();
			 $categoryid = array();
			 foreach($res as $v){
				$categoryid[$v->category_id] = $v->category_id;
			 }
			 $categoryList= Category::getInstance()->getCategoryList();
			 foreach($res as $k =>  $vt){                                                                     
				 foreach($categoryList  as $vc){
					 if($vt->category_id==$vc ->id){
						 $res[$k]->catename = $vc->name ;
					 }
					 if(isset($vc->child)){
						 foreach($vc->child as $vcc){
							 if($vt->category_id==$vcc ->id){
								 $res[$k]->catename = isset($res[$k]->catename) ? $vcc->name . '-' . $res[$k]->catename  : $vcc->name . '-' . $vc->name;
							 }
						 }
					 }
				 }
			 }
			 return $res;
	}

	/*
	 * 检查重复
	 */
	public function addTag($name,$cid){
		$row = DBTagHelper::getConn()->field('tag_id')->where('is_delete=0 AND name=:name AND category_id=:category_id', array('name'=>$name, 'category_id'=>$cid))->fetch();
		if($row){
			return false;
		}
		return DBTagHelper::getConn()->insert(array('name'=>$name,'category_id'=>$cid));
	}
	public function getTag($id){
		return DBTagHelper::getConn()->field('tag_id,name,category_id')->where('tag_id=:id',array('id'=>$id))->fetch();
	}

	/*
	 * 检查重复
	 */
	public function upTag($id,$name,$cid){
		$where = 'is_delete=0 AND name=:name AND category_id !=:category_id AND tag_id!=:tag_id';
		$params = array('name'=>$name, 'category_id'=>$cid, 'tag_id'=>$id);
		$row = DBTagHelper::getConn()->field('tag_id')->where($where, $params)->fetch();
		if($row){
			return false;
		}
		return DBTagHelper::getConn()->update(array('name'=>$name,'category_id'=>$cid),'tag_id=:id',array('id'=>$id));
	}
	public function delTag($id){
		return DBTagHelper::getConn()->update(array('is_delete'=>1),'tag_id=:id',array('id'=>$id));
	}
/********************所有文章*************************/
   //可显示分类的所有文章（N）
	public function getAllarticle($offset,$length){
			 $res = DBArticleHelper::getConn()->limit($offset,$length)->field('article_id,title,category_id,description,is_check,quality')->fetchAll();
			 $categoryid = array();
			 foreach($res as $v){
				$categoryid[$v->category_id] = $v->category_id;
			 }
		
			$result= Category::getInstance()->getCategorybyIds($categoryid);
			 foreach($res as $k =>  $vt){                                                                     
				 foreach($result  as $vc){
					 if($vt->category_id==$vc ->id){
						 $res[$k]->catename = $vc->name ;
		             }
				 }
			 }
			 return $res;
	}

	//条件查询文章
	public function articleWhere($id){
			$where="article_id=:aid";
			$params=array("aid"=>$id);
			$res=DBArticleHelper::getConn()->field('article_id,title')->where($where,$params)->fetch();
			return $res;
	}

	//添加文章到话题
	public function addTopicArticle($dataAll){
		 $newId = DBTopicArticleHelper::getConn()->insert($dataAll);
		 if($newId){
			 // 统计
			DBTopicHelper::getConn()->increment('article_num', array('topic_id'=>$data['topic_id']));
		 }
		return $newId;
	}
	public function PageTotal(){
		$resu = DBArticleHelper::getConn()->fetchCount();
		return $resu;
	}


	/*
	 *	根据不同条件来显示文章
	 *	$search  搜索添加  array
	 */
	public function getSearchArticle($search, $offset, $length){
		$where = '1=1 AND forward_article_id=0';
		$params = array();

		//搜索文章ID
		if(isset($search['article_id']) && $search['article_id'] != ''){
			$where .= ' AND article_id =:article_id';
			$params['article_id'] = $search['article_id'];
		}

		//搜索文章标题
		if(isset($search['title']) && $search['title'] != ''){
			$where .= ' AND title LIKE :title';
			$params['title'] = '%'.$search['title'] . '%';
		}

		//搜索文章被删除  这个主要用来做回收站
		if(isset($search['is_delete']) && $search['is_delete'] !== ''){
			$where .= ' AND is_delete =:is_delete';
			$params['is_delete'] = $search['is_delete'];
		}

		//根据名字搜索
		if(isset($search['realname']) && $search['realname'] != ''){
			$keyword = $search['realname'];
			$userIds =DBUserProfileHelper::getConn()->field('user_id')->where('realname like :realname', array('realname'=>"%$keyword%"))->fetchCol();
			$where .= ' AND user_id IN (:user_id)';
			$params['user_id'] = $userIds;
		}

		//根据质量搜索
		if(isset($search['quality']) && $search['quality'] != 0){
			$keyword = $search['quality'];
			$where  .= ' AND quality =:quality';
			$params['quality'] = $keyword;
		}

		//根据审核状态搜索
		if(isset($search['is_check'])){
			$keyword = $search['is_check'];
			$where  .= ' AND is_check =:is_check';
			$params['is_check'] = $keyword;
		}

		//根据分类搜索
		if(isset($search['category']) && $search['category'] != 0){
			$keyword = $search['category'];
			$where  .= ' AND category_id =:category';
			$params['category'] = $keyword;
		}

		//根据标签搜索
		if(isset($search['tag']) && $search['tag']!=0){
			$tagArticleIds  = DBTagArticleHelper::getConn()->field('article_id')->where('tag_id =:tag_id',array('tag_id'=>$search['tag']))->fetchCol();
			$where .= ' AND article_id IN(:article_ids)';
			$params['article_ids'] = $tagArticleIds;
		}
       
		//段时间内指定用户发布的文章
		if(isset($search['start_time'])&& $search['start_time']!='' && isset($search['end_time']) && $search['end_time']!='' )
		{
			$start_time = $search['start_time'];
			$end_time = $search['end_time'];
			$where .=' AND create_time >:start_time AND create_time<:end_time ';
			$params['start_time'] = $start_time;
			$params['end_time'] = $end_time;
		}
		// 文章数据
		$articleList =  $this->getArticle('', $where, $params, $offset, $length);
		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[$v->user_id]	= $v->user_id;
			$categoryIds[$v->category_id]	= $v->category_id;
			$articleIds[]   = $v->article_id;
		}

		$userList			= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c', 0, $length);
		//$categoryList	= DBCategoryHelper::getConn()->field('id, name')->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		$categoryList		= Category::getInstance()->getCategorybyIds($categoryIds);
		$topcategoryList	= Category::getInstance()->getTopCategoryList();
		$topicList			= Topic::getInstance()->getTopicListByAids($articleIds, 'topic_id,title');
		$tagName = '';
		$artTagNames = array();
		if(isset($search['tag']) && $search['tag']!=0){
			// tag 信息
			$tagArticle     = DBTagHelper::getConn()->where('tag_id =:tag_id',array('tag_id'=>$search['tag']))->fetch();
			// 统计文章的标签到一个数组
			$artTagNames = $tagArticle->name;

		}else{
			// tag 信息
			$tagArticleList = DBTagArticleHelper::getConn()->field('article_id, tag_id')->where('article_id IN(:article_id)', array('article_id'=>$articleIds))->fetchAll();
			$tagIds = array();
			foreach($tagArticleList as $v){
				$tagIds[] = $v->tag_id;
			}

			$tagArticle		= DBTagHelper::getConn()->where('tag_id IN (:tag_id)',array('tag_id'=>$tagIds))->fetchAssocAll();
			// 统计文章的标签到一个数组
			foreach($tagArticleList as $vta ){
				$artTagNames[$vta->article_id][] = $tagArticle[$vta->tag_id]->name;
			}
		}


		$data = array();
		foreach($articleList as $v){
			$v->user = $userList[$v->user_id];
			$v->topic	= isset($topicList[$v->article_id] ) ? $topicList[$v->article_id] : false;
			if(isset($categoryList[$v->category_id])){
				$v->category		= $categoryList[$v->category_id];
				$v->category->id	= $v->category_id;
				$v->top_category_name    = $v->category->pid>1 ?  $topcategoryList[$categoryList[$v->category_id]->pid]->name : '';
			}
			if(isset($search['tag']) && $search['tag']!=0){
				$v->tagName = $artTagNames;
			}else{
				$v->tagName = isset($artTagNames[$v->article_id]) ?  implode(',', $artTagNames[$v->article_id]) : '';
			}

			$data[] = $v;
		}
		$hotArticleId		= DBHotArticleHelper::getConn()->field('article_id')->fetchCol();
		$hotUserArticleId	= DBHotUserArticleHelper::getConn()->field('article_id')->fetchCol();
		foreach($data as $d){
			$d->hotArticle = 0;
			$d->hotUserArticle = 0;
			foreach($hotArticleId as $hai){
				if($hai == $d->article_id){
					$d->hotArticle = 1;
				}
			}
			foreach($hotUserArticleId as $huai){
				if($huai == $d->article_id){
					$d->hotUserArticle = 1;
				}
			}
		}		
		return $data;
	}

	public function editArticleById($articleId, $data=array(), $detailData=array()){
		if(!empty($data)){
			$row = DBArticleHelper::getConn()->field('category_id')->where('article_id=:article_id', array('article_id'=>$articleId))->fetch();
			DBArticleHelper::getConn()->update($data, 'article_id=:article_id', array('article_id'=>$articleId));
			// 分类是否更改
			if(isset($data['category_id']) && $data['category_id']!=$row->category_id){
				// 原来的减量
				DBCategoryHelper::getConn()->decrement(array('article_num','article_num_check'), array('id'=>$row->category_id));
				// 更改的增量
				DBCategoryHelper::getConn()->increment(array('article_num','article_num_check'), array('id'=>$data['category_id']));
			}
		}
		if(!empty($detailData)){
			DBArticleDetailHelper::getConn()->update($detailData, 'article_id=:article_id', array('article_id'=>$articleId));
		}
		return 1;
	}

}
