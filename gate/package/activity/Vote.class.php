<?php
namespace Gate\Package\Activity;
use Gate\Package\Helper\DBTopicArticleVoteHelper;
use Gate\Package\Helper\DBTopicArticleVoteUserHelper;
use Gate\Package\Helper\DBArticleHelper;
use Gate\Package\Helper\DBTopicArticleHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\Article\Article;
use Gate\Package\Article\Topic;
use Gate\Package\User\Userinfo;

class Vote{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getUserVoteNum($user_id,$activity_id=1){
		return DBTopicArticleVoteUserHelper::getConn()->field('vote_times')->where('user_id=:user_id AND activity_id=:activity_id',array('user_id'=>$user_id,'activity_id'=>$activity_id))->fetchCol();
	}

	public function getArticleVoteInfo($articleIds,$activity_id=1){
		return DBTopicArticleVoteHelper::getConn()->field('article_id,topic_id,num')->where('article_id IN (:article_id) AND activity_id=:activity_id',array('article_id'=>$articleIds,'activity_id'=>$activity_id))->fetchAll();
	}

	public function getArticleList($topicId,$user_id,$offset=0,$length=20){
		$article_ids = DBTopicArticleVoteHelper::getConn()->field('article_id')->where('topic_id=:topic_id AND activity_id=1',array('topic_id'=>$topicId))->order('num DESC')->limit($offset,$length)->fetchCol();
		$articleList = Article::getInstance()->getArticle('article_id,user_id,title,comment_num,title_pic','article_id IN (:article_ids)',array('article_ids'=>$article_ids),$offset,$length);
		$voteNum = $this->getArticleVoteInfo($article_ids);
		$user_ids = array();
		foreach($articleList as $v){
			$user_ids[] = $v->user_id;
			foreach($voteNum as $vv){
				if($v->article_id == $vv->article_id){
					$v->vote_num = $vv->num;
				}
			}
		}
		$userinfo = Userinfo::getInstance()->getUserByIds($user_ids,'',$offset,$length);
		$voteListA = $this->getUserVoteList($user_id);
		if(empty($voteListA)){
			$voteArticleList = array();
		}else{
			$voteList = $voteListA[0];
			$voteArticleList = explode(',',$voteList->article_ids);
		}
		foreach($articleList as $v){
			foreach($userinfo as $vv){
				if($v->user_id == $vv->user_id){
					$v->userInfo = $vv;
				}
			}
			$v->is_vote = 0;
			if(in_array($v->article_id,$voteArticleList)){
				$v->is_vote = 1;
			}
			$v->topic_id = $topicId;
		}
		foreach($article_ids as $k=>$v){
			foreach($articleList as $vv){
				if($v == $vv->article_id){
					$articleListDesc[$k] = $vv;
				}
			}
		}
		return $articleListDesc;

/*
		$articleList = DBArticleHelper::getConn()->field('')->where('article_id IN (:article_ids)',array('article_ids'=>$article_ids))->fetchAssocAll();
		$voteNum = $this->getArticleVoteInfo($article_ids);
		$commentNumList = DBArticleStatisticHelper::getConn()->where('article_id IN (:article_ids)',array('article_ids'=>$article_ids))->fetchAll();
		foreach($commentNumList as $v){
			$commentNum[$v->article_id] = $v->comment_num;
		}
		$uids = array();
		foreach($articleList as $v){
			$uids[] = $v->user_id;
		}
		$userinfo = Userinfo::getInstance()->getUserByIds($uids,'',0,count($uids));
		$voteListA = $this->getUserVoteList($user_id);
		if(empty($voteListA)){
			$voteArticleList = array();
		}else{
			$voteList = $voteListA[0];
			$voteArticleList = explode(',',$voteList->article_ids);
		}
		foreach($articleList as $v){
			$v->topic_id = $topicId;
			$v->comment_num = $commentNum[$v->article_id];
			$v->vote_num = 0;
			foreach($voteNum as $vv){
				if(	$v->article_id == $vv->article_id){
					$v->vote_num = $vv->num;
				}
			}
	//		$v->like_num = $likeNum[$v->article_id];
			foreach($userinfo as $vv){
				if($v->user_id == $vv->user_id){
					$v->userInfo = $vv;
				}
			}
			$v->is_vote = 0;
			if(in_array($v->article_id,$voteArticleList)){
				$v->is_vote = 1;
			}
		}
		return $articleList;*/
	}

	public function getUserVoteList($user_id){
		return DBTopicArticleVoteUserHelper::getConn()->field('*')->where('user_id=:user_id',array('user_id'=>$user_id))->fetchAll();
	}

	public function addVote($user_id,$article_id,$topic_id){
		$res = array('error'=>'');
		$userVoteInfoList = $this->getUserVoteList($user_id); 
		// 第一次投票的用户
		if(empty($userVoteInfoList)){
			$data_user = array('user_id' => $user_id , 'article_ids' => $article_id . ',' , 'activity_id' => 1 , 'vote_times' => 9);
			$insert_user = DBTopicArticleVoteUserHelper::getConn()->insert($data_user);
			$vote_times = 9;
			if(!$insert_user){
				$res['error'] = 'insert_user_fail';
				return $res;
			}

		// 多次投票的用户
		}else{ 
			$userVoteInfo = $userVoteInfoList[0];
			if($userVoteInfo->vote_times<=0){
				$res['num_left'] = 0;
				return $res;
			}
			$article_ids = $userVoteInfo->article_ids . $article_id . ',';
			$vote_times = $userVoteInfo->vote_times - 1 ;
			$update_user = DBTopicArticleVoteUserHelper::getConn()->update(array('article_ids'=>$article_ids , 'vote_times' => $vote_times) ,'user_id=:user_id' , array('user_id'=>$user_id));
		}

		// 参与投票文章
		$check_article_vote = DBTopicArticleVoteHelper::getConn()->field('num')->where('article_id=:article_id',array('article_id'=>$article_id))->fetchCol();
		if(empty($check_article_vote)){
			$data_article = array('activity_id'=>1 , 'article_id'=>$article_id , 'topic_id'=>$topic_id , 'num'=>0);
			$insert_article_vote = DBTopicArticleVoteHelper::getConn()->insert($data_article);
			if(!$insert_article_vote){
				$res['error'] = 'insert article fail';
				return $res;
			}
		}
		// 文章票数累计
		$increm = DBTopicArticleVoteHelper::getConn()->increment('num',array('article_id'=>$article_id));
		if(!$increm){
			$res['error'] = 'insert vote fail';
			return $res;
		}
		$res['num_left'] = $vote_times;
		return $res;
	}

}
