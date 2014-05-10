<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Cosmetic;
class Edit_comment extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $comment_id;
	private $comment_content;
	private $isUp = 0;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->isUp){
			 $upRes = $this->upComment();
			 $rs = new \stdClass;                                                                                                                                                   
			 $rs->navTabId       = "/cosmetic/comment?cosmetic_id=$this->comment_id";
			 $rs->rel            ='';
			 $rs->forwardUrl     = "/cosmetic/comment";
			 if($upRes){
				 $rs->statusCode     = '200';
				 $rs->message        = '保存成功';
				  $rs->callbackType   = 'closeCurrent';
			 }else{
				  $rs->statusCode     = '300';
				  $rs->message        = '输入有误';
				  $rs->callbackType   = 'forward';
			}
		   echo json_encode($rs);
		   die();     
		}
	}
	private function _init() {
		$this->comment_id = $this->getRequest('comment_id', 1);
		$this->isUp = $this->getRequest('isUp',1);
		$comment = Cosmetic::getInstance()->getCommentByID($this->comment_id);
		$this->view->comment_content = '';
		$this->view->comment_id = $this->comment_id;
		if($comment){
			$this->view->comment_content = $comment->content;
			$this->view->comment_id = $comment->id;
		}
		$this->comment_content = $this->getRequest("comment_content");
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getBrand(){
		return Brand::getInstance()->getBrand($this->id);
	}
	private function upComment()
	{
		return Cosmetic::getInstance()->upComment($this->comment_id,$this->comment_content);
	}
}
