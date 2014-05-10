<?php
/*
 * 
 * @author wuhui
 */
namespace Gate\Modules\Feedback;
use Gate\Package\User\Userinfo;

class Del_feedback extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private		$feedbackId;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$isDel=$this->delFeedback();
		if($isDel){
		}
	}

	private function _init() {
		$this->feedbackId = $this->getRequest('id', 1);

		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		if( !$this->feedbackId ){
			return FALSE;
		}
		return TRUE;
	}

	private function delFeedback(){
		return Userinfo::getInstance()->delFeedback($this->feedbackId);
	}


	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '删除成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			echo json_encode($array);
			exit();
	}
}
