<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Article\Navigation AS NavP;

class Navigation extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = true;

	private $navigationName;
	private $pid;
	private $sort;
	private $navigationId;
	private $isHot;

	public function run() {

	}

	private function _init() {
		return  $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

}
