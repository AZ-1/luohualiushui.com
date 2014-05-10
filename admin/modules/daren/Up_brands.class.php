<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Libs\Utilities;
use Gate\Package\Brands\Brands;
use Gate\Libs\Base\Upload;
class Up_brands extends \Gate\Libs\Controller{
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
	protected $view_switch = false;
	private $id;
	private $name;
	private $pic;
	private $logo;
	private $link_url;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
		if($_FILES){
			$up = $this->uploadImg($this->pic);
			if($up['error']==''){
				$this->logo = $up['url'];
				$isAdd = $this->upBrands();
				if($isAdd){
					$this->forward('/daren/brands');
				}
			}
		}
		$this->logo = '';
		$isAdd = $this->upBrands();
		if($isAdd){
			$this->forward('/daren/brands');
		}
	}

	private function _init() {
		$this->id			= $this->getRequest('id',0);
		$this->name			= $this->getRequest('name',0);
		$this->logo			= $this->getRequest('logo',0);
		$this->link_url		= $this->getRequest('link_url',0);
		$this->pic = 'logo';
		return $this->_check();
	}
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function _check(){
		return TRUE;
	}
	private function upBrands(){
		return Brands::getInstance()->upBrands($this->id,$this->name,$this->logo,$this->link_url);
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
