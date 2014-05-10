<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\Ad\Ad;
class Is_online_banner_m extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $id;
	private $online;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->online){
			$isOnline = $this->onlineBanner();
		}else{
			$isOnline = $this->offlineBanner();
		}
		if($isOnline){
			$this->forward('/daren/banner_m');
		}
	}

	private function _init() {
		$this->id			= $this->getRequest('id',1);
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
	private function onlineBanner(){
		return Ad::getInstance()->onlineBanner($this->id);
	}
	private function offlineBanner(){
		return Ad::getInstance()->offlineBanner($this->id);
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
