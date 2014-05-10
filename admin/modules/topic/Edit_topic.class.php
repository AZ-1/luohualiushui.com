<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Topic;
use Gate\Package\Maidan\Maidan;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;

class edit_topic extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = TRUE;
	private $title;
	private $pic;
	private $description;
	private $id;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->title){
			$newPic = '';
			if(isset($_FILES[$this->pic]) && !empty($_FILES[$this->pic])){
				$up = $this->uploadImg();
				if($up['error']==''){
					$newPic = $up['url'];
				}
			}
			$this->upTopic($newPic);
			$this->forward("/topic/index");
		}
		// 显示
		$this->view->topicInfo = $this->getTopic();

	}

	private function _init() {
		$this->id				= $this->getRequest('id',1);
		$this->title			= $this->getRequest('title',1);
		$this->description		= $this->getRequest('description',1);
		$this->pic				= 'pic'; // 文件域

		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		return TRUE;
	}
	private function getTopic(){
		return Maidan::getInstance()->getTopic($this->id);
	}
	private function upTopic($pic){
		return Maidan::getInstance()->updateTopic($this->id,$this->title,$pic,$this->description);
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
