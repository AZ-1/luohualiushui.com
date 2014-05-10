<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Topic;
use Gate\Package\Maidan\Maidan;
class Is_online_topic extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $topicId;
	private $online;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->online){
			$isOnline = $this->onlineTopic();
		}else{
			$isOnline = $this->offlineTopic();
		}
		if($isOnline){
			$this->forward('/topic/index');
		}
	}

	private function _init() {
		$this->topicId			= $this->getRequest('topic_id',1);
		$this->online		= $this->getRequest('online',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	/*
	 * 上线下线
	 */
	private function onlineTopic(){
		return Maidan::getInstance()->onlineTopic($this->topicId);
	}
	private function offlineTopic(){
		return Maidan::getInstance()->offlineTopic($this->topicId);
	}

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '操作成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			echo json_encode($array);
			exit();
	}
}
