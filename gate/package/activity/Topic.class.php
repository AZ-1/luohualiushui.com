<?php
/*
 * 话题
 * @author
 */
namespace Gate\Package\Activity;
use Gate\Package\Helper\DBTopicHelper;
use Gate\Package\Helper\DBTopicUserHelper;
use Gate\Package\Helper\DBTopicArticleHelper;
use Gate\Package\Helper\DBHotTopicHelper;
use Gate\Package\User\Userinfo;

class Topic{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }


	public function getHotTopic(){
		$topicIds = DBHotTopicHelper::getConn()->field('topic_id')->fetchCol();
		if(empty($topicIds)){
			return array();
		}
		return DBTopicHelper::getConn()->field('topic_id, title, pic,description, user_num,article_num')->where('topic_id IN(:topic_id)', array('topic_id'=>$topicIds))->fetchAll();
	}

	public function getTopicById($id, $fields='*'){
		return DBTopicHelper::getConn()->field($fields)->where('topic_id=:id',array('id'=>$id))->fetch();
	}
	public function getTopicByIds($ids,$fields='*'){
		return DBTopicHelper::getConn()->field($fields)->where('topic_id IN(:id) AND is_delete=0',array('id'=>$ids))->fetchAll();	
	}

	public function getTopicList($offset, $length){
		return DBTopicHelper::getConn()->where('is_delete=0', array())->limit($offset, $length)->order('topic_id DESC')->fetchAll();
	}

	public function getTopicNum(){
		return DBTopicHelper::getConn()->where('is_delete=0',array())->fetchCount();
	}
	
	public function getTopicArticleNum($topicId){
		return DBTopicArticleHelper::getConn()->where('topic_id=:id',array('id'=>$topicId))->fetchCount();
	}

	/*
	 * 关注话题的所有用户
	 */
	public function getTopicUser($topicId, $offset, $length){
		$userIds =  DBTopicUserHelper::getConn()->field('user_id')->where('topic_id=:topic_id', array('topic_id'=>$topicId))->limit($offset, $length)->order('id DESC')->fetchCol();
		if(empty($userIds)){
			return array();
		}
		return Userinfo::getInstance()->getUserByIds($userIds, '', 0, $length);	
	}

	/*
	 * 关注话题的达人
	 * 按时间倒叙排
	 */
	public function getTopicDaren($topicId, $offset, $length){
		$userIds = DBTopicUserHelper::getConn()->field('user_id')->where('topic_id=:topic_id AND user_grade=0', array('topic_id'=>$topicId))->limit($offset, $length)->order('id DESC')->fetchCol();
		if(empty($userIds)){
			return array();
		}
		return Userinfo::getInstance()->getUserByIds($userIds, '', 0, $length);	
	}

	/*
	 * 用户关注的话题
	 */
	public function getTopicByUid($userId, $offset, $length){
		$topicIds = DBTopicUserHelper::getConn()->field('topic_id')->where('user_id=:user_id', array('user_id'=>$userId))->fetchCol();
		if(empty($topicIds)){
			return array();
		}
		return DBTopicHelper::getConn()->where('is_delete=0 AND topic_id IN(:topic_id)', array('topic_id'=>$topicIds))->limit($offset, $length)->order('topic_id DESC')->fetchAll();
	}

	/*
	 * 文章的话题
	 */
	public function getTopicByAid($articleId, $fields='*'){
		$row = DBTopicArticleHelper::getConn()->field('topic_id')->where('article_id=:article_id', array('article_id'=>$articleId))->fetch();
		if(empty($row)){
			return false;
		}
		return DBTopicHelper::getConn()->field($fields)->where('is_delete=0 AND topic_id =:topic_id', array('topic_id'=>$row->topic_id))->limit(1)->fetch();
	}

	/*
	 * 文章的话题
	 */
	public function getTopicListByAids($articleIds, $fields='*'){
		$topicIdList = DBTopicArticleHelper::getConn()->field('article_id, topic_id')->where('article_id IN(:article_id)', array('article_id'=>$articleIds))->fetchAssocAll();
		if(empty($topicIdList)){
			return array();
		}
		$topicIds = array();
		foreach($topicIdList as $v){
			$topicIds[]	= $v->topic_id;
		}
		$list		= DBTopicHelper::getConn()->field($fields)->where('is_delete=0 AND topic_id IN(:topic_id)', array('topic_id'=>$topicIds))->fetchAssocAll();
		$rs			= array();
		foreach($topicIdList as $kArticleId=>$v){
			$rs[$kArticleId] = $list[$v->topic_id];
			$rs[$kArticleId]->topic_id = $v->topic_id;
		}
		return $rs;
	}

	public function changeCollect($topicId,$userId,$grade=0,$change){
		if(!$change){
			$iscollect =  DBTopicUserHelper::getConn()->delete('topic_id=:topic_id AND user_id=:user_id',array('topic_id'=>$topicId,'user_id'=>$userId));
			DBTopicHelper::getConn()->decrement('user_num',array('topic_id'=>$topicId));
		}else{
			$iscollect = DBTopicUserHelper::getConn()->insert(array('topic_id'=>$topicId,'user_id'=>$userId,'user_grade'=>$grade));
			$res = DBTopicHelper::getConn()->increment('user_num',array('topic_id'=>$topicId));
		}
		return $iscollect;
	}

	public function getCollect($topicId,$userId){
		$iscollect = DBTopicUserHelper::getConn()->field('id')->where('topic_id=:topic_id AND user_id=:user_id',array('topic_id'=>$topicId,'user_id'=>$userId))->limit(0,1)->fetchAll();
		if($iscollect){
			return 1;
		}
		return 0;
	}

	/*
	 * 检查是否关注(收藏)话题
	 */
	public function getCollectTopic($topicIds, $loginUserId){
		$topicIds = DBTopicUserHelper::getConn()->field('topic_id')->where('topic_id IN(:topic_id) AND user_id=:user_id',array('topic_id'=>$topicIds,'user_id'=>$loginUserId))->fetchCol();
		return $topicIds;
	}

	public function getMyCollect($userId){
		$mycollect = DBTopicUserHelper::getConn()->field('topic_id')->where('user_id=:user_id',array('user_id'=>$userId))->fetchCol();
		if(empty($mycollect)){
			return array();
		}
		return $mycollect;
	}
}
