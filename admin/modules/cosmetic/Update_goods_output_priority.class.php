<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Ranking;

class Update_goods_output_priority extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private $goods_id;
	private $output_priority;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		return $this->updateGoodsOutputPriority();
	}

	private function _init() {
		$this->goods_id					=    $this->getRequest("goods_id");
		$this->output_priority			=    $this->getRequest("output_priority");
		return $this->_check();
	}

	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}
	//得到所有分类
	private function updateGoodsOutputPriority(){
		$data = array(
			'output_priority' => $this->output_priority
		);
		return Ranking::getInstance()->updateGoodsOutputPriority($this->goods_id , $data);
	}

}
