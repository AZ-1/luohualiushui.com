<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;
use Gate\Package\Maidan\Maidan;

class Batch_check_article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = true;
	//private	$pageNum;
	private $id;
	private $quality = '';
	private $is_check;
	private $no_pass_reason;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->quality && !$this->no_pass_reason){
			$this->upCheck();
			$this->forward("/article/index");
		}else if($this->no_pass_reason){
			$this->upReason();
			$this->forward("/article/index");
		}
	}

	private function _init() {
			//$this->pageNum = $this->getRequest('pageNum','1');
			$this->id				= $this->getRequest('article_id','1');
			$this->quality		= $this->getRequest('quality','1');
			$this->no_pass_reason = $this->getRequest('reason','1');

			return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function upCheck(){
		$article_ids = explode(',',$this->id);
		foreach($article_ids as $ai=>$v){
			if($v){
				Article::getInstance()->upCheck($v,$this->quality);
			}
		}
		return true;	
	}

	private function upReason(){
		$article_ids = explode(',',$this->id);
		foreach($article_ids as $ai=>$v){
			if($v){
				Article::getInstance()->upReason($v,$this->no_pass_reason);
			}
		}
		return true;
	}

	private function PageTotal(){
		return Article::getInstance()->PageTotal();
	}

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '审核完成',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	
	}

}
