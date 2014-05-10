<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Topic;
use Gate\Package\Maidan\Maidan;
class Del_topic extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private $id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($this->id){
			$isAdd = $this->delTopic();
			if($isAdd){
				$this->forward('/topic/index');
			}
		}
		// 显示
		// $this->view;

	}

	private function _init() {
		$this->id		= $this->getRequest('id',1);
		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		return TRUE;
	}
	private function delTopic(){
		return Maidan::getInstance()->delTopic($this->id);
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
