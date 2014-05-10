<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Topic;
use Gate\Package\Article\Topic;
class add_topic_article extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private $article;
	private $id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->article){
				$this->upTopicArticle();
				$this->forward("/topic/topic_article?id=$this->id");
			}
		$this->view->id = $this->id;
	}

	private function _init() {
		$this->id				= $this->getRequest('id',1);
		$this->article			= $this->getRequest('article',0);
		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		if(!$this->id && empty($this->article)){
			return FALSE;
		}
		return TRUE;
	}

	private function upTopicArticle(){
		if(is_array($this->article)){
			$article_ids = $this->article;
		}else{
			$article_ids = explode(',',$this->article);
		}
		foreach($article_ids as $ai=>$v){
			if(!$v){
				unset($article_ids[$ai]);
			}
		}
		return Topic::getInstance()->updateTopicArticle($this->id,$article_ids);
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
