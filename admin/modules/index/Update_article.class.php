<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Maidan\Maidan;
#use Gate\Package\

class Update_article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;

	public function run() {
		$res=$this->getRequest('position');
		print_r($res); exit;
		if (!$this->_init()) {return FALSE;}
			$this->view->hotArticle=$this->hotArticle();
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

	private function hotArticle(){
	
		return Maidan::getInstance()->hotArticle();
	
	}
	


}
