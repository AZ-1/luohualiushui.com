<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;
use Gate\Package\Article\Category;

class add_tag extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private $name;
	private $category_id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->name){
				$error = '';
				$isAdd = $this->addTag();
				if($isAdd){
					$this->ajaxDialog('article_tag');
				}else{
					$error = '保存失败，可能同类目下的标签已存在';
					$this->ajaxDialog('article_tag', $error, true);
				}
			}
		$this->view->categoryList = $this->getCategoryList();
	}

	private function _init() {
		$this->name           =    $this->getRequest("name",1);
		$this->category_id    =    $this->getRequest("category",1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getCategoryList(){
		return Category::getInstance()->getCategoryList();
	}
	private function addTag(){
		return Article::getInstance()->addTag($this->name,$this->category_id);
	
	}
}
