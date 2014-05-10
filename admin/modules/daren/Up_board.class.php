<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Libs\base\Upload;
use Gate\Libs\Utilities;
#use Gate\Package\

class Up_board extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = false;
	private		$file;

	public function run() {
		if (!$this->_init()) {return FALSE;}
			$up = $this->uploadImg();
		if($up['error']==''){
			$url = AD_BANNER_PATH . '/' . $up['name'];
			$this->forward("/daren/board");
		}else{
		}

	}

	private function _init() {
		$this->file = "board";
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function uploadImg(){
		$dir		= AD_BANNER_PATH;
		$saveName	= 'daren_board';
		$up = Upload:: uploadImage($this->file, $dir, $saveName);
		return $up;
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
