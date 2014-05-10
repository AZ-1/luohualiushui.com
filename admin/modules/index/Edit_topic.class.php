<?php
/*
 * 
 * @author  haochaofei 
 */
namespace Gate\Modules\Index;
use Gate\Package\Article\Article;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Topic;
use Gate\Package\Maidan\Maidan;
class Edit_topic extends \Gate\Libs\Controller{
    protected $view_switch = TRUE;
    private $articleId;
    private $editId;
    private $editArticleIds;
	private $editTag;
	private $description;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 编辑
		if($this->editId && $this->editArticleIds && $this->editTag && $this->des){
			$isUp = $this->updateTopic();
			if($isUp){
				$rs = new \stdClass;
				$rs->statusCode		= '200';
				$rs->message		= '保存成功';
				$rs->navTabId		= '';
				$rs->rel			='';
				$rs->callbackType	= 'forward';
				$rs->forwardUrl		= '/index/topic';

				echo json_encode($rs);
				die();

			} 
		}

		// 显示
	    $this->view->topic = $this->topicWhere();

	}

	private function _init() {
		
		   $this->editId			=$this->getRequest('topic_id',1);
		   $this->editArticleIds	=$this->getRequest('title',1);
		   $this->editTag		    =$this->getRequest('pic',1);
		   $this->des			    =$this->getRequest('description',1);
		 
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    

	private function topicWhere(){
	
		return Maidan::getInstance()->topicWhere($this->editId);
	
	}
   
	private function updateTopic(){
		return Maidan::getInstance()->updateTopic($this->editId,$this->editArticleIds,$this->editTag,$this->des);
	} 
}
