<?php
/*
 * 文章评论
 * @author
 */
namespace Gate\Package\Activity;
use Gate\Package\Helper\DBCommentHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\User\Userinfo;

class Comment{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }


	/*
	 * 文章的评论总数
	 */
	public function getCommentCount($articleId){
		$row = DBArticleStatisticHelper::getConn()->field('comment_num')->where('article_id=:article_id', array('article_id'=>$articleId))->fetch();
		return $row->comment_num;
        //return DBCommentHelper::getConn()->findCount();
	}

	/*
	 * 文章的评论列表
	 */
	public function getCommentList($articleId, $offset,$length){
        $commentList    = DBCommentHelper::getConn()->where('article_id=:article_id AND is_delete=0', array('article_id'=>$articleId))->order('comment_id DESC')->limit($offset, $length)->fetchAll();
		// user ids
		$ids = array();
		foreach($commentList as $v){
			$ids[] = $v->user_id;
		}	
		// user data
		$userinfo = Userinfo::getInstance()->getUserByIds($ids,'',0,count($ids));	
		// merge
		foreach($commentList as $v){
			$v->user = $userinfo[$v->user_id];
		}
		return $commentList;
	}


	/*
	 * 添加评论
	 */
	public function addComment($data){
		$lastTime = Userinfo::getInstance()->getUserById($data['user_id'],'last_comment_time');		
		$lTime = $lastTime->last_comment_time;
		$now = time();
		if($now-$lTime > 5){
			$newId = DBCommentHelper::getConn()->insert( $data);
			Userinfo::getInstance()->editUserInfo($data['user_id'],array('last_comment_time'=>$now));
			if( $newId ){
				$ok = DBArticleStatisticHelper::getConn()->increment('comment_num', array('article_id'=>$data['article_id']));
			}
			return $newId;
		}else{
			return FALSE;
		}
	}


	/*
	 * 修改评论
	 */
	private function updateComment(){
		
	}


	/*
	 * 删除评论
	 * 伪删除,更改is_delete
	 */
	public function delComment($id, $userId){
		$isD =DBCommentHelper::getConn()->update(array('is_delete'=>1), 'comment_id=:comment_id AND user_id=:user_id', array('comment_id'=>$id, 'user_id'=>$userId));
		//$isD =DBCommentHelper::getConn()->delete('comment_id=:comment_id AND user_id=:user_id', array('comment_id'=>$id, 'user_id'=>$userId));
		if($isD){
			DBArticleStatisticHelper::getConn()->decrement('comment_num', array('article_id'=>$id));
		}
		return $isD;
	}

}
