<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Article\Article;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Topic;
use Gate\Package\Maidan\Maidan;
class Edit_article extends \Gate\Libs\Controller{
    protected $view_switch = TRUE;
    private $id;
    private $category_id;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 编辑
			if($this->category_id){
			$isUp = $this->upHotArticle();
			if($isUp){
				$rs = new \stdClass;
				$rs->statusCode		= '200';
				$rs->message		= '保存成功';
				$rs->navTabId		= '';
				$rs->rel			='';
				$rs->callbackType	= 'forward';
				$rs->forwardUrl		= '/index/article';

				echo json_encode($rs);
				die();

			} 
		}

		// 显示
			    $this->view->article = 	$this->getHotArticle();
				$this->view->categoryList = $this->getHotCategoryList();
	}

	private function _init() {
		
		 $this->id			        = $this->getRequest('aid',1);
		 $this->category_id			= $this->getRequest('category_id',1);
		 
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function getHotArticle(){
		return Article::getInstance()->getHotArticle($this->id);
	}
	private function getHotCategoryList(){
		return Article::getInstance()->getHotCategoryList();
	}

   
	private function upHotArticle(){
		return Article::getInstance()->updateHotArticle($this->id,$this->category_id);
	} 
}
