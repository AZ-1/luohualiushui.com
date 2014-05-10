<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;
use Gate\Package\Article\Category;

class Edit_tag extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected	$view_switch = true;
	private		$id;
	private     $name;
	private     $category_id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->name){
				$isUp = $this->upTag();
				if($isUp){
					$this->ajaxDialog('article_tag');
				}else{
					$error = '保存失败，可能同类目下的标签已存在';
					$this->ajaxDialog('article_tag', $error, true);
				}
			}
		$this->view->categoryList = $this->getCategoryList();
		$this->view->tag = $this->getTag();
		
	}

	private function _init() {
		$this->id = $this->getRequest('id', 1);
		$this->name = $this->getRequest('name', 1);
		$this->category_id = $this->getRequest('category_id', 1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getTag(){
		return Article::getInstance()->getTag($this->id);
	}
	private function upTag(){
		return Article::getInstance()->upTag($this->id,$this->name,$this->category_id);
	}
	private function getCategoryList(){
		return Category::getInstance()->getCategoryList();
	}
	private function dialog($navTabId){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '成功',
						'navTabId'		=> $navTabId,
						'rel'			=> '',
						'callbackType'	=> 'closeCurrent',
						'forwardUrl'	=> '');
			die( json_encode($array));
	}
}
