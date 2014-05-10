<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\Article\Article;

class Add_user_article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $articleId;
	private $hotUserCategory;
	private $hotUserTag;
	private $hotUserCategoryId;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->articleId){
			$status = $this->addUserArticle();
			$this->ajaxDialog("daren_user_article");
		}
		$this->view->hotUserCategoryList = $this->getHotUserCategory();
		$this->view->hotUserTagList		 = $this->getHotUserTag();
	}

	private function _init() {
		$this->articleId					= $this->getRequest("articleId",0);
		$this->hotUserCategory				= $this->getRequest("hotUserCategory",0);
		$this->hotUserTag					= $this->getRequest("hotUserTag",0);
		$this->hotUserCategoryId			= $this->getRequest("hotUserCategoryId",1);
		return $this->_check();
	}
	
	private function getHotUserCategory(){
		return Article::getInstance()->getHotUserCategoryAll();
	}

	private function getHotUserTag(){
		$tagAll =  Article::getInstance()->getHotUserTagAll();
		$data	= array();
		if($this->hotUserCategoryId){
			foreach($tagAll as $ta){
				if($this->hotUserCategoryId == $ta->hot_user_category_id)
				$data[] = $ta;
			}
		}else{
			$data = $tagAll;
		}
		return $data;
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function addUserArticle(){
		$tmpId		= str_replace('，',',',$this->articleId);
		$tmpId		= str_replace(' ','',$tmpId);
		$articleIds = explode(',',$this->articleId);
		return Article::getInstance()->addUserArticle($articleIds,$this->hotUserCategory,$this->hotUserTag); 
	}
	private function forward($forwardUrl,$status=200,$message="成功"){
			$array = array(
						'statusCode'	=> $status,
						'message'		=> $message,
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
}
