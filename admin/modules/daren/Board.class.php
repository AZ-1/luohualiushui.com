<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Libs\base\Upload;
use Gate\Libs\Utilities;
#use Gate\Package\

class Board extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private		$file;
	private		$isUp;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->isUp){
			$up = $this->uploadImg();
			if($up){
				$this->forward('/daren/board');
			}
		}
		$this->view->boardUrl = AD_BANNER_URL . '/daren_board.jpg?t='.time();
	}


	private function _init() {
		$this->isUp = $this->getRequest('isUp',1);
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
		$saveName	= 'daren_board.jpg';
		$up = Upload:: uploadImage($this->file, $dir, $saveName);
		return $up;
	}

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '成功',
						'navTabId'		=> 'daren_board',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
}
