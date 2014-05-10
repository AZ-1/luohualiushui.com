<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Ranking;

class Rankgoods extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private $classify_id;
	private	$pageNum;
	private	$length = 30;
	private $title;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->cid	= $this->classify_id;
		$this->view->title	= $this->title;
		$this->view->goods	= $this->getGoodsByClassifyId();
		$this->page();
	}

	private function _init() {
		$this->classify_id					=   $this->getRequest("cid");
		$this->pageNum						=	$this->getRequest('pageNum',0);
		$this->title						=	$this->getRequest('title' , 0);
		return $this->_check();
	}

	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}
	//得到所有分类
	private function getGoodsByClassifyId(){
		$this->pageNum	= $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length;
		$search			= array();
		$search['title'] = $this->title;
		return Ranking::getInstance()->getGoodsByClassifyId($this->classify_id , $offset , $this->length , $search);
	}

	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$search			= array();
		$search['title'] = $this->title;
		$page->totalNum = Ranking::getInstance()->getGoodsByClassifyIdNum($this->classify_id , $search);
		$this->view->page = $page;
	}

}
