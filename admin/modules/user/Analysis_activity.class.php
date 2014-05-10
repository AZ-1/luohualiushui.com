<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\User;
use Gate\Package\User\Userinfo;

class Analysis_activity extends \Gate\Libs\Controller {
	// TRUE: 输出view 页面; FALSE: 输出json格式数据
   protected $view_switch = true;
   private $pageNum;
   private $length = 20;
   private $only_daren = false;
	public function run() {
		if (!$this->_init()) {
			$this->view->user_Activity['status']='0';
			return FALSE;
		}
		$this->getRequiredList();
		$this->page();
	}
	private function _init()
	{
		if(!isset($this->request->REQUEST['only_daren']) OR $this->request->REQUEST['only_daren']!='yes')$this->only_daren = false;
		else $this->only_daren = true;
		$this->pageNum = $this->getRequest('pageNum',0);
		return $this->_check();
	}
	private function _check(){
	      return TRUE;
	}
	private function page(){
	     $page = new \stdClass;
	     $page->length   = $this->length;
	     $page->pageNum  = $this->pageNum;
		 $this->view->user_Activity['status']='1';
		 $page->totalNum = Userinfo::getInstance()->getUserStatisticTotalNum($this->only_daren);
	     $this->view->page = $page;
	}
	private function getRequest($param, $isInt=null){
		 return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));                                                      
	}
	private function getRequiredList()
	{
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset     = ($this->pageNum-1) * $this->length; 
		$this->view->only_daren=$this->only_daren;
		$this->view->user_Activity['data'] = Userinfo::getInstance()->getUserStatisticList($offset,$this->length,$this->only_daren);
	//	var_dump($this->view->user_Activity);
	}
}
