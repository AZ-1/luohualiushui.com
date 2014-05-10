<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\User\Userinfo;
class Edit_user extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = TRUE;
	private $id;
	private $grade;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			// 显示
		if($this->grade){
			$this->upDaren();
			$this->forward("/daren/user");
		}
		$this->view->userInfo = $this->getUserById();
		$this->view->grade    = $this->getGradeList();
	}

	private function _init() {
		$this->id		= $this->getRequest('id',1);
		$this->grade    = $this->getRequest('grade',1);
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
	private function getGradeList(){
		return Userinfo::getInstance()->getGradeList();
	}
	private function upDaren(){
		return UserInfo::getInstance()->upDaren($this->id,$this->grade);
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
