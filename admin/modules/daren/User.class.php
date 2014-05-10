<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
#use Gate\Package\
use Gate\Package\User\Userinfo;
class User extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
	//protected $view_switch = FALSE;
	private $keyword;
	private	$pageNum;
	private	$length = 20;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->userList = $this->getUserInfoList();
		if($this->keyword){
			$this->view->userList = $this->getSearchDaren();
		}
		$this->page();
	}

	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Userinfo::getInstance()->getDarenTotalNum();
		$this->view->page = $page;
	}
	private function _init() {
		$this->keyword = $this->getRequest("keyword",0);
		$this->pageNum = $this->getRequest('pageNum', 0);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getUserInfoList(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length;
		return Userinfo::getInstance()->getDaren($offset,$this->length);
	}
	private function getSearchDaren(){
		return Userinfo::getInstance()->getSearchDaren($this->keyword);
	}
}
