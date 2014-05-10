<?php
namespace Gate\Scripts\Data;

use Gate\Package\Article\Like;
use Gate\Package\Article\Comment;
use Gate\Package\Activity\Vote;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Package\Helper\DBLikeHelper;
use Gate\Package\Helper\DBTopicArticleHelper;
use Gate\Package\Helper\DBTopicArticleVoteHelper;
use Gate\Package\Helper\DBCommentHelper;
use Gate\Package\Helper\DBArticleHelper;
use Gate\Package\User\Register;
use Gate\Package\User\Fans;

/*
 * Author：xujiantao
 *
 * LastModified：2014-1-3
 *
 * 15分钟定时执行机器人添加喜欢，早上11点到晚上10点之间
 *
 */

class RebotFifteen extends \Gate\Libs\Scripts
{

	public function run()
	{
		date_default_timezone_set('PRC');
		$seconds = mt_rand(0, 180);
		sleep($seconds);
		$this->getRunLinkFunc();
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

		$currentHour = date('H', time());
		$timeInfo = $this->timeFunc($currentHour);

		if(!empty($timeInfo))
		{
			$condition = explode('-', $timeInfo['timeSection']);
			if($currentHour >= $condition[0] && $currentHour < $condition[1])
			{
				$this->runDbUpdate($data, $timeInfo);
			}
		}

	}


	public function runDbUpdate($data)
	{
		extract($data);

		$this->writeLog("\r\n---------执行15分钟间隔-------------\r\n", false);
		//喜欢操作
		if(!empty($articleIdList) && !empty($userIdList))
		{
			$likeId = Like::getInstance()->addLike(intval($articleIdList[0]), intval($userIdList[0]));
			$logText = sprintf('----Robot--添加喜欢--- LikeId:%s -- ArticleId:%s -- UserId:%s', $likeId, $articleIdList[0], $userIdList[0]);
			$this->writeLog($logText);
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
