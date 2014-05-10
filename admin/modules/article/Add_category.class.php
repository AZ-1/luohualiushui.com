<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Category;

class Add_category extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected	$view_switch = FALSE;
	private		$categoryId;
	private     $name;

	public function run() {
		if (!$this->_init()) {return FALSE;}

		// 新增
		if( $this->name!='' && $this->categoryId > 0 ){
			$isUp = $this->addChildCategory();
			if($isUp){
				$this->dialog('article_categoroy');
			} 
		}

		// 显示
	    $this->view->category = $this->getCategory();
	}

	private function _init() {
	    $this->categoryId   = $this->getRequest('cid',1);
		$this->name			= $this->getRequest("name",1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		if( !$this->categoryId){
			return FALSE;
		}
		return TRUE;
	}
	private function addChildCategory(){
		return Category::getInstance()->addChild($this->name, $this->categoryId);
	}

	private function getCategory(){
		$list = Category::getInstance()->getCategorybyIds(array($this->categoryId));
		return current($list);
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