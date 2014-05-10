<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Links;
use Gate\Package\Links\Links;

class Edit_Links extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $Lid;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 显示
		 $this->view->linksInfo = $this->getLinksOne();

	}

	private function _init() {
		$this->Lid		= $this->getRequest('Lid',1);
		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		return TRUE;
	}
	private function getLinksOne(){
		return Links::getInstance()->getLinksOne($this->Lid);
	}
	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
}
