<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Topic;
use Gate\Package\Maidan\Maidan;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;
class Add_topic extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $title;
	private $pic;
	private $description;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($this->title){

			$up = $this->uploadImg();
			if($up['error']==''){
				$isAdd = $this->addTopic($up['url']);
				if($isAdd){
					$this->forward('/topic/index');
				}
			}
		}
		// 显示
		// $this->view;

	}

	private function _init() {
		$this->title		= $this->getRequest('title',1);
		$this->pic	= 'pic'; // 文件域
		$this->description	= $this->getRequest('description',1);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function addTopic($pic){
		$data = array("title"=>$this->title,"pic"=>$pic,"description"=>$this->description);
		return Maidan::getInstance()->addTopic($data);
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

	private function uploadImg(){
		$dir		= TOPIC_PATH;
		$saveName	= Utilities::getUniqueId();
		$up = Upload:: uploadImage($this->pic, $dir, $saveName);
		if($up['error']==''){
			$newUrl = Upload::ossServer($up['path'], 'mei/topic/');
			if($newUrl){
				$up['url'] = $newUrl;
				// 删除本地图片
				unlink($up['path']);
			}else{
				$up['error'] = '云存储失败，请稍后重试';
				//$up['url'] = str_replace(TOPIC_PATH, TOPIC_URL, $up['path']);
			}
		}
		return $up;
	}
}
