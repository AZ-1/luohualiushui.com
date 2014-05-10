<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Article\Article;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Topic;
use Gate\Package\Article\HotTag;

class Add_hot_article extends \Gate\Libs\Controller{
    protected $view_switch = true;
	private $article_id;
	private $hot_category_id;
	private $ids = array();
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->article_id && $this->hot_category_id){
				$this->addHotTag();
				$this->forward("/index/article");
			}
		
	}

	private function _init() {
		$this->article_id = $this->getRequest("article_id",1);
		$this->hot_category_id = $this->getRequest("hot_category_id",1);
		$this->ids = $this->getIds($this->article_id);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
//		foreach($this->ids as $v){
//			if(is_numeric($v)){
//				return FALSE;
//			}
//		}
		return TRUE;
	}

	private function addHotTag(){
		$categoryId = $this->hot_category_id;
		foreach($this->ids as $v){
			$articleId = $v;
			HotTag::getInstance()->addHotTag($articleId, $categoryId);
		}
		return true;
	}

	private function getIds($article_id){
		$article_id = str_replace(' ', '', $article_id);
		return explode(',',trim($article_id));
	}
		
	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '添加成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			echo json_encode($array);
			exit();
	}
}
