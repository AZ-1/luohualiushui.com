<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
/*use Gate\Package\Article\Article;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Topic;*/
use Gate\Package\Maidan\Maidan;
class Del_article extends \Gate\Libs\Controller{
    protected $view_switch = FALSE;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		$isDel = $this->delArticle($this->id);
		if($isDel){
			$this->forward("/index/article");
		}

	}

	private function _init() {
		$this->id=$this->getRequest('aid',1);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function delArticle($id){
		return Maidan::getInstance()->delArticle($id);
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
