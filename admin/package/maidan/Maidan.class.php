<?php /*
* 
* @author
*/
namespace Gate\Package\Maidan;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\Helper\DBMaidanHelper;
use Gate\Package\Helper\DBHotArticleHelper;
use Gate\Package\Helper\DBCategoryHelper;
use Gate\Package\User\Userinfo;
use Gate\Package\Helper\DBAdBannerHelper;
use Gate\Package\Helper\DBTopicHelper;


class Maidan{
private static $instance;
public static function getInstance(){
	is_null(self::$instance) && self::$instance = new self(); 
	return self::$instance;
}


/*
 * array $data
 */
public function bannerAdd($data){
	if(!empty($data)){
		$res = DBAdBannerHelper::getConn()->insert($data);
	}
	return $res;
}



 
public function delArticle($id){
	$where="article_id=:aid";
	$params=array("aid"=>$id);
	$res=DBHotArticleHelper::getConn()->delete($where,$params);
	return $res;
}



public function hotArticle($fields="*"){
	
		$res= DBHotArticleHelper::getConn()->field('id,article_id')->fetchAll();
		pr($res);
}	

public function hotArticlewhere($id){
		$where="id=:aid";
		$params=array("aid"=>$id);
		$res= DBHotArticleHelper::getConn()->field('id,article_id,category_id')->where($where,$params)->fetch();
		return $res;
}	

public function addArticle($data){
	$res = DBHotArticleHelper::getConn()->insert($data);
   /*
	if($newId){
		DBHotArticleHelper::getConn()->increment('article_num', array('id'=>$data['category_id']));
	}*/
	return $res;
}

public function updateArticle($id,$idarticle,$tag){
	$data	= array("article_id"=>$idarticle,"tag"=>$tag);
	$where	= 'id=:gid';
	$params = array('gid'=>$id);
	$res=DBHotArticleHelper::getConn()->update($data, $where, $params); //如果需要指定某个表，第四个参数填写表名
	return $res;
}
/***************广场--焦点图********************/
public function focusImg($fields="*"){
		 $res= DBAdBannerHelper::getConn()->field('id,title,link_url,pic_url,file_path')->fetchAll();
		return $res;
}	

public function addFocus($data){
	$res = DBAdBannerHelper::getConn()->insert($data);
   /*
	if($newId){
		DBHotArticleHelper::getConn()->increment('article_num', array('id'=>$data['category_id']));
	}*/
	return $res;
}
public function delFocus($id){
	$where="id=:aid";
	$params=array("aid"=>$id);
	$res=DBAdBannerHelper::getConn()->delete($where,$params);
	return $res;
}

public function focusWhere($id){
		$where="id=:aid";
		$params=array("aid"=>$id);
		$res=DBAdBannerHelper::getConn()->field('id,title,link_url,pic_url,file_path')->where($where,$params)->fetch();
		return $res;
}	
public function updateFocus($id,$title,$link_url,$pic_url,$file_path){
	$data	= array("title"=>$title,"link_url"=>$link_url,"pic_url"=>$pic_url,"file_path"=>$file_path);
	$where	= 'id=:gid';
	$params = array('gid'=>$id);
	$res=DBAdBannerHelper::getConn()->update($data, $where, $params); //如果需要指定某个表，第四个参数填写表名
	return $res;
}
/******************广场--热门话题*************************/


	public function getTopic($id){
			 $res= DBTopicHelper::getConn()->field('topic_id,title,pic,description')->where('topic_id=:id',array('id'=>$id))->fetchAll();
			return $res;
	}

	public function getTopicList($offset, $length){
		$res= DBTopicHelper::getConn()->field('topic_id,title,pic,description,sort,is_delete')->order('is_delete ASC')->limit($offset ,$length)->fetchAll();
		return $res;
	}

	public function getSearchTopic($keyword){
		$res= DBTopicHelper::getConn()->field('topic_id,title,pic,description,sort,is_delete')->where('title like :title' , array('title'=>"%$keyword%"))->order('sort DESC')->fetchAll();
		return $res;
	}

	public function editTopicSort($topicId,$sort){
		return DBTopicHelper::getConn()->update(array('sort'=>$sort),'topic_id=:id',array('id'=>$topicId));
	}

	public function addTopic($data){
		$newId = DBTopicHelper::getConn()->insert($data);
		return $newId;
	}

	/*public function delTopic($id){
		$where="topic_id=:aid";
		$params=array("aid"=>$id);
		$res=DBTopicHelper::getConn()->delete($where,$params);
		return $res;
	}*/

	public function topicWhere($id){
			$where="topic_id=:aid";
			$params=array("aid"=>$id);
			$res=DBTopicHelper::getConn()->field('topic_id,title,pic,description')->where($where,$params)->fetch();
			return $res;
	}	
	public function delTopic($id){
		$data	= array("is_delete"=>1);
		$where	= 'topic_id=:gid';
		$params = array('gid'=>$id);
		$res=DBTopicHelper::getConn()->update($data, $where, $params); //如果需要指定某个表，第四个参数填写表名
		return $res;
	}

	public function offlineTopic($id){
		$data	= array("is_delete"=>1, 'sort'=>0);
		$where	= 'topic_id=:gid';
		$params = array('gid'=>$id);
		$res=DBTopicHelper::getConn()->update($data, $where, $params); //如果需要指定某个表，第四个参数填写表名
		return $res;
	}

	public function onlineTopic($id){
		$data	= array("is_delete"=>0);
		$where	= 'topic_id=:gid';
		$params = array('gid'=>$id);
		$res=DBTopicHelper::getConn()->update($data, $where, $params); //如果需要指定某个表，第四个参数填写表名
		return $res;
	}

	public function updateTopic($id,$title,$pic,$description){
		$data	= array("title"=>$title,"description"=>$description);
		if($pic!=''){  // 此处是图片上传，为空代表没上传图片，不用更改
			$data["pic"] = $pic;
		}
		$where	= 'topic_id=:gid';
		$params = array('gid'=>$id);
		$res=DBTopicHelper::getConn()->update($data, $where, $params); //如果需要指定某个表，第四个参数填写表名
		return $res;
	}



}
