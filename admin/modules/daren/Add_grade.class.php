<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\User\Userinfo;

class Add_grade extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $grade;
	private $value;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->grade){
			$this->addGrade();
			$this->forward("/daren/grade");
		}

	}

	private function _init() {
		$this->grade = $this->getRequest("grade",1);
		$this->value = $this->getRequest("value",1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function addGrade(){
		return Userinfo::getInstance()->addGrade($this->grade,$this->value); 
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
