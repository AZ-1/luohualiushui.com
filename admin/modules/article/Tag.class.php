<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;
use Gate\Package\Ad\Ad;

class Tag extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private	$pageNum;
	private	$length = 20;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->tagList=$this->getTagList();
		$this->page();
	}

	private function _init() {

		$this->pageNum = $this->getRequest('pageNum', 1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getTagList(){
	   $this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
	   $offset         = ($this->pageNum-1) * $this->length; 
		return Article::getInstance()->getTagList($offset, $this->length);
	}
	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Article::getInstance()->getTagTotalNum();
		$this->view->page = $page;
	}
}
