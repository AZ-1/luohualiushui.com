<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\User\Userinfo;
class Add_hot_daren extends \Gate\Libs\Controller{
    protected $view_switch = TRUE;
	private $user_id=0;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($this->user_id){
			$rs = $this->addHotDaren();
			if($rs['status']){
				$this->ajaxDialog('index_daren');
			}else{
				$this->ajaxDialog('index_daren', $rs['error'], true);
			}
		}

		//显示
		//$this->view
	}

	private function _init() {
		$this->user_id = $this->getRequest("user_id",1);
		$this->user_id = str_replace(' ', '', $this->user_id);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		if($this->user_id==''){
			return FALSE;
		}
		return TRUE;
	}
    
	private function addHotDaren(){
		$userIds = explode(',', $this->user_id);
		return Userinfo::getInstance()->addHotDaren($userIds);
	}
}
