<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Feedback;
use Gate\Package\User\Userinfo;
class Index extends \Gate\Libs\Controller{
    //protected $view_switch = true;
	private	$pageNum;
	private	$length = 20;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->feedbackList = $this->getFeedbackList();
		$this->page();
	}

	private function _init() {
		$this->pageNum		= $this->getRequest('pageNum',0);

		return $this->_check();
	}

	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Userinfo::getInstance()->getFeedbackNum();
		$this->view->page = $page;
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function getFeedbackList(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length;
		return Userinfo::getInstance()->getFeedbackList($offset,$this->length);
	}

}
