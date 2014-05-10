<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Topic;
use Gate\Package\Maidan\Maidan;
use Gate\Package\Article\Topic;

class Topic_article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private	$length = 20;
	private	$pageNum;
	private $article_id;	
	private $id;

	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->article_id){
				// 删除
				if($this->delTopicArticle()){
					$this->forward("/topic/topic_article?id=$this->id");
				}
			}
		$this->view->getTopicArticle = $this->getTopicArticle();
		$this->view->id              = $this->id;   
		$this->page();
	}
	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Topic::getInstance()->getTopicArticleNum($this->id);
		$this->view->page = $page;
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

	private function _init() {
		 $this->pageNum    = $this->getRequest("pageNum",1);
		 $this->article_id = $this->getRequest("aid",1);
		 $this->id         = $this->getRequest("id",1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function delTopicArticle(){
		return Topic::getInstance()->delTopicArticle($this->article_id);
	}
	private function _check(){
		return TRUE;
	}
	private function getTopicArticle(){
		 $this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
	     $offset         = ($this->pageNum-1) * $this->length; 
		return Topic::getInstance()->getTopicArticle($this->id,$offset,$this->length);
	
	}
}
