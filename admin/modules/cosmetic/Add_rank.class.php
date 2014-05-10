<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Ranking;

class Add_rank extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private $list_name;
	private $pid;
	private $classify_id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->list_name){
				$error = '';
				$isAdd = $this->addRank();
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
		$this->view->topRank = $this->getTopRank();
		$this->view->funList = $this->getFunList();
	}

	private function _init() {
		$this->list_name			=    $this->getRequest("list_name");
		$this->classify_id			=    $this->getRequest("classify_id");
		$this->pid					=    $this->getRequest("pid");
		return $this->_check();
	}

	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}
	//得到所有分类
	private function getFunList(){
		return Ranking::getInstance()->getFunList();
	}

	private function getTopRank(){
		return Ranking::getInstance()->getTopRank();
	}

	private function addRank(){
		$data = array(
			'pid'		=> $this->pid,
			'list_name' => $this->list_name,
			'classify_id' => $this->classify_id	
		);
		return Ranking::getInstance()->addRank($data);
	}
}
