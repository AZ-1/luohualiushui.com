<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Comment as CommentP;
use Gate\Package\User\Userinfo;	
use Gate\Package\Article\Article;

class Comment extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
//    protected $view_switch = FALSE;
	private	$pageNum;
	private	$length = 20;
	private $commentList;
	private $keyword;
	public function run() {
        if (!$this->_init()) return FALSE;
		if($this->keyword){
			$this->commentList = $this->checkCommentList();
		}
		$this->view->commentList = $this->getCommentList();
		$this->page();
		
	//	$this->view->userinfo = $this->getUserinfo();
	}

	private function _init() {
		$this->keyword = $this->getRequest('keyword',0);
		$this->pageNum = $this->getRequest('pageNum',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function getCommentList(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length;
		$user_ids = array();
		$article_ids = array();
		$commentList = CommentP::getInstance()->getAllCommentList($offset,$this->length);
		foreach($commentList as $v){
			$user_ids[] = $v->user_id;
			$article_ids[] = $v->article_id;
		}
		$userInfoList = Userinfo::getInstance()->getUserByIds($user_ids , '' , 0, $this->length);
		$articleInfoList = Article::getInstance()->getArticleByIds($article_ids, 'title , article_id', 0, $this->length);
		foreach($commentList as $v){
			$v->user = $userInfoList[$v->user_id];
			foreach($articleInfoList as $av){
				if($v->article_id == $av->article_id){
					$v->title = $av->title;
					break;
				}
			}
		}
		return  $commentList;

	}

	private function checkCommentList(){
		return CommentP::getInstance()->checkCommentList($this->keyword);
	}


	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = CommentP::getInstance()->getCommentCount();
		$this->view->page = $page;
	}
}
