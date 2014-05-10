<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Benefits as Benefit;

class Add_benefits extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
   //protected $view_switch = FALSE;
	private $name ;
	private $pid;
	private $isAdd = 0;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->isAdd == 1 ){
			$isAdd = $this->addBenefit();
			$this->dialog($isAdd);
		} 
}

	private function _init() {
		$this->pid  =  $this->getRequest("base_id",1);
		$this->name = $this->getRequest("name");
		if('' != $this->name){
			$this->isAdd = 1;
		}			
		$this->view->cur_id = $this->pid;
		return $this->_check();
	}

	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}
	private function addBenefit(){
		return Benefit::getInstance()->addBenefits($this->pid,$this->name);
	}
	private function dialog($isAdd){
			$error = '';
			$mess = "失败";
			$url = "cosmetic/Add_benefits?base_id=$this->pid";
			$callback = "forward";
			$state = '300';
			if($isAdd){
					$mess="成功";
					$url = "/cosmetic/Benefits?requested_id=0";
					$callback = "closeCurrent";
					$state = '200';
				}
				$rs = new \stdClass;                                                                                                                                                       
				$rs->statusCode     = $state;
				$rs->message        = $mess;
				$rs->navTabId       = '';
				$rs->rel            ='';
				$rs->callbackType   = $callback;
				$rs->forwardUrl     = $url;
				echo json_encode($rs);
				die();     
	}
}
