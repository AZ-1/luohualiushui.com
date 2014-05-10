<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\User\Userinfo;
class Del_daren extends \Gate\Libs\Controller{
	protected $view_switch = FALSE;
	private $id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		$isDel = $this->delHotDaren();
		if($isDel){
			$this->forward("/index/daren");
		}

	}

	private function _init() {
		$this->id = $this->getRequest('id',1);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function delHotDaren(){
		return Userinfo::getInstance()->delHotDaren($this->id);
	}
	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '编辑成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}

}
