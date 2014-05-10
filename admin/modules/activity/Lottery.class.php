<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Activity;
#use Gate\Package\
use Gate\Package\User\Userinfo;
use Gate\Package\Activity\Prize;
class Lottery extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
//	protected $view_switch = FALSE;
	private	$pageNum;
	private	$length = 20;
	private $keyword;
	private $prize_arr = array('1'=>'1000元',
							'2'=>'100元',
							'3'=>'50元',
							'4'=>'10元',
							'5'=>'5元',
							'6'=>'2元',
							'7'=>'1元'
						);
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->keyword){
			$this->view->lotteryList = $this->getLotteryListById($this->keyword);
		}else{
			$this->view->lotteryList = $this->getLotteryList();
		}
		$this->page();
	}

	private function getLotteryList(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset         = ($this->pageNum-1) * $this->length; 
		$prize_list = Prize::getInstance()->getLotteryList($offset,$this->length);
		foreach($prize_list as $v){
			 $prize = explode(',',$v->prize);
			 $prize_re = array();
			 foreach($prize as $vv){
				 if($vv != 8 && $vv !=''){
					 $prize_re[] = $this->prize_arr[$vv];
				 }
			 }
			 $v->prize = implode(',',$prize_re);
		}
		return $prize_list;
	}

	private function getLotteryListById($user_name){
		$prize_list = Prize::getInstance()->getLotteryList(0,1,$user_name);
		if(!$prize_list){
			return FALSE;
		}
		foreach($prize_list as $v){
			 $prize = explode(',',$v->prize);
			 $prize_re = array();
			 foreach($prize as $vv){
				 if($vv != 8 && $vv !=''){
					 $prize_re[] = $this->prize_arr[$vv];
				 }
			 }
			 $v->prize = implode(',',$prize_re);
		}
		return $prize_list;
	}


	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Prize::getInstance()->getTotoleLotteryNum();
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
