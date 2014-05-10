<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Comment;

class Del_article_comment extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private $is_del;
	private $comment_id;
	private $user_id;
	private $article_id;
	private $comment_ids;
	private $article_ids;
	private $user_ids;
	public function run() {
        if (!$this->_init()){ return FALSE;}
			if($this->comment_id){
				$isDel = $this->delCommentById($this->comment_id,$this->user_id,$this->article_id);
				if($isDel){
					$this->forward("/article/edit_article_comment?article_id={$this->article_id}");
				}
			}
			if($this->comment_ids != ''){
				$isDel = $this->delCommentByIds($this->comment_ids,$this->user_ids,$this->article_ids);
				if($isDel){
					$this->forward("/article/comment");
				}	
			}
		
	}

	private function _init() {
		$this->is_del = $this->getRequest('is_delete',1);
		$this->comment_id = $this->getRequest('comment_id',1);
		$this->user_id = $this->getRequest('user_id',1);
		$this->article_id = $this->getRequest('article_id',1);
		$this->comment_ids = $this->getRequest('comment_ids',0);
		$this->article_ids = $this->getRequest('article_ids',0);
		$this->user_ids = $this->getRequest('user_ids',0);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		if(!$this->is_del){return FALSE;}
		return TRUE;
	}

	private function delCommentById($commentid,$user_id,$article_id){
		$res = Comment::getInstance()->delComment($commentid,$user_id,$article_id);
		return $res;
	}

	private function delCommentByIds($comment_ids,$user_ids,$article_ids){
		foreach($comment_ids as $k=>$v){
			$res = $this->delCommentById($v,$user_ids[$k],$article_ids[$k]);
			if(!$res){return FALSE;}
		}
		return true;
	}
	
	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '删除成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
}
