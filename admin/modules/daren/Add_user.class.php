<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\User\Userinfo;
class Add_user extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $user_id;
	private $grade;
	private $keyword;
	private	$pageNum;
	private $length = 20;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($this->grade){
			$isAdd = $this->addDaren();
			$this->ajaxDialog('daren_user');
				//$this->forward('/daren/user');
			//$this->redirect('/daren/user');
		}
		// 显示
		// $this->view;
		$this->view->grade    = $this->getGradeList();
		$this->view->userList    = $this->getUserInfoList();
		if($this->keyword){
			$this->view->userList = $this->getSearchDaren();
		}
		$this->page();

	}

	private function _init() {
		$this->pageNum = $this->getRequest('pageNum', 1);
		$this->user_id		= $this->getRequest('user_id',1);
		$this->grade	    = $this->getRequest('grade',1);
		$this->keyword      = $this->getRequest('keyword',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function addDaren(){
		return Userinfo::getInstance()->addDaren($this->grade);
	}


	private function getGradeList(){
		return Userinfo::getInstance()->getGradeList();
	}


	private function getUserInfoList(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length;
		return Userinfo::getInstance()->getDaren($offset,$this->length);
	}


	private function getSearchDaren(){
		return Userinfo::getInstance()->getSearchDaren($this->keyword);
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



	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Userinfo::getInstance()->getDarenTotalNum();
		$this->view->page = $page;
	}
}
