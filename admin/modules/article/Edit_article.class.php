<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
#use Gate\Package\

class Edit_article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private		$articleId;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		
	}

	private function _init() {
		$this->articleId = $this->getRequest('aid', 1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
}
