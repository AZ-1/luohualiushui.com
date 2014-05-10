<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Benefits as Benefit;

class Edit_Benefits extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $id;
	private $isUp =0;
	private $newname;
	private $pid;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->isUp == 1){
			$upRes = $this->upBenefit();
		    $rs = new \stdClass;                                                                                                                                                   
		    $rs->navTabId       = '/cosmetic/benefits';
		    $rs->rel            ='';
		    $rs->forwardUrl     = "/cosmetic/benefits";
			if($upRes){
				  $rs->statusCode     = '200';
			      $rs->message        = '保存成功';
			      $rs->callbackType   = 'closeCurrent';
			}else{
				  $rs->statusCode     = '300';
			      $rs->message        = '输入有误';
			      $rs->callbackType   = 'forward';
			}
		   echo json_encode($rs);
		   die();     
		}
	}
	private function _init() {
		$this->isUp = $this->getRequest("isUp");
		$this->id = $this->getRequest('benefit_id', 1);
		$item = Benefit::getInstance()->getBenefitsDetail($this->id);
		$this->view->base_item = Benefit::getInstance()->getSubBenefitsName(0);
		if($item){
			$this->view->cur_name = $item->des_info;
			$this->view->benefit_id = $item->id;
			$this->view->benefit_content = $item->des_info;
		}
	//	var_dump($this->view);
		$this->pid = $this->getRequest("select_id",1);
		$this->newname = $this->getRequest("newName",0);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function upBenefit()
	{
		return Benefit::getInstance()->upBenefits($this->newname,$this->pid,$this->id);
	}
	private function dialog($url){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '成功',
						'navTabId'		=> "",
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $url);
		die(json_encode($array));
	}
}
