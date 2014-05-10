<?php
/*
 * 删除热门话题
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Article\Topic;

class Del_hot_topic extends \Gate\Libs\Controller{
    protected $view_switch = FALSE;
	private $topic_id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		$isDel = $this->delTopic();
		if($isDel){
			$this->forward('/index/topic');
		}
	}

	private function _init() {
		$this->topic_id = $this->getRequest('topic_id', 0);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function delTopic(){
		return Topic::getInstance()->delHotTopic($this->topic_id);
	}

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '删除成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}

}
