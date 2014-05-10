<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Article\Article as ArticleP;
use Gate\Package\Article\HotTag ;

class Article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private	$pageNum;
	private	$length = 20;

	public function run() {
		if (!$this->_init()) {return FALSE;}

		$this->view->hotArticle = $this->getHotArticleList();
		$this->page();
	}

	private function _init() {
		
		$this->pageNum = $this->getRequest('pageNum', 1);

		return $this->_check();
	}
	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		//$page->totalNum = ArticleP::getInstance()->getHotArticleTotalNum();
		$page->totalNum = HotTag::getInstance()->getHotTagTotalNum();
		$this->view->page = $page;
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getHotArticleList(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length; 
		return HotTag::getInstance()->getHotTagList($offset, $this->length);
	//	return ArticleP::getInstance()->getHotArticleList(0,0, $offset, $this->length);
	}
	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '编辑成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
}
