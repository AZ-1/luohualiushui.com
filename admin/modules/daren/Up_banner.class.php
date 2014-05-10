<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;
use Gate\Package\Ad\Ad;
class Up_banner extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private $id;
	private $title;
	private $pic;
	private $link_url;
	private $pic_url;
	private $flie_path;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			// 新增	
		if($this->id){
			if($_FILES){
				$up = $this->uploadImg($this->pic);
				if($up['error']==''){
					$this->pic_url = $up['url'];
					$isAdd = $this->upBanner();
					if($isAdd){
						$this->forward("/daren/banner");	
					}
				}
			}
			$isAdd = $this->upBanner();
			if($isAdd){
				$this->forward("/daren/banner");	
			}

		}
		// 显示
		// $this->view;

	}

	private function _init() {
		$this->id			= $this->getRequest('id',1);
		$this->title		= $this->getRequest('title',1);
		$this->link_url		= $this->getRequest('link_url',1);
		$this->pic_url		= $this->getRequest('pic_url',1);
		$this->pic			= 'pic_url';
//		$this->file_path		= $this->getRequest('file_path',1);
		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		return TRUE;
	}
	private function upBanner(){
		return Ad::getInstance()->upBanner($this->id,$this->title,$this->link_url,$this->pic_url,'');
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
