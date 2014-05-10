<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Ranking;

class Edit_rank extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private $list_name;
	private $pid;
	private $id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->list_name || $this->pid){
				$error = '';
				$isAdd = $this->updateRank();
				if($isAdd){
					$rs = new \stdClass;                                                                                                                                                       
					$rs->statusCode     = '200';
					$rs->message        = '保存成功';
					$rs->navTabId       = '';
					$rs->rel            ='';
					$rs->callbackType   = 'forward';
					$rs->forwardUrl     = '/article/index';
					echo json_encode($rs);
					die();     
				}
			}
		$this->view->rank				= $this->getRank();
	}

	private function _init() {
		$this->list_name			=    $this->getRequest("list_name");
		$this->pid					=    $this->getRequest("pid");
		$this->id					=    $this->getRequest("id");
		return $this->_check();
	}

	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}

	private function updateRank(){
		$data = array(
			'list_name' => $this->list_name	
		);
		return Ranking::getInstance()->updateRank($data , $this->id);
	}


	private function getRank(){
		return Ranking::getInstance()->getRank($this->id);
	}
}
