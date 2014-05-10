<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBLikeHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\Helper\DBArticleHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Package\User\Message;
use Gate\Package\User\Userinfo;

class Like{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	* 
	*/
	public function addLike($articleId, $userId){
		$likeRow	= DBLikeHelper::getConn()->field('like_id')->where('user_id=:user_id AND article_id=:article_id', array('user_id'=>$userId, 'article_id'=>$articleId))->limit(1)->fetch();
		if($likeRow){
			return $likeRow->like_id;
		}

		$articleRow = DBArticleHelper::getConn()->field('user_id')->where('article_id=:article_id', array('article_id'=>$articleId))->fetch();
		if(!$articleRow){
			return false;
		}
		//if($articleRow->user_id==$userId){
		//	return false;
		//}

		$data	= array('article_id'=>$articleId, 'user_id'=>$userId, 'article_user_id'=>$articleRow->user_id);
		$newId	= DBLikeHelper::getConn()->insert( $data );
		if($newId){
			DBArticleStatisticHelper::getConn()->increment('like_num', array('article_id'=>$articleId));
			DBUserStatisticHelper::getConn()->increment('like_article_num', array('user_id'=>$userId));
			// 消息提醒
			if($articleRow->user_id!=$userId){ //喜欢自己的文章不提醒
				$messageType = Message::getInstance()->getType();
				Message::getInstance()->addMessage($articleRow->user_id, $messageType->like);
			}
		}
		return $newId;
	}


	/*
	 * 删除喜欢
	 */
	public function delLike($id=0, $articleId=0, $userId=0){
		$where = 'user_id=:user_id';
        $sqlData['user_id']		= $userId;
		if(!$articleId){
			$row	= DBLikeHelper::getConn()->field('article_id')->where('user_id=:user_id AND like_id=:id', array('user_id'=>$userId, 'id'=>$id))->limit(1)->fetch();
			if( !$row){
				return true;
			}
			$where .= ' AND like_id=:like_id';
			$sqlData['like_id']		= $id;
			$articleId = $row->article_id;
		}else{
			$where .= ' AND article_id=:article_id';
			$sqlData['article_id']	= $articleId;
		}
		$isD = DBLikeHelper::getConn()->delete($where, $sqlData);
		
		if($isD){
			DBArticleStatisticHelper::getConn()->decrement('like_num', array('article_id'=>$articleId));
			DBUserStatisticHelper::getConn()->decrement('like_article_num', array('user_id'=>$userId));
		}
		return $isD;
	}


	/*
	 * 用户喜欢的文章
	 */
	public function getUserLikeArticle($userId, $offset, $length){
		$sqlData['user_id'] = $userId;
		$likeList	= DBLikeHelper::getConn()->where('user_id=:user_id', $sqlData)->order('like_id DESC')->limit($offset, $length)->fetchAll();
		if(empty($likeList)){
			return array();
		}
		$articleIds = array();
		foreach($likeList as $v){
			$articleIds[] = $v->article_id;
		}
		return $articleIds;
	}

	/*
	 *通过文章找到喜欢他的用户
	 */
	public function getUserByArticleId($articleId,$offset,$length){
		$result		= array();
		$userIds	= DBLikeHelper::getConn()->field('user_id')->order('create_time DESC')->where('article_id=:articleId',array('articleId'=>$articleId))->limit($offset, $length)->fetchCol();
		$userInfo	= Userinfo::getInstance()->getUserByIds($userIds,'user_id,avatar_c', 0, count($userIds));
		$i = 0;
		foreach($userIds as $ui){
			foreach($userInfo as $k=>$v){
				if($ui == $k){
					$result[$i]	= $v;
					$i++;
				}	
			}
		}
		return $result;
	}


	/*
	 *喜欢文章的的用户统计
	 */
	public function getUserByArticleIdCount($articleId){
		$row	= DBArticleStatisticHelper::getConn()->field('like_num')->where('article_id=:articleId',array('articleId'=>$articleId))->fetch();
		return (int)$row->like_num;
	}

	/*
	 *判断某个用户是否喜欢某篇文章
	 */
	public function userIsLikeArticle($userId, $articleId){
		$row	= DBLikeHelper::getConn()->field('user_id')->where('article_id=:articleId AND user_id=:user_id',array('articleId'=>$articleId, 'user_id'=>$userId))->fetch();
		$result['is_like'] = $row ? 1 :  0;
		return $result;
	}

	public function isLikeArticle($userId, $articleId){
		$row	= DBLikeHelper::getConn()->field('user_id')->where('article_id=:articleId AND user_id=:user_id',array('articleId'=>$articleId, 'user_id'=>$userId))->fetch();
		if($row){
			return true;
		}else{
			return false;	
		}
		//return $row ? true : false;
	}

	/*
	 *获取用户喜欢文章的总数
	 */
	public function getUserLikeArticleCount($userId){
		$row = DBUserStatisticHelper::getConn()->field('like_article_num')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		return (int)$row->like_article_num;
		//return DBLikeHelper::getConn()->where('user_id=:userId',array('userId'=>$userId))->fetchCount();
	}

	public function checkUserLikeArticle($user_id , $articleList){
		$articleIds = array();
		$forwardIds = array();
		$allArticleIds = array();
		foreach($articleList as $v){
			$articleIds[] = $v->article_id;
			$forwardIds[] = $v->forward_article_id;
		}
		$allArticleIds = array_merge($articleIds,$forwardIds);
		$userLikeArticleIds = DBLikeHelper::getConn()->field('article_id')->where('user_id=:user_id AND article_id IN (:article_id)',array('user_id'=>$user_id,'article_id'=>$allArticleIds))->fetchCol();
		foreach($articleList as $v){
			if(in_array($v->article_id,$userLikeArticleIds)){
				$v->is_like = 1;
			}
			if(in_array($v->forward_article_id,$userLikeArticleIds)){
				$v->forward_info->is_like = 1;
			}
		}
		return $articleList;
	}

	public function checkUserLikeArticle_2($userId , $articleIds){
		$articleList = DBLikeHelper::getConn()->field('article_id')->where('user_id=:user_id AND article_id IN (:article_id)',array('user_id'=>$userId, 'article_id'=>$articleIds))->fetchCol();
		return $articleList;
	}
}
