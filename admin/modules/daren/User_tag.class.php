<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\Article\Article;
class User_tag extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
	//protected $view_switch = FALSE;
	private	$pageNum;
	private	$name		= '';
	private	$length		= 20;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->userTagList = $this->getHotUserTag();
		$this->page();
	}

	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Article::getInstance()->getHotUserTagNum($this->name);
		$this->view->page = $page;
	}
	private function _init() {
		$this->name		= $this->getRequest("name",1);
		$this->pageNum	= $this->getRequest('pageNum', 1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getHotUserTag(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length;
		return Article::getInstance()->getHotUserTag($this->name,$offset,$this->length);
	}
}