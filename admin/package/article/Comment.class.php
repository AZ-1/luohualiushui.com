<?php
/*
 * 文章评论
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBCommentHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;

class Comment{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }


	/*
	 * 
	 */
	public function getCommentList($articleId, $offset,$length){
        $commentList    = DBCommentHelper::getConn()->where('article_id=:article_id AND is_delete=0', array('article_id'=>$articleId))->order('comment_id DESC')->limit($offset, $length)->fetchAll();
		return $commentList;
	}

	/*
	 *获取所有评论信息
	 */
	public function getAllCommentList($offset,$length){
		$commentList = DBCommentHelper::getConn()->where('is_delete=0', array())->limit($offset,$length)->order('comment_id DESC')->fetchAll();
		return $commentList;
	}

	/*
	 *筛选评论
	 */
	public function checkCommentList($keyword){
//DBCommentHelper::getConn()->dump();
		$commentList = DBCommentHelper::getConn()->where('is_delete=0 AND content LIKE :keyword',array('keyword'=>'%'.$keyword.'%'))->order('comment_id DESC')->fetchAll();
		return $commentList;
	}

	/*
	 * 添加评论
	 */
	public function addComment($data){
		$newId = DBCommentHelper::getConn()->insert( $data);
		if( $newId ){
			DBArticleStatisticHelper::getConn()->increment('comment_num', array('article_id'=>$newId));
		}
		return $newId;
	}


	/*
	 * 修改评论
	 */
	private function updateComment(){
		
	}


	/*
	 * 评论总数
	 */
	public function getCommentCount(){
        return DBCommentHelper::getConn()->where('is_delete=0',array())->fetchCount();
	}

	/*
	 * 删除评论
	 */
	public function delComment($comment_id, $user_id,$article_id){
		//$isD =DBCommentHelper::getConn()->delete('comment_id=:comment_id AND user_id=:user_id', array('comment_id'=>$id, 'user_id'=>$userId));
		$isD = DBCommentHelper::getConn()->update(array('is_delete'=>1),'comment_id=:comment_id AND user_id=:user_id',array('comment_id'=>$comment_id,'user_id'=>$user_id));
		if($isD){
			DBArticleStatisticHelper::getConn()->decrement('comment_num', array('article_id'=>$article_id));
		}
		return $isD;
	}
}
