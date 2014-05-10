<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Cosmetic;


class Comment extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
//    protected $view_switch = FALSE;
	private	$pageNum;
	private	$length = 20;
	private $commentList;
	private $keyword;
	public function run() {
        if (!$this->_init()) return FALSE;
		if($this->keyword || $this->cosmetic_id){
			$this->view->commentList = $this->getCommentList($this->keyword,$this->cosmetic_id);
			$this->view->cosmetic_id = $this->cosmetic_id;
			$this->view->keyword = $this->keyword;
			$this->page();
		}
		
	//	$this->view->userinfo = $this->getUserinfo();
	}

	private function _init() {
		$this->keyword = $this->getRequest('keyword',0);
		$this->cosmetic_id = $this->getRequest('cosmetic_id',1);
		$this->pageNum = $this->getRequest('pageNum',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function getCommentList($keyword=0 , $cosmetic_id=''){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length;
		if($cosmetic_id){
			 $commentList = Cosmetic::getInstance()->getCommentListById($cosmetic_id,$offset,$this->length);
			 return $commentList;
		}	
		$list = Cosmetic::getInstance()->getCommentListByKeyword($keyword,$offset,$this->length);
		return $list;
	}



	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Cosmetic::getInstance()->getCommentCount($this->cosmetic_id,$this->keyword);
		$this->view->page = $page;
	}
}
