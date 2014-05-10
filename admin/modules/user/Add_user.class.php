<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\User;
use Gate\Package\User\Userinfo;
class Add_user extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $realname;
	private $password;
	private $identity;
	private $id;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($this->realname){
			$isAdd = $this->addUser();
			if($isAdd){
				$this->forward('/user/user');
			}
		}
		if($this->id){
			$this->noDelUser();
				$this->forward('/user/user');
		}
		// 显示
		// $this->view;
		$this->view->identity = $this->getIdentityList();

	}

	private function _init() {
		$this->realname		= $this->getRequest('realname',1);
		$this->password	= $this->getRequest('password',1);
		$this->identity	= $this->getRequest('identity',1);
		$this->id	= $this->getRequest('id',1);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function addUser(){
		$data = array("realname"=>$this->realname,"password"=>$this->password,"identity"=>$this->identity);
		return Userinfo::getInstance()->addUser($data);
	}
	private function noDelUser(){
		return Userinfo::getInstance()->noDelUser($this->id);
	}

	private function getIdentityList(){
		return Userinfo::getInstance()->getIdentityList();
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
