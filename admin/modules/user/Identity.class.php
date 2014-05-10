<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\User;
use Gate\Package\User\Userinfo;

class Identity extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->identityList = $this->getIdentityList();

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
	private function getIdentityList(){
		return Userinfo::getInstance()->getIdentityList(); 
	}
}
