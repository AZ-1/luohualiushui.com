<?php
/**
 *
 * */
namespace Gate\Scripts\Data;
use Gate\Package\Helper\DBTopicArticleHelper;
use Gate\Package\Helper\DBTopicHelper;
use Gate\Package\Helper\DBTopicUserHelper;
use Gate\Package\Helper\DBArticleHelper;
use Gate\Package\Helper\DBUserGradeHelper;
use Gate\Package\Helper\DBLikeHelper;
use Gate\Package\Helper\DBUser;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Package\Helper\DBCommentHelper;
use Gate\Package\Helper\DBUserProfileHelper;

class Data_count extends \Gate\Libs\Scripts{

	public function run(){
		$this->test();
		//$this->articleLikeUserCount();
		//$this->userGradeCount();
		$this->collectTopicCount();
		//$this->userLikeArticleCount();
		//$this->topicArticleCount();
		//$this->clear();
		//$this->commentCount();
		//$this->checkUserAndStatistic();
	}


	/*
	 * 话题的文章统计
	 */
	function topicArticleCount(){
		$topicIds = DBTopicHelper::getConn()->field('topic_id')->fetchCol();
		foreach($topicIds as $vtid){
			$articleIds = DBTopicArticleHelper::getConn()->field('article_id')->where('topic_id=:topic_id', array('topic_id'=>$vtid))->fetchCol();
			// 被删除的文章
			$delArticleIds = DBArticleHelper::getConn()->field('article_id')->where('is_delete=1 AND article_id IN(:article_id)', array('article_id'=>$articleIds))->fetchCol();
			if(!empty($delArticleIds)){
				DBTopicArticleHelper::getConn()->delete('article_id IN(:article_id)', array('article_id'=>$delArticleIds));
			}

			$num = DBTopicArticleHelper::getConn()->where('topic_id=:topic_id', array('topic_id'=>$vtid))->fetchCount();
			DBTopicHelper::getConn()->update(array('article_num'=>$num), 'topic_id=:topic_id', array('topic_id'=>$vtid));
			echo '话题的文章统计:'.$vtid . '-' . $num . "\n";
		}
	}

	/*
	 * 评论统计
	 */
	public function commentCount(){
		$sql = "SELECT   article_id, COUNT(*) AS num FROM beauty_comment WHERE is_delete=0 GROUP BY article_id";
		$list = DBCommentHelper::getConn()->fetchAssocAll($sql);
		foreach($list as $aid=>$v){
			$isUp = DBArticleStatisticHelper::getConn()->update(array('comment_num'=>$v->num), 'article_id=:article_id', array('article_id'=>$aid));
			if($isUp){
				echo '评论统计：'.$aid."\n";
			}
		}
	}


	/*
	 * 文章的喜欢用户数统计
	 */
	public function articleLikeUserCount(){
		$sql = "SELECT  article_id, COUNT(*) AS num FROM beauty_like GROUP BY article_id";
		$list = DBLikeHelper::getConn()->fetchAssocAll($sql);
		foreach($list as $vid=>$v){
			$isUp = DBArticleStatisticHelper::getConn()->update(array('like_num'=>$v->num), 'article_id=:article_id', array('article_id'=>$vid));
			if($isUp){
				echo '文章的喜欢用户数统计：'.$vid."\n";
			}
		}
	}


	/*
	 * 用户喜欢文章数统计
	 */
	public function userLikeArticleCount(){
		$sql = "SELECT  user_id, COUNT(*) AS num FROM beauty_like GROUP BY user_id";
		$list = DBLikeHelper::getConn()->fetchAssocAll($sql);
		foreach($list as $vid=>$v){
			$isUp = DBUserStatisticHelper::getConn()->update(array('like_article_num'=>$v->num), 'user_id=:user_id', array('user_id'=>$vid));
			if($isUp){
				echo '用户喜欢文章数统计：'.$vid."\n";
			}
		}
	}

	/*
	 * 关注话题统计
	 */
	public function collectTopicCount(){
		// 去重
		$sql = "SELECT id
				FROM (
					SELECT id, COUNT( * ) AS num
					FROM beauty_topic_user
					GROUP BY user_id, topic_id
				)t
				WHERE num >1
			";
		$repeatIds = DBTopicHelper::getConn()->fetchCol($sql);
		if(!empty($repeatIds)){
			$isDel = DBTopicUserHelper::getConn()->delete('id IN(:id)', array('id'=>$repeatIds));
		}
		
		// 统计
		$sql = "SELECT  user_id, COUNT(*) AS num FROM beauty_topic_user GROUP BY user_id";
		$list = DBTopicHelper::getConn()->fetchAssocAll($sql);
		foreach($list as $vid=>$v){
			$isUp = DBUserStatisticHelper::getConn()->update(array('collect_topic_num'=>$v->num), 'user_id=:user_id', array('user_id'=>$vid));
			if($isUp){
				echo '关注话题统计-用户:'.$vid.'-话题数:'. $v->num."\n";
			}
		}
	}


	/*
	 * 达人统计
	 */
	public function userGradeCount(){
		$sql = "SELECT  grade, COUNT(*) AS num FROM beauty_user_profile GROUP BY grade";
		$list = DBLikeHelper::getConn()->fetchAssocAll($sql);
		unset($list[0]);
		foreach($list as $vid=>$v){
			$isUp = DBUserGradeHelper::getConn()->update(array('user_num'=>$v->num), 'id=:id', array('id'=>$vid));
			if($isUp){
				echo '达人统计：'.$vid.':'.$v->num . "\n";
			}
		}
	}


	/*
	 * 清除测试数据
	 */
	public function clear(){

		// topic_article
		$articleIds = DBTopicArticleHelper::getConn()->field('article_id')->fetchCol();
		$hasArticleIds = DBArticleHelper::getConn()->field('article_id')->where('article_id IN(:aid)', array('aid'=>$articleIds))->fetchCol();
		$clearArticleIds = array_diff($articleIds, $hasArticleIds);
		if( !empty($clearArticleIds)){
			echo "清除 topic_article 不存在文章id, \n";
			echo DBTopicArticleHelper::getConn()->delete('article_id IN(:aid)', array('aid'=>$clearArticleIds));
		}
	}


	/*
	 * 保持用户表和用户统计表数据一致
	 */
	public function checkUserAndStatistic(){
		$userIdList = DBUserProfileHelper::getConn()->field('user_id')->order('user_id DESC')->fetchCol();
		
		foreach($userIdList as $val){
			$data = array('user_id'=>$val);
			DBUserStatisticHelper::getConn()->insertIgnore($data);
		}
	}

	public function test(){
		$forwardAids = DBArticleHelper::getConn()->field('forward_article_id')->where('forward_article_id>0', array())->fetchCol();
		$list = DBArticleHelper::getConn()->field('article_id, user_id')->where('article_id IN(:aid)', array('aid'=>$forwardAids))->fetchAll();
		foreach($list as $v){
			DBArticleHelper::getConn()->update(array('by_forward_user_id'=>$v->user_id), 'forward_article_id=:aid', array('aid'=>$v->article_id));
			print_r($v);
		}
	}
}
