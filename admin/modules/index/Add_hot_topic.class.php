<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Article\Article;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Topic;
class Add_hot_topic extends \Gate\Libs\Controller{
    protected $view_switch = true;
	private $topic_id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($this->topic_id!=''){
			$isAdd = $this->addHotTopic();
			if($isAdd){
				$this->forward('/index/topic');
			}
		}
		// 显示
		// $this->view
	}

	private function _init() {
		$this->topic_id = $this->getRequest('topic_id', 0);
		$this->topic_id = str_replace(' ', '', trim($this->topic_id));
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function addHotTopic(){
		$topicIds = explode(',', $this->topic_id);
		return Topic::getInstance()->addHotTopic($topicIds);
	}

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '添加成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
		
}
