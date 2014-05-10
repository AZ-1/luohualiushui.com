<?php
/*
 * 话题
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBTopicHelper;
use Gate\Package\Helper\DBTopicUserHelper;
use Gate\Package\Helper\DBTopicArticleHelper;
use Gate\Package\Helper\DBHotTopicHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Package\User\Userinfo;
use Gate\Libs\Utilities;

class Topic{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }


	public function getHotTopic(){
		$topic = DBHotTopicHelper::getConn()->field('topic_id,sort')->ORDER('sort DESC')->fetchAll();
		$topicIds = array();
		if(empty($topic)){
			return array();
		}
		foreach($topic as $ti){
			$topicIds[] = $ti->topic_id;
		}
		$topicList =  DBTopicHelper::getConn()->field('topic_id, title, pic,description, user_num,article_num')->where('topic_id IN(:topic_id)', array('topic_id'=>$topicIds))->order('topic_id DESC')->fetchAll();
		foreach($topic as $t){
			foreach($topicList as $tl){
				if($t->topic_id == $tl->topic_id){
					$tl->sort = $t->sort;
				}
			}
		}
		$count	= count($topicList);
		for($i = 0 ; $i < $count-1 ; $i++){
			for($j = $i+1 ; $j < $count ; $j++){
				if($topicList[$i]->sort < $topicList[$j]->sort){
					$tmp = $topicList[$j];
					$topicList[$j] = $topicList[$i];
					$topicList[$i] = $tmp;
				}
			}
		}
		return $topicList;
	}

	public function getTopicById($id, $fields='*'){
		return DBTopicHelper::getConn()->field($fields)->where('topic_id=:id',array('id'=>$id))->fetch();
	}

	public function getTopicByIds($ids,$fields='*'){
		return DBTopicHelper::getConn()->field($fields)->where('topic_id IN(:id) AND is_delete=0',array('id'=>$ids))->fetchAll();	
	}

	public function getTopicList($offset, $length){
		return DBTopicHelper::getConn()->where('is_delete=0', array())->limit($offset, $length)->order('sort DESC')->fetchAll();
	}

	public function getTopicNum(){
		return DBTopicHelper::getConn()->where('is_delete=0',array())->fetchCount();
	}
	
	public function getTopicArticleNum($topicId){
		$list = DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id=:id',array('id'=>$topicId))->fetchCol();
		return count(array_unique($list));
	}

	/*
	 * 关注话题的所有用户
	 */
	public function getTopicUser($topicId, $offset, $length){
		$userIds =  DBTopicUserHelper::getConn()->field('user_id')->where('topic_id=:topic_id', array('topic_id'=>$topicId))->limit($offset, $length)->order('id DESC')->fetchCol();
		if(empty($userIds)){
			return array();
		}
		return Userinfo::getInstance()->getUserByIds($userIds, '', $offset, $length);	
	}

	/*
	 * 关注话题的达人
	 * 按时间倒叙排
	 */
	public function getTopicDaren($topicId, $offset, $length){
		$userIds = DBTopicUserHelper::getConn()->field('user_id')->where('topic_id=:topic_id AND user_grade!=0', array('topic_id'=>$topicId))->limit($offset, $length)->order('id DESC')->fetchCol();
		if(empty($userIds)){
			return array();
		}
		return Userinfo::getInstance()->getUserByIds($userIds, '', 0, $length);	
	}

	/*
	 * 用户关注的话题
	 * 随机产生
	 */
	public function getTopicByUid($userId, $offset, $length){
		$topicIds = DBTopicUserHelper::getConn()->field('topic_id')->where('user_id=:user_id', array('user_id'=>$userId))->fetchCol();
		if(empty($topicIds)){
			return array();
		}
		$topicIdList = DBTopicHelper::getConn()->where('is_delete=0 AND topic_id IN(:topic_id)', array('topic_id'=>$topicIds))->order('topic_id DESC')->fetchAll();
		if(count($topicIdList) > $length){
			return Utilities::array_random($topicIdList,$length); 
		}else{
			return $topicIdList;
		}
	}

	/*
	 * 用户关注的话题
	 * 我的话题 列表
	 */
	public function getUserTopic($userId, $offset, $length){
		$topicIds = DBTopicUserHelper::getConn()->field('topic_id')->where('user_id=:user_id', array('user_id'=>$userId))->order('topic_id DESC')->limit($offset,$length)->fetchCol();
		if(empty($topicIds)){
			return array();
		}
		$topicIdList = DBTopicHelper::getConn()->where('is_delete=0 AND topic_id IN(:topic_id)', array('topic_id'=>$topicIds))->order('topic_id DESC')->fetchAll();
		return $topicIdList;
	}

	/*
	 *用户关注话题的数目
	 */
	public function getUserTopicCount($userId){
		$row = DBUserStatisticHelper::getConn()->field('collect_topic_num')->where('user_id=:userId',array('userId'=>$userId))->fetch();
		return (int)$row->collect_topic_num;
		//return DBTopicUserHelper::getConn()->where('user_id=:userId',array('userId'=>$userId))->fetchCount();
	}

	public function getTopicIdsByUid($userId){
		$topicIds = DBTopicUserHelper::getConn()->field('topic_id')->where('user_id=:user_id', array('user_id'=>$userId))->fetchCol();
		return $topicIds;
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

	/*
	 * 关注话题 
	 * change = 1 关注 
	 * change = 0 取消关注 
	 */
	public function changeCollect($topicId,$userId,$grade=0,$change){
		if(!$change){ //取消
			$iscollect =  DBTopicUserHelper::getConn()->delete('topic_id=:topic_id AND user_id=:user_id',array('topic_id'=>$topicId,'user_id'=>$userId));
			if($iscollect){
				DBTopicHelper::getConn()->decrement('user_num',array('topic_id'=>$topicId));
				DBUserStatisticHelper::getConn()->decrement('collect_topic_num',array('user_id'=>$userId));
			}
		}else{
			$row = DBTopicUserHelper::getConn()->field('id')->where('topic_id=:topic_id AND user_id=:user_id', array('topic_id'=>$topicId, 'user_id'=>$userId))->fetch();
			if($row){
				return $row->id;
			}
			// todo
			$userRow = DBUserProfileHelper::getConn()->field('grade')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
			$grade = $userRow->grade;
			$iscollect = DBTopicUserHelper::getConn()->insert(array('topic_id'=>$topicId,'user_id'=>$userId,'user_grade'=>$grade));
			if($iscollect){
				DBTopicHelper::getConn()->increment('user_num',array('topic_id'=>$topicId));
				DBUserStatisticHelper::getConn()->increment('collect_topic_num',array('user_id'=>$userId));
			}
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

	/*
	 * 用户关注的话题 的文章
	 */
	public function getArticleIdsByTopicUid($userId, $offsetArticleId, $length){
		$topicIds = DBTopicUserHelper::getConn()->field('topic_id')->where('user_id=:user_id', array('user_id'=>$userId))->fetchCol();
		if(empty($topicIds)){
			return array();
		}
		if($offsetArticleId>0){
			$where = 'topic_id IN(:topic_id) AND article_id <= :article_id';
			$param = array('topic_id'=>$topicIds, 'article_id'=>$offsetArticleId);
		}else{
			$where = 'topic_id IN(:topic_id)';
			$param = array('topic_id'=>$topicIds);
		}

		$articleIds = DBTopicArticleHelper::getConn()->field('article_id')->where($where, $param)->order('article_id DESC')->limit(0, $length)->fetchCol();
		return $articleIds;
	}

}
