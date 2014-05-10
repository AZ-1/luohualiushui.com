<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Ad\Ad;
class Del_banner_m extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $id;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		$isDel = $this->DelBanner();
		if($isDel){
			$this->forward('/index/banner_m');
		}
	}

	private function _init() {
		$this->id		= $this->getRequest('id',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	/*
	 *
	 */
	private function DelBanner(){
		return Ad::getInstance()->DelBanner($this->id);
	}

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '删除成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			echo json_encode($array);
			exit();
	}
}
