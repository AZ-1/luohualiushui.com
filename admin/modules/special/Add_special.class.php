<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Special;
use Gate\Package\article\Special;
use Gate\Libs\Utilities;

class Add_special extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $title;
	private $description;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if(!empty($_POST['title'])){
			$data['title'] = empty($_POST['title']) ? 'shit' : $_POST['title'];
			$data['description'] = empty($_POST['description']) ? 'shit' : $_POST['description'];

			$status = $this->addSpecial($data);

			if($status){
				$this->message('/special/index', 'success');
			}else{
				$this->message('/special/index', 'error');
			}
		}
		// 显示
		// $this->view;
	}

	private function _init() {
		$description = empty($_POST['description']) ? '' : $_POST['description'];
		$this->title		= $this->getRequest('title', 0);
		$this->description	= trim($description);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function addSpecial($data){
		$data['create_time'] = date('Y-m-d H:i:s', time());
		return Special::getInstance()->addSpecial($data);
	}
    
	private function addTopic($pic){
		$data = array("title"=>$this->title,"pic"=>$pic,"description"=>$this->description);
		return Maidan::getInstance()->addTopic($data);
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
