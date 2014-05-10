<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
#use Gate\Package\

class Is_login extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = false;
    protected $checkLogin = false;

	public function run() {
        if (!$this->_init()) $this->redirect('/bad/badrequest');

		if( !$this->isLogin()){
			$this->view->isLogin =  false;
		}else{
			$this->view->isLogin =  true;
		}
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
