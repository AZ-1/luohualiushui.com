<?php
/*
 * 
 * @author haochaofei  广场图片替换
 */
namespace Gate\Modules\Index;
use Gate\Package\Ad\Ad;

class Banner_m extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
//    protected $view_switch = FALSE;
	private	$pageNum;
	private	$length = 20;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->bannerList = $this->getBannerList();

		$this->page();
	}

	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Ad::getInstance()->getBannerTotalNum(1);
		$this->view->page = $page;
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

	private function getBannerList(){
	   $this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
	   $offset         = ($this->pageNum-1) * $this->length; 

		return Ad::getInstance()->getBannerList(1, $offset, $this->length);
	}
}

