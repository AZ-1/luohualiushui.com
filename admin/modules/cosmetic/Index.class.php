<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Brand;

class Index extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private	$pageNum;
	private	$length = 20; 
	private $brand_name = '';
	private $brand_id =1;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->brand_id = $this->getRequest("brand_id");
		$this->view->brand_name = $this->getRequest("brand_name");
		$this->view->pageNum = $this->pageNum;
		$this->page();
		$this->view->brandList = $this->getBrandList();
	}

	private function _init() {
		$this->pageNum = $this->getRequest('pageNum', 1);
		$this->brand_name = $this->getRequest("brand_name");
		$this->brand_id = $this->getRequest("brand_id");
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getBrandList(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset = ($this->pageNum-1) * $this->length;
		return Brand::getInstance()->getBrandList($offset, $this->length,$this->brand_name,$this->brand_id );
	}
	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum==0?1:$this->pageNum;
		$page->totalNum = Brand::getInstance()->getBrandCount($this->brand_name,$this->brand_id);
		$this->view->page = $page;
	}
}
