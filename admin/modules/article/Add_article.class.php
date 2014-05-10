<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;
use Gate\Package\Maidan\Maidan;
use Gate\Package\User\Userinfo;

class Add_article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected	$view_switch = false;
	private		$title;
	private     $count;

	public function run() {
		if (!$this->_init()) {return FALSE;}

		//添加多个文章到话题
		if($this->article && $this->topic_id){
			foreach($this->article as $v){
				$dataAll = array("topic_id"=>$this->topic_id,"article_id"=>$v);
				$isAdd = $this->addTopicArticle(array("topic_id"=>$this->topic_id,"article_id"=>$v));
			}
			if($isAdd){
				$rs = new \stdClass;
				$rs->statusCode     = '200';
				$rs->message        = '保存成功';
				$rs->navTabId       = '';
				$rs->rel            ='';
				$rs->callbackType   = 'forward';
				$rs->forwardUrl     = '/article/index';

				echo json_encode($rs);
				die();     
			}
		}

		//显示	
		$this->view->Listtopic = $this->getTopic();	
		$this->view->Listarticle = $this->articleWhere();	
	}

	private function _init() {
	    $this->editId     = $this->getRequest('aid',1);
		$this->topic      = $this->getRequest("topic",1);
		$this->article_id = $this->getRequest("article_id",1);
		$this->article = $this->getRequest("article",1);
		$this->topic_id = $this->getRequest("topic_id",1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function getTopic(){
		return Maidan::getInstance()->getTopic();
	}

	private function articleWhere(){
	
		return Article::getInstance()->articleWhere($this->editId);
	
	}
	private function addTopicArticle($dataAll){
		return Article::getInstance()->addTopicArticle($dataAll);	
	}
}
