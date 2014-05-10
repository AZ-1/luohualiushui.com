<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Funtionality as Fun;

class Funtionality extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private	$pageNum;
	private	$length = 20; 
	private $id = 0;
	private $pid = 0;
	private $name = '';
	public function run() {
		$this->_init();
		$this->page();
	}

	private function _init() {
		$this->getAllFun();
	}
	private function getAllFun()
	{
		$allClassify = array();
		$allFirstClassify = Fun::getInstance()->getSubFunName(0);
		$i=0;
		foreach($allFirstClassify as $item)
		{
			$id = $item->id;
			$secClassify = Fun::getInstance()->getSubFunName($id);
			$obj = new \stdClass;
			$obj->base = $item;
			$obj->child = $secClassify;
			$allClassify[$i++] = $obj;
		}	
		$this->view->allClassify = $allClassify;
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Fun::getInstance()->getFunCount($this->id);
		$this->view->page = $page;
	}
}
