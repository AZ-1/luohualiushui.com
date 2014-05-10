<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\User;
#use Gate\Package\
use Gate\Package\User\Userinfo;
class User extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
	protected $view_switch = true;
	private	$pageNum;
	private	$length = 20;
	private $keyword;
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
		$page->totalNum = Userinfo::getInstance()->getUserInfoTotalNum();
		$this->view->page = $page;
	}

	private function getSearchDaren(){
		return Userinfo::getInstance()->getSearchDaren($this->keyword);
	}
	private function _init() {
		  $this->pageNum = $this->getRequest('pageNum',0);
		$this->keyword = $this->getRequest("keyword",1);
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
		return Userinfo::getInstance()->getUserInfoList($offset,$this->length);
	}
}
