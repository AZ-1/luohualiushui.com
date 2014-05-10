<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Comment;
use Gate\Package\User\Userinfo;	

class Edit_article_comment extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
   // protected $view_switch = FALSE;
	private $articleid;
	private $commentList;
	public function run() {
        if (!$this->_init()) return FALSE;
		$this->commentList = $this->getCommentList($this->articleid);
		$this->view->commentList = $this->getUserinfo();
		
	//	$this->view->userinfo = $this->getUserinfo();
	}

	private function _init() {
		$this->articleid = $this->getRequest('article_id',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function getCommentList($articleid){
		return Comment::getInstance()->getCommentList($articleid,0,20);
	}

	private function getUserinfo(){
		foreach($this->commentList as $v){
			$v->user = Userinfo::getInstance()->getUserById($v->user_id);
		}
		return  $this->commentList;
	}

}
