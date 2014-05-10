<?php
namespace Gate\Modules\Index;
use Gate\Package\Article\HotTag;

class Del_hot_article extends \Gate\Libs\Controller{
    protected $view_switch = FALSE;
	private $article_id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if(!empty($this->article_id)){
			$isDel = $this->delHotTag();
			if($isDel){
				$this->forward('/index/article');
			}
		}
	}

	private function _init() {
		$this->article_id = $this->getRequest('aid', 1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function delHotTag(){
		return HotTag::getInstance()->delHotTag($this->article_id);		
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
