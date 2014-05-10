<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Special;
use Gate\Package\Article\Special;
class Is_online_special extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $topicId;
	private $online;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$isOnline = $this->onlineSpecial();
		if($isOnline){
			$this->forward('/special/index');
		}
	}

	private function _init() {
		$this->specialId	= $this->getRequest('special_id',1);
		$this->online		= $this->getRequest('online',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	/*
	 * 上线下线
	 */
	private function onlineSpecial(){
		$saveData = array(
			'is_online'=>$this->online
		);
		return Special::getInstance()->setSpecialOnline($this->specialId, $saveData);
	}

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '操作成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			echo json_encode($array);
			exit();
	}
}
