<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Index;
use Gate\Package\Article\Article;
use Gate\Package\User\Userinfo;
use Gate\Package\Article\Topic;
use Gate\Package\Ad\Ad;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;
class Edit_banner_m extends \Gate\Libs\Controller{
    protected $view_switch = TRUE;
    private $editId;
    private $title;
	private $pic_url;
	private $link_url;
	private $file_path;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 编辑
		if($this->title != ''){
			$up = $this->uploadImg($this->pic);
			if($up['error']==''){
				$this->pic_url = $up['url'];
				$isUp = $this->edit();
				if($isUp){
					$this->forward('/index/banner_m');
				} 
			}
		}
		// 显示
	    $this->view->banner=$this->getBanner();
	}

	private function _init() {
		  $this->pic = 'pic_url';
		  $this->editId			= $this->getRequest('id',1);
		  $this->title			= $this->getRequest('title',1);
		  $this->link_url		= $this->getRequest('link_url',0);
		  $this->file_path		= $this->getRequest('file_path',0); 
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    

	private function getBanner(){
		return Ad::getInstance()->getBanner($this->editId);
	}
   
	private function edit(){
		return Ad::getInstance()->editBanner($this->editId,$this->title, $this->link_url, $this->pic_url,'');
	} 

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '编辑成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}
	private function uploadImg($pic){
		$dir		= TOPIC_PATH;
		$saveName	= Utilities::getUniqueId();
		$up = Upload::uploadImage($pic, $dir, $saveName);

		if($up['error']==''){
			$newUrl = Upload::ossServer($up['path'], 'mei/ad/');
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
