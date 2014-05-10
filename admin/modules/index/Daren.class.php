<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\User\Userinfo;

class Daren extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = TRUE;
	private	$pageNum;
	private	$length = 20;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->hotDarenList = $this->getHotDarenList();
		$this->page();
	}

	private function _init() {
		  $this->pageNum	= $this->getRequest('pageNum',0);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}


	private function getHotDarenList(){
	   $this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
	   $offset         = ($this->pageNum-1) * $this->length; 
		$hotDarenList = Userinfo::getInstance()->getHotDarenList($offset,$this->length);
		foreach($hotDarenList as $hl){
			$hl->userInfo = Userinfo::getInstance()->getUserInfo('','user_id IN(:user_id)',array('user_id'=>$hl->user_id),0,100);
		}
		return $hotDarenList;
	}


	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Userinfo::getInstance()->getHotDarenNum();
		$this->view->page = $page;
	}
}
