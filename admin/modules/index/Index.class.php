<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
class Index extends \Gate\Libs\Controller{
    //protected $view_switch = true;

	public function run() {
		if (!$this->_init()) {return FALSE;}
			$this->redirect('/dwz/index.html');
	}

	private function _init() {

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

}
