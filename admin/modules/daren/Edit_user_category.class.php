<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\Article\Article;
class Edit_user_category extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
	//protected $view_switch = FALSE;
	private	$id;
	private	$name;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->name){
				$isUp = $this->upHotUserCategory();
				if($isUp){
					$this->ajaxDialog('daren_user_category');
				}
			}
		$this->view->userCategory = $this->getHotUserCategoryById();
	}


	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}

	private function _init() {
		$this->name		= $this->getRequest("name",0);
		$this->id		= $this->getRequest('id', 1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function getHotUserCategoryById(){
		return Article::getInstance()->getHotUserCategoryById($this->id);
	}

	private function upHotUserCategory(){
		return Article::getInstance()->upHotUserCategory($this->id,$this->name);
	}
}
