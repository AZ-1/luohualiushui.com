<?php
/*
 * 
 * @author wanghaihong
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Category;

class Del_category extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private		$categoryId;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$isD=$this->delCategory();
		if($isD){
			$this->ajaxForward('/article/category');
		}else{
			$this->ajaxForward('/article/category', '删除失败', true);
		}
	}

	private function _init() {
		$this->categoryId = $this->getRequest('cid', 1);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		if( !$this->categoryId ){
			return FALSE;
		}
		return TRUE;
	}

	private function delCategory(){
		return Category::getInstance()->delToDefault($this->categoryId);
	}
}
