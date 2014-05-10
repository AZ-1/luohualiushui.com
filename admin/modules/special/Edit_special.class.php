<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Special;
use Gate\Package\Article\Special;
use Gate\Libs\Utilities;

class edit_special extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = TRUE;
	private $title;
	private $description;
	private $id;
	private $is_edit;
	public function run() {
		if (!$this->_init()) {return FALSE;}

		if($this->is_edit == 1){
			$status = $this->updateSpecial();
			if($status > 0){
				$this->message('/special/edit_special?id='.$this->special_id, 'success');		
			}else{
				$this->message('/special/index', 'error');
			}
		}
		$this->view->specialInfo = $this->getSpecialFind();
	}

	private function _init() {
		$description = empty($_POST['description']) ? '' : $_POST['description'];
		$this->special_id		= $this->getRequest('id',1);
		$this->title			= $this->getRequest('title',0);
		$this->description		= $description;
		$this->is_edit          = $this->getRequest('is_edit', 1);
		return $this->_check();
	}
	
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	
	private function _check(){
		return TRUE;
	}
	
	private function getSpecialFind(){
		return Special::getInstance()->getSpecialFind($this->special_id);
	}
	
	private function updateSpecial(){
		$data = array(
			'title' => $this->title,
			'description' => $this->description
		);
		return Special::getInstance()->updateSpecial($data, $this->special_id);
	}

	private function message($forwardUrl, $type){
			$success = array(
				'code'=>'200',
				'message'=>'成功'
			);

			$error = array(
				'code'=>'300',
				'message'=>'失败'
			);

			$info = ($type == 'success') ? $success : $error;


			$array = array(
						'statusCode'	=> $info['code'],
						'message'		=> $info['message'],
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
}
