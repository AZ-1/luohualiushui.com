<?php
/*
 * 进行文章回收
 * @author huizai
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;
use Gate\Package\Article\Topic;
use Gate\Package\Article\Category;
use Gate\Package\Ad\Ad;

class Recycle extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private	$pageNum;
	private	$length = 20;
	private	$title;
	private	$realname;
	private	$quality;
	private	$category;
	private	$tag;
	private	$qualityValue;
	private	$is_check;
	private $is_delete = 0;

	public function run() {

		if (!$this->_init()) {return FALSE;}
			$this->view->articleList		= $this->getSearchArticle();
			$this->view->title				=  $this->title;
			$this->view->tag				=  $this->tag;
			$this->view->realname			=  $this->realname;
			$this->view->quality			=  $this->quality;
			$this->view->searchCategory		=  $this->category;
			$this->view->category			=  Category::getInstance()->getCategoryList(); 	
			$this->view->topicList			=  $this->getTopic();
			$this->view->tagList			=  $this->getTagList();
			$this->page();
	}



	private function _init() {

		  $this->pageNum	= $this->getRequest('pageNum',0);
		  $this->is_delete	= $this->getRequest('is_delete',0) ? $this->getRequest('is_delete',0) : 0;
		  $this->title		= $this->getRequest('title',0);
		  $this->tag		= $this->getRequest('tag',1);
		  $this->realname	= $this->getRequest('realname',0);
		  $this->quality	= $this->getRequest('quality',1);
		  $this->category	= $this->getRequest('category',1);
		  if(strstr($this->quality,"上")){
			  $this->qualityValue = 1;
		  }elseif(strstr($this->quality,"中")){
			  $this->qualityValue = 2;
		  }elseif(strstr($this->quality,"下")){
			  $this->qualityValue = 3;
		  }elseif($this->quality==='未审核'){
			  $this->qualityValue = 0;
			  $this->is_check = 0;
		  }elseif($this->quality==='未通过'){
			  $this->qualityValue = 0;
			  $this->is_check = 1;
		  }elseif($this->quality==='通过'){
			  $this->qualityValue = 0;
			  $this->is_check = 2;
		  }
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	//更具搜索条件来显示文章
	private function getSearchArticle(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length;
		$search = array('title'=>$this->title,'realname'=>$this->realname,'quality'=>$this->qualityValue, 'is_check'=>$this->is_check,'category'=>$this->category,'tag'=>$this->tag,'is_delete'=>1);
		return Article::getInstance()->getSearchArticle($search,$offset,$this->length);
	}
	
	//获得话题
	private function getTopic(){
		return Topic::getInstance()->getTopicList(0, 100);
	}

	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$search = array('title'=>$this->title,'realname'=>$this->realname,'quality'=>$this->qualityValue, 'is_check'=>$this->is_check,'category'=>$this->category,'is_delete'=>1);
		$page->totalNum = Article::getInstance()->getArticleTotalNum($search);
		$this->view->page = $page;
	}

	
	private function getTagList(){
		return Article::getInstance()->getTagList(0, 100);
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
