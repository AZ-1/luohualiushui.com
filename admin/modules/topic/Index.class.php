<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Topic;
use Gate\Package\Maidan\Maidan;
use Gate\Package\Article\Topic;

class Index extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private	$length = 20;
	private	$pageNum;
	
	private $keyword;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$this->view->getTopic=$this->getTopicList();
		
		if($this->keyword){
			$this->view->getTopic = $this->getSearchTopic();
		}
		$this->page();
	}
	private function getSearchTopic(){
		return Maidan::getInstance()->getSearchTopic($this->keyword);
	}
	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Topic::getInstance()->getTopicTotalNum();
		$this->view->page = $page;
	}


	private function _init() {
		 $this->pageNum = $this->getRequest("pageNum",1);
		 $this->keyword = $this->getRequest("keyword",0);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getTopicList(){
		 $this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
	     $offset         = ($this->pageNum-1) * $this->length; 
		return Maidan::getInstance()->getTopicList($offset,$this->length);
	
	}

}
