<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Ad\Ad;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;
class Add_banner extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $bannerType;
	private $title;
	private $link_url;
	private $pic_url;
//	private $file_path;
	private $description;
	private $pic;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($this->title){
			$error = '';
			$up = $this->uploadImg($this->pic);
			if($up['error']==''){
				$isAdd = $this->addBanner($up['url']);
				if($isAdd){
					$this->forward('/index/banner');
				}else{
					$error = '数据保存失败';
				}
			}else{
				$error = $up['error'];
			}
			$this->forward('/index/banner', $error);
		}
		// 显示
		// $this->view;
	}

	private function _init() {
		$this->title		= $this->getRequest('title',0);
		$this->pic			= 'pic_url';
		$this->link_url		= $this->getRequest('link_url',0);
		$this->pic_url		= $this->getRequest('pic_url',0);
//		$this->file_path	= $this->getRequest('file_path',1);
		$this->bannerType = Ad::getInstance()->bannerType()->web['guang'];

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function addBanner($pic_url){
		$data = array("title"=>$this->title,"link_url"=>$this->link_url,"pic_url"=>$pic_url,"type"=>$this->bannerType);
		return Ad::getInstance()->addBanner($data);
	}

	private function forward($forwardUrl, $error=''){
		$statusCode = $error=='' ? 200 : 300;
		$message = $error=='' ? '添加成功' : $error;
			$array = array(
						'statusCode'	=> $statusCode,
						'message'		=> $message,
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}

	private function uploadImg($pic){
		$dir		= TOPIC_PATH;
		$saveName	= Utilities::getUniqueId();
		$imageInfo = Upload::cloudFormImg($pic, $dir, $saveName, 'mei/ad/');
		return $imageInfo;
	}
}
