<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Ranking as R;

class Ranking extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->rank = $this->showRank();
	}

	private function _init() {
		return $this->_check();
	}

	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}
	private function showRank(){
		$offset = 0;
		$length = 100;
		return R::getInstance()->showRank($offset , $length);
	}
}
