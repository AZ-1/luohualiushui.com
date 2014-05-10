<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\Article\Article;
class Edit_user_tag extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
	//protected $view_switch = FALSE;
	private	$id;
	private	$name;
	private	$category;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->name){
				$isUp = $this->upHotUserTag();
				if($isUp){
					$this->ajaxDialog('daren_user_tag');
				}
			}
		$this->view->userTag = $this->getHotUserTagById();
		$this->view->categoryList = $this->getCategoryList();
	}

	private function getCategoryList(){
		return Article::getInstance()->getHotUserCategoryAll();
	}


	private function _init() {
		$this->name		= $this->getRequest("name",0);
		$this->category		= $this->getRequest("category",1);
		$this->id		= $this->getRequest('id', 1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function getHotUserTagById(){
		return Article::getInstance()->getHotUserTagById($this->id);
	}

	private function upHotUserTag(){
		return Article::getInstance()->upHotUserTag($this->id,$this->name,$this->category);
	}
}
