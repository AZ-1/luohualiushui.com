<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\Ad\Ad;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;

class Add_banner extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $bannerType;
	private $title;
	private $link_url;
	private $pic;
	private $pic_url;
	private $file_path;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($this->title){
			$up = $this->uploadImg($this->pic);
			if($up['error']==''){
				$isAdd = $this->addBanner($up['url']);
				if($isAdd){
					$this->forward('/daren/banner');
				}
			}
		}
		// 显示
		// $this->view;

	}

	private function _init() {
		$this->title		= $this->getRequest('title',1);
		$this->link_url	= $this->getRequest('link_url',1);
		$this->pic	=	'pic_url';
		$this->pic_url	= $this->getRequest('pic_url',1);
		$this->file_path	= "";//$this->getRequest('file_path',1);
		$this->bannerType = Ad::getInstance()->bannerType()->web['daren'];

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function addBanner($pic_url){
		$data = array("title"=>$this->title,"link_url"=>$this->link_url,"pic_url"=>$pic_url,"file_path"=>$this->file_path,"type"=>$this->bannerType);
		return Ad::getInstance()->addBanner($data);
	}

	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '添加成功',
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
		$up['url'] = str_replace(TOPIC_PATH, TOPIC_URL, $up['path']);
		return $up;
	}

}
