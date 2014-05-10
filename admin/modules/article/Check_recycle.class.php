<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;
use Gate\Package\Maidan\Maidan;

class Check_recycle extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $id;
	private $check;//判断是不是有看过文章然后提交过来的

	public function run() {
		if (!$this->_init()) {return FALSE;}

		if($this->check){
			$this->recycle();
			$this->forward("/article/recycle");
		}
		$this->view->article = $this->getArticleById();
	}

	private function recycle(){
		return Article::getInstance()->recycle($this->aid);
	}

	private function _init() {
		$this->id = $this->getRequest('id','1');
		$this->check = $this->getRequest('check','1');
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	// 调用带分类所有文章
	private function getArticleById(){
		return Article::getInstance()->getArticleById($this->id,"article_id,title,content",1);
	}

	private function PageTotal(){
		return Article::getInstance()->PageTotal();
	}
	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '回收完成',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	
	}

}
