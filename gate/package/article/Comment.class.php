<?php
/*
 * 文章评论
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBCommentHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Category;
use Gate\Package\Article\Article;
use Gate\Package\User\Message;

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
		return (int)$row->comment_num;
        //return DBCommentHelper::getConn()->findCount();
	}

	/*
	 * 专题评论总数
	 */
	public function getSeminarCommentCount($seminar_id){
		$row = DBCommentHelper::getConn()->where('seminar_id=:seminar_id AND is_delete=0',array('seminar_id'=>$seminar_id))->fetchCount();
		return (int)$row;
	}
	/*
	 * 文章的评论列表
	 */
	public function getCommentList($articleId=0, $offset,$length ,$seminar_id=0){
		if($seminar_id == 0){
			$commentList    = DBCommentHelper::getConn()->where('article_id=:article_id AND is_delete=0 AND seminar_id=0', array('article_id'=>$articleId))->order('comment_id DESC')->limit($offset, $length)->fetchAll();
		}else{
			$commentList	= DBCommentHelper::getConn()->where('seminar_id=:seminar_id AND is_delete=0', array('seminar_id'=>$seminar_id))->order('comment_id DESC')->limit($offset, $length)->fetchAll();
		}
		if(empty($commentList)){
			return array();
		}
		$userIds = array();
		foreach($commentList as $v){
			$userIds[$v->user_id] = $v->user_id;
		}
		// user data
		$userinfo = Userinfo::getInstance()->getUserByIds($userIds,'',0, count($userIds));	

		// merge
		foreach($commentList as $v){
			$v->user = $userinfo[$v->user_id];
		}
		return $this->getRegCommentList($commentList);
	}

	public function getRegCommentList($list){
		if(empty($list)){
			return array();
		}
		$reg = '/回复@(.*)[:|\s]/U';
		foreach($list as $v){
			$res = preg_match($reg,$v->content,$v->preg_name);
			if($res){
				$v->preg_content = str_replace($v->preg_name[0],'',$v->content);
			}
		}
		return $list;
	}

	/*
	 *
	 */
	public function getUserCommentCount($userId){
		$count = DBCommentHelper::getConn()->where('reply_user_id=:user_id OR article_user_id=:user_id AND is_delete=0',array('user_id'=>$userId))->fetchCount();
		return $count;
	}

	public function getUserCommentListCount($userId){
		return DBCommentHelper::getConn()->where('is_delete=0 AND seminar_id=0 AND reply_user_id=:user_id OR article_user_id=:user_id',array('user_id'=>$userId))->fetchCount();
	}

	public function getUserCommentList($userId,$offset,$length){
		$commentList = DBCommentHelper::getConn()->where('is_delete=0 AND seminar_id=0 AND reply_user_id=:user_id OR article_user_id=:user_id',array('user_id'=>$userId))->order('create_time DESC')->limit($offset,$length)->fetchAll();
		$uids=array();
		$articleids=array();
		$articleinfo_array=array();
		$categoryIds=array();
		foreach($commentList as $v){
			$uids[$v->user_id] = $v->user_id;
			$articleids[$v->article_id] = $v->article_id;
			$uids[$v->reply_user_id] = $v->reply_user_id;
		}
		$userinfo = Userinfo::getInstance()->getUserByIds($uids,'realname,avatar_c,grade',0,count($uids));
		$articleinfo = Article::getInstance()->getArticleByIds($articleids,'article_id,category_id,user_id,title',0,count($articleids));
		foreach($commentList as $v){
			$v->content = $this->filterContent($v->content);
			$v->userinfo = isset($userinfo[$v->user_id]) ? $userinfo[$v->user_id] : '';
			$v->replay_userinfo = isset($userinfo[$v->reply_user_id]) ? $userinfo[$v->reply_user_id] : '';
			foreach($articleinfo as $vv){
				if($v->article_id == $vv->article_id){
					$v->article_info = $vv;
				}
			}
		}
		
		return $commentList;
	}

	public function getUserCommentOtherCount($user_id){
		$count = DBCommentHelper::getConn()->where('user_id=:user_id AND is_delete=0',array('user_id'=>$user_id))->fetchCount();
		return $count;
	}

	/*
	 *我评论他人,我回复他人
	 */
	public function getUserCommentOtherList($user_id,$offset,$length){
		$commentList = DBCommentHelper::getConn()->where('user_id=:user_id AND is_delete=0 AND seminar_id=0',array('user_id'=>$user_id))->limit($offset,$length)->fetchAll();
		$uids=array();
		$articleids=array();
		$articleinfo_array=array();
		$categoryIds=array();
		foreach($commentList as $v){
			$uids[$v->user_id] = $v->user_id;
			$articleids[$v->article_id] = $v->article_id;
			$uids[$v->reply_user_id] = $v->reply_user_id;
		}
		$userinfo = Userinfo::getInstance()->getUserByIds($uids,'realname,avatar_c,grade',0,count($uids));
		$articleinfo = Article::getInstance()->getArticleByIds($articleids,'article_id,category_id,user_id,title',0,count($articleids));
		foreach($commentList as $v){
			$v->content = $this->filterContent($v->content);
			$v->userinfo = isset($userinfo[$v->user_id]) ? $userinfo[$v->user_id] : '';
			$v->replay_userinfo = isset($userinfo[$v->reply_user_id]) ? $userinfo[$v->reply_user_id] : '';
			foreach($articleinfo as $vv){
				if($v->article_id == $vv->article_id){
					$v->article_info = $vv;
				}
			}
		}
		return $commentList;
	}

	private function filterContent($content){
		$reg = '/回复@.*:(.*)/';
		$fact = preg_match($reg,$content,$res);
		if($fact){
			return $res[1];
		}
		return $content;
	}

	/*
	 * 添加评论
	 */
	public function addComment($data, $loginUserId=0){
		$newId = DBCommentHelper::getConn()->insert( $data);
		if( $newId ){
			Userinfo::getInstance()->editUserInfo($data['user_id'],array('last_comment_time'=>time()));
			DBArticleStatisticHelper::getConn()->increment('comment_num', array('article_id'=>$data['article_id']));
			// 消息提醒
			if($data['article_user_id']!=$data['user_id']){
				$messageType = Message::getInstance()->getType();
				Message::getInstance()->addMessage($data['article_user_id'], $messageType->comment);
			}
		}
		return $newId;
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
	public function delComment($data){
		$isD =DBCommentHelper::getConn()->update(array('is_delete'=>1), 'comment_id=:comment_id AND user_id=:user_id', array('comment_id'=>$data['comment_id'], 'user_id'=>$data['user_id']));
		if($isD){
			DBArticleStatisticHelper::getConn()->decrement('comment_num', array('article_id'=>$data['article_id']));
		}
		return $isD;
	}

	public function delComments(){
	
	
	}
}
