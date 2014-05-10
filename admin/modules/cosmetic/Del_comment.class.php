<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Cosmetic ;

class Del_comment extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = false;
	private $id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->id){
			$isD = $this->delComment();
			if($isD){
				$this->ajaxForward('/cosmetic/comment');
			}else{
				$this->ajaxForward('/cosmetic/comment', '删除失败', true);
			}
		}
	}

	private function _init() {
		$this->id = $this->getRequest("del_id",1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function delComment(){
		return Cosmetic::getInstance()->delComment($this->id);
	
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
