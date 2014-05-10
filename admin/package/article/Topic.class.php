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
use Gate\Package\User\Userinfo;

class Topic{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getTopicList($offset, $length){
		$res= DBTopicHelper::getConn()->field('topic_id,title')->where('is_delete=0', array())->order('topic_id DESC')->limit($offset ,$length)->fetchAll();
		return $res;
	}

	public function getHotTopic($offset,$length){
		$topic = DBHotTopicHelper::getConn()->field('topic_id,sort')->limit($offset,$length)->fetchAll();
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
	
	/*
	 * 更新推荐热门话题的排序
	 */
	public function editHotTopicSort($topicId,$sort){
		return DBHotTopicHelper::getConn()->update(array('sort'=>$sort),'topic_id=:topicId',array('topicId'=>$topicId));
	}

	public function getHotTopicNum(){
		return DBHotTopicHelper::getConn()->fetchCount();
	}

	public function getTopicById($id, $fields='*'){
		return DBTopicHelper::getConn()->field($fields)->where('topic_id=:id',array('id'=>$id))->fetch();
	}

	/*
	 * 新增话题
	 */
	public function addTopic($data){
		return DBTopicHelper::getConn()->insert($data);
	}


	/*
	 * 热门话题
	 */
	public function addHotTopic($topicIds){
		// 过滤已经存在的
		$hadTopicIds = DBHotTopicHelper::getConn()->field('topic_id')->where('topic_id IN(:topic_id) ', array('topic_id'=>$topicIds))->fetchCol();
		$topicIds = array_diff($topicIds, $hadTopicIds);
		if(empty($topicIds)){
			return true;
		}
		// 过滤不存在的
		$topicIds = DBTopicHelper::getConn()->field('topic_id')->where('topic_id IN(:topic_id) ', array('topic_id'=>$topicIds))->fetchCol();
		if(empty($topicIds)){
			return true;
		}

		foreach($topicIds as $vid){
			$data['topic_id'] = $vid;
			DBHotTopicHelper::getConn()->insert($data);
		}
		return true;
	}

	/*
	 * 删除热门话题
	 */
	public function delHotTopic($topicId){
		return DBHotTopicHelper::getConn()->delete('topic_id = :topic_id', array('topic_id'=>$topicId));
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
		$list		= DBTopicHelper::getConn()->field($fields)->where('topic_id IN(:topic_id)', array('topic_id'=>$topicIds))->fetchAssocAll();
		$rs			= array();
		foreach($topicIdList as $kArticleId=>$v){
			if(!isset($list[$v->topic_id]))continue;
			$rs[$kArticleId] = $list[$v->topic_id];
			$rs[$kArticleId]->topic_id = $v->topic_id;
		}
		return $rs;
	}
	
	public function getTopicArticle($id,$offset,$length){
		$article_ids = DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id=:id',array('id'=>$id))->fetchCol();
		return Article::getInstance()->getArticleByIds($article_ids,'',$offset,$length);
	}
	public function getTopicArticleNum($id){
		 
	    return 	DBTopicArticleHelper::getConn()->where('topic_id=:id',array('id'=>$id))->fetchCount();
	}

	/*
	 * 添加到话题
	 */
	public function updateTopicArticle($topicId,$ids){
		// 看看是不是已存在的
		$oldArticlIds = DBTopicArticleHelper::getconn()->field('article_id')->where('article_id IN(:aid) AND topic_id=:tid', array('aid'=>$ids, 'tid'=>$topicId))->fetchCol();
		$ids = array_diff($ids, $oldArticlIds);
		foreach($ids as $article_id){
			DBTopicArticleHelper::getconn()->insert(array('topic_id'=>$topicId,'article_id'=>$article_id));
			DBTopicHelper::getConn()->increment('article_num', array('topic_id'=>$topicId));
		}
		return true;
	}

	public function getTopicTotalNum(){
		return DBTopicHelper::getConn()->where('is_delete=0', array())->fetchCount();
	}
	public function delTopicArticle($id){
		return DBTopicArticleHelper::getConn()->delete('article_id=:id',array('id'=>$id));
	}
}
