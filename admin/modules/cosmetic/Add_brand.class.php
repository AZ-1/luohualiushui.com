<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Brand;
use Gate\Libs\Base\Upload;
use Gate\Libs\Utilities;
class Add_brand extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private	$chi_name;
	private	$eng_name;
	private	$initator;
	private	$create_time;
	private $img_add;
	private	$story;
	private	$official_web;
	private	$birth_place;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->chi_name){
				$error = '';
				$isAdd = $this->addBrand();
				$rs = new \stdClass;                                                                                                                                                       
				if($isAdd){
					$rs->statusCode     = '200';
					$rs->message        = '保存成功';
					$rs->callbackType   = 'closeCurrent';
				}else{
					$rs->statusCode     = '300';
					$rs->message        = '保存失败，输入有误';
					$rs->callbackType   = 'forward';
				}
				$rs->navTabId       = '';
				$rs->rel            ='';
				$rs->forwardUrl     = '/cosmetic/index';
				echo json_encode($rs);
				die();     
			}
		$this->view->brandList = $this->getBrandList();
	}

	private function _init() {
		$this->character          =    strtoupper($this->getRequest('first_character'));
		$this->chi_name           =    $this->getRequest("chi_name");
		$this->eng_name           =    $this->getRequest("eng_name");
		$this->initator           =    $this->getRequest("initator");
		$this->create_time        =    $this->getRequest("create_time");
		$this->brand_classify     =    $this->getRequest("brand_classify");
		$this->story              =    $this->getRequest("story");
		$this->official_web       =    $this->getRequest("official_web");
		$this->birth_place        =    $this->getRequest("birth_place");
		$img_add = $this->getRequest('enter_img_add');
		$this->img_add = '';
		if(strlen($img_add)<5){
			$up = $this->uploadImg("select_img_add");
			if($up['error'] == ''){
				$this->img_add = $up['url'];
			}
		}else{
			$url = $this->uploadImg2Cloud($img_add);
			if($url) $this->img_add = $url;		
		}
		return $this->_check();
	}

	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}
	private function getBrandList(){
		return Brand::getInstance()->getBrandList();
	}
	private function addBrand(){
		return Brand::getInstance()->addBrand($this->character, $this->chi_name,$this->eng_name,$this->brand_classify,$this->initator,$this->img_add,$this->create_time,$this->story,$this->official_web,$this->birth_place);
	}
	private function uploadImg2Cloud($pic){
		$up = Upload:: cloudFile($pic);
		return $up;	
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
