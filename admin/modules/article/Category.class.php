<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Category as CategoryP;

class Category extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private	$pageNum;
	private	$length = 20;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->categoryList = $this->getCategory();
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
	private function getCategory(){
		$list = CategoryP::getInstance()->getCategoryList();
		$data = array();
		foreach($list as $v){
			$data[$v->id] = $v;
			if(isset($v->child) && !empty($v->child)){
				foreach($v->child as $vc){
					$vc->is_child = 1;
					$data[$vc->id] = $vc;
				}
			}
			unset($data[$v->id]->child);
		}
		return $data;
	}


	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		//$page->totalNum = Category::getInstance()->getTagTotalNum();
		$this->view->page = $page;
	}
}
