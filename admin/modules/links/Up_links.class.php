<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Links;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;
use Gate\Package\Links\Links;

class Up_links extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private $Lid;
	private $Lname;
	private $Lurl;

	public function run() {
		if (!$this->_init()) {return FALSE;}
			// 新增	
			$data = array(
				'name'=> $this->Lname,
				'url' => $this->Lurl,
			);
			$isAdd = $this->upLinks($data,$this->Lid);
			if($isAdd){
				$this->forward("/links/Listlinks");	
			}

		// 显示
		// $this->view;

	}

	private function _init() {
		$this->Lid			= $this->getRequest('Lid',1);
		$this->Lname		= $this->getRequest('Lname',0);
		$this->Lurl		= $this->getRequest('Lurl',0);
		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		return TRUE;
	}
	private function upLinks($data,$Lid){
		return Links::getInstance()->upLinks($data,$Lid);
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
}
