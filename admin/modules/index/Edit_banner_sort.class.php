<?php
/*
 * 热门话题
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Ad\Ad;

class Edit_banner_sort extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private $id;
	private $sort;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->sort = $this->editBannerSort();
	}

	private function _init() {
		$this->id	= $this->getRequest('id',1);
		$this->sort		= $this->getRequest('sort',1);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function editBannerSort(){
		return Ad::getInstance()->editBannerSort($this->id,$this->sort);
	}
}
