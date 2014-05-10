<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;

class Del_tag extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = false;
	private $id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->id){
			$isD = $this->delTag();
			if($isD){
				$this->ajaxForward('/article/tag');
			}else{
				$this->ajaxForward('/article/tag', '删除失败', true);
			}
		}
	}

	private function _init() {
		$this->id = $this->getRequest("id",1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function delTag(){
		return Article::getInstance()->delTag($this->id);
	
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
