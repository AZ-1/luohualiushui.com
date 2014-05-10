<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Activity;
#use Gate\Package\
use Gate\Package\User\Userinfo;
use Gate\Package\Activity\Prize;
class Shoppinglist extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
//	protected $view_switch = FALSE;
	private	$pageNum;
	private	$length = 20;
	private $keyword;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->keyword){
			$this->view->Shoppinglist = $this->getShoppingListById($this->keyword);
		}else{
			$this->view->Shoppinglist = $this->getShoppingList();
		}
		$this->page();
	}

	private function getShoppingList(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length; 
		$prize_list = Prize::getInstance()->getShoppingList($offset,$this->length);
		return $prize_list;
	}

	private function getShoppingListById($user_name){
		$prize_list = Prize::getInstance()->getShoppingList(0,1,$user_name);
		return $prize_list;
	}


	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		if($this->keyword){
			$page->totalNum =	Prize::getInstance()->getTotalShoppingIdNum($this->keyword);
		}else{
			$page->totalNum =	Prize::getInstance()->getTotalShoppingNum();
			}
		$this->view->page = $page;
	}

	private function _init() {
		  $this->pageNum = $this->getRequest('pageNum',1);
		  $this->keyword = $this->getRequest("keyword",0);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
}
