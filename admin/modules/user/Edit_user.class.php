<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\User;
use Gate\Package\User\Userinfo;
class edit_user extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private $id;
	private $old_id;
	private $identity;
	private $newId;
	private $unbind;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			// 显示
		if($this->identity || $this->newId || $this->old_id){
			$result_upUser = $this->upUser();
			if(is_array($result_upUser)){
					$this->forward("/user/edit_user?id=$this->id",300,$result_upUser['message']);
			}else{
					$this->forward("/user/edit_user?id=$this->id",200,'绑定成功');
			}
		}
		$this->view->userInfo = $this->getUserById();
		$this->view->identity    = $this->getIdentityList();
	}

	private function _init() {
		$this->id		= $this->getRequest('id',1);
		$this->old_id		= $this->getRequest('old_id',1);
		$this->unbind		= $this->getRequest('unbind',1);
		$this->identity    = $this->getRequest('identity',1);
		$this->newId    = $this->getRequest('newId',1);
		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		return TRUE;
	}
	private function getUserById(){
		return Userinfo::getInstance()->getUserById($this->id);
	}
	private function getIdentityList(){
		return UserInfo::getInstance()->getIdentityList();
	}
	private function userUnbind(){
		return UserInfo::getInstance()->userUnbind($this->id,$this->old_id);
	}
	private function upUser(){
		return UserInfo::getInstance()->upUser($this->id,$this->identity,$this->newId,$this->old_id);
	}
	private function forward($forwardUrl,$statusCode=200,$message='成功'){
			$array = array(
						'statusCode'	=> $statusCode,
						'message'		=> $message,
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
}
