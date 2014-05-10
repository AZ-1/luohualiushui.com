<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\User\Userinfo;
class Recommend extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = false;
	private $id;
	private $is_recommend;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			// 新增
		if($this->id){
			$isAdd = $this->recommend();
			if($isAdd){
				$this->forward('/daren/user');
			}
		}
		// 显示
		// $this->view;

	}

	private function _init() {
		$this->id		= $this->getRequest('id',1);
		$this->is_recommend		= $this->getRequest('is_recommend',1);
		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		return TRUE;
	}
	private function recommend(){

		return Userinfo::getInstance()->recommendDaren($this->id, $this->is_recommend);
	}
	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '操作成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
}
