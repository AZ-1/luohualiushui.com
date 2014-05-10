<?php

namespace Gate\Scripts\Data;

use Gate\Package\Article\Like;
use Gate\Package\Article\Comment;
use Gate\Package\Activity\Vote;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Package\Helper\DBLikeHelper;
use Gate\Package\Helper\DBCommentHelper;
use Gate\Package\Helper\DBArticleHelper;
use Gate\Package\Helper\DBTopicArticleVoteHelper;
use Gate\Package\Helper\DBTopicArticleVoteUserHelper;
use Gate\Package\User\Register;
use Gate\Package\User\Fans;

/*
 * Author：xujiantao

 * LastModified：2014-1-3

 * 30分钟定时自动执行喜欢、评论、投票、加粉丝操作
 *
 */
class Rebot extends \Gate\Libs\Scripts
{

	public function run()
	{
		date_default_timezone_set('PRC');
		$seconds = mt_rand(0, 180);
		sleep($seconds);
		$this->getRunLinkFunc();
	}

    
	//增加假用户
	public function runAddfakeUserData()
	{
		$fakeUserList = DBUserProfileHelper::getConn()->from('dolphin_user_profile')->field('nickname,avatar_c')->where('user_id>5 AND avatar_c LIKE "avatar_c%"', array())->limit(300)->fetchArrAll();
		foreach($fakeUserList as $key=>$val)
		{
			if(strstr($val['avatar_c'], 'avatar_'))
			{
				$fakeUserList[$key]['avatar_c'] = 'http://avatar.qiniudn.com/'.$val['avatar_c'];
			}
			//Register::getInstance()->addUser();
		}		
	}

	public function writeLog($content='', $timeShow=true)
	{
		$file = fopen('/tmp/log/mei.hitao.com/robot.log', 'a');
		$time = $timeShow == true ? date('Y-m-d H:i:s', time()).':' : '';
		if($file)
		{
			fwrite($file, $time.$content."\r\n");
			fclose($file);
		}
	}

	public function getRunLinkFunc()
	{
		$data['userIdList'] = DBUserProfileHelper::getConn()->field('user_id')->where('password="ghost"', array())->order('user_id desc')->limit(300)->fetchCol();

		//喜欢的数据
		$likeUserIds = DBLikeHelper::getConn()->field('user_id')->where('user_id in(:id)', array('id'=>$data['userIdList']))->fetchCol();
		$data['userIdList'] = array_diff($data['userIdList'], $likeUserIds);
		$data['userIdList'] = array_values($data['userIdList']);
		shuffle($data['userIdList']);
		$data['articleIdList'] = DBTopicArticleVoteHelper::getConn()->field('article_id')->where('topic_id=34 OR topic_id=35', array())->order('article_id desc')->fetchCol();
		shuffle($data['articleIdList']);

		//评论的数据
		$data['commentCon'] = DBCommentHelper::getConn()->from('beauty_tmp_comment')->where('status=0', array())->order('id DESC')->limit(1)->fetchArrAll();
		$data['articleUserId'] = DBArticleHelper::getConn()->field('user_id')->where('article_id IN(:article_id)', array('article_id'=>$data['articleIdList']))->order('article_id desc')->fetchCol();

		//投票的数据
		$data['topicId'] = DBTopicArticleVoteHelper::getConn()->field('topic_id')->where('topic_id=34 OR topic_id=35', array())->order('article_id desc')->fetchCol();

		//粉丝的数据
		$data['fansInfo']['userId'] = DBArticleHelper::getConn()->field('user_id')->where('article_id=:aid', array('aid'=>$data['articleIdList'][0]))->fetchCol();
		$data['fansInfo']['fansUserId'] = $data['userIdList'][0];

		$currentHour = date('H', time());
		$timeInfo = $this->timeFunc($currentHour);
		if(!empty($timeInfo))
		{
			$condition = explode('-', $timeInfo['timeSection']);
			if($currentHour >= $condition[0] && $currentHour < $condition[1])
			{
				$data['currentHour'] = $currentHour;
				$this->runDbUpdate($data, $timeInfo);
			}
		}

	}

	public function runDbUpdate($data)
	{
		extract($data);
		$this->writeLog("\r\n---------执行30分钟间隔-------------\r\n", false);

		//喜欢操作
		$likeTimeStatus = false;
		if($currentHour < 11 && $currentHour > 22)
		{
			$likeTimeStatus = true;
		}

		if(!empty($articleIdList) && !empty($userIdList) && $likeTimeStatus)
		{
			$likeId = Like::getInstance()->addLike(intval($articleIdList[0]), intval($userIdList[0]));                                           
			$logText = sprintf('----Robot--添加喜欢--- LikeId:%s -- ArticleId:%s -- UserId:%s', $likeId, $articleIdList[0], $userIdList[0]);
			$this->writeLog($logText);
		}

		//执行评论操作
		if(!empty($articleIdList) && !empty($userIdList) && !empty($commentCon))
		{	
			$saveData['article_id'] = $articleIdList[0];
			$saveData['user_id'] = $userIdList[0];
			$saveData['content'] = $commentCon[0]['comment'];
			$saveData['pid'] = 0;
			$saveData['reply_user_id'] = 0;
			$saveData['article_user_id'] = $articleUserId[0];
			$commentId = Comment::getInstance()->addComment($saveData);
			$logText = sprintf('----Robot--添加评论--- CommentId:%s -- ArticleId:%s -- UserId:%s -- ArticleUserId:%s', $commentId, $articleIdList[0], $userIdList[0], $articleUserId[0]);
			$this->writeLog($logText, true);
		
			if($commentId)
			{
				DBCommentHelper::getConn()->from('beauty_tmp_comment')->update(array('status'=>1), 'id=:id', array('id'=>$commentCon[0]['id']));
			}	
		}


		//添加投票
		if(!empty($userIdList) && !empty($articleIdList) && !empty($topicId))
		{
			Vote::getInstance()->addVote($userIdList[0], $articleIdList[0], $topicId[0]);
			$voteStatus = DBTopicArticleVoteUserHelper::getConn()->field('id')->where('user_id=:uid', array('uid'=>$userIdList[0]))->fetch();	
			$logText = sprintf('----Robot--添加投票--- VoteId:%s -- ArticleId:%s -- UserId:%s -- TopicId:%s', $voteStatus->id, $articleIdList[0], $userIdList[0] ,$topicId[0]);
			$this->writeLog($logText, true);
		}

		//添加粉丝
		if(!empty($fansInfo['fansUserId']) && !empty($fansInfo['userId']))
		{
			$addFansId = Fans::getInstance()->addFans($fansInfo['fansUserId'], $fansInfo['userId'][0]);
			$logText = sprintf('----Robot--添加粉丝--- AddFansId:%s --  FansUserId:%s -- UserId:%s', $addFansId, $fansInfo['fansUserId'], $fansInfo['userId'][0]);
			$this->writeLog($logText, true);
		}
	}

	/*
	 *
	 * 配置参数
	 *
	 */
	function timeFunc($time)
	{
		$result = array();
		switch($time)
		{
			case ($time >= 8 && $time <= 10):
				$userNum = array('likeNum'=>3, 'commentNum'=>2, 'voteNum'=>3);
			    $result = $this->getResult('8-10', $userNum);
		        break;
			
			case ($time >= 11 && $time <= 14):
				$userNum = array('likeNum'=>8, 'commentNum'=>2, 'voteNum'=>8);
				$result = $this->getResult('11-14', $userNum);
				break;
			
			case ($time >= 15 && $time <= 18):
				$userNum = array('likeNum'=>7, 'commentNum'=>3, 'voteNum'=>9);
				$result = $this->getResult('15-18', $userNum);
				break;

			case ($time >= 19 && $time <= 22):
				$userNum = array('likeNum'=>9, 'commentNum'=>3, 'voteNum'=>12);
			    $result = $this->getResult('19-22', $userNum);
				break;

			case ($time >= 23 && $time <= 24):
				$userNum = array('likeNum'=>3, 'commentNum'=>0, 'voteNum'=>3);
			     $result = $this->getResult('23-24', $userNum);
				 break;
		}

		return $result;
	}

	public function getResult($section, $userNumber)
	{
		return array(
			'timeSection'=>$section, 
			'userNumber'=>array(
				'likeNum'=>$userNumber['likeNum'], 
				'commentNum'=>$userNumber['commentNum'],
				'voteNum'=>$userNumber['voteNum']
			)
		);
	}

}
