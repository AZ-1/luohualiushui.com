<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Benefits AS  Benefit;

class Benefits extends \Gate\Libs\Controller {
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
		$this->getAllBenefits();
	}

	private function getAllBenefits()
	{
		$allClassify = array();
		$allFirstClassify = Benefit::getInstance()->getSubBenefitsName(0);
		$i=0;
		foreach($allFirstClassify as $item)
		{
			$id = $item->id;
			$secClassify = Benefit::getInstance()->getSubBenefitsName($id);
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
		$page->totalNum = Benefit::getInstance()->getBenefitsCount($this->id);
		$this->view->page = $page;
	}
}
