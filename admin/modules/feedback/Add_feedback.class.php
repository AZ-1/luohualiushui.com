<?php
/*
 * 
 * @author wuhui
 */
namespace Gate\Modules\Feedback;
use Gate\Package\User\Userinfo;

class Add_feedback extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private		$user_id;
	private		$content;
	private		$create_time;
	private		$client_type;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->user_id)
			$this->addFeedback();
	}

	private function _init() {
		$this->user_id		= $this->getRequest('user_id', 1);
		$this->content		= $this->getRequest('content', 0);
		$this->create_time	= $this->getRequest('create_time', 0);
		$this->client_type	= $this->getRequest('client_type', 0);

		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		if( !$this->content ){
			return FALSE;
		}
		return TRUE;
	}

	private function addFeedback(){
		$data = array('user_id'=>$this->user_id,'content'=>$this->content,'create_time'=>$this->create_time,'client_type'=>$this->client_type);
		return Userinfo::getInstance()->addFeedback($data);
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
