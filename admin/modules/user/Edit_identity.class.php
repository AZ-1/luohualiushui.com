<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\User;
use Gate\Package\User\Userinfo;

class Edit_identity extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $id;
	private $identity;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->identity){
				$this->upIdentity();
				$this->forward("/user/identity");
			}
			$this->view->identity = $this->getIdentity();

	}

	private function _init() {
		$this->id  =  $this->getRequest('id',1);
		$this->identity  =  $this->getRequest('identity',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getIdentity(){
		return Userinfo::getInstance()->getIdentity($this->id); 
	}
	private function upIdentity(){
		return Userinfo::getInstance()->upIdentity($this->id,$this->identity); 
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
