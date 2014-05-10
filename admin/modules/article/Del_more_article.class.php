<?php
/*
 * 
 * @author wanghaihong
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;

class Del_more_article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private		$articleId;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$isDel=$this->delMoreArticle();
		if($isDel){
		}
	}

	private function _init() {
		$this->articleId = $this->getRequest('aid', 1);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		if( !$this->articleId ){
			return FALSE;
		}
		return TRUE;
	}

	private function delMoreArticle(){
		$article_ids = explode(',',$this->articleId);
		foreach($article_ids as $ai=>$v){
			if($v){
				Article::getInstance()->delArticle($v);
			}
		}
		return true;
	}


	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '删除成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			echo json_encode($array);
			exit();
	}
}
