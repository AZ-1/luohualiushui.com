<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Brand;
use Gate\Libs\Base\Upload;
use Gate\Libs\Utilities;
class Edit_brand extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $id;
	private $official_web;
	private	$chi_name;
	private $eng_name;
	private $img_add;
	private $initator;
	private $create_time;
	private $birth_place;
	private $brand_classify;
	private $add_time;
	private $story;
	private $isUp =0;
	public function run() {

		if (!$this->_init()) {return FALSE;}
			if($this->id && $this->isUp == 1){
			$isUp = $this->upBrand();
			if($isUp){
				$this->dialog('/cosmetic/index');
			}else{
				$error = '修改失败';
				$this->dialog('/cosmetic/index', $error, true);
			}
		}
	}
	private function _init() {
		$this->id = $this->getRequest('id', 1);
		$this->isUp = $this->getRequest('isUp',1);
		$this->chi_name = $this->getRequest('chi_name');
		$this->eng_name = $this->getRequest('eng_name');
		$this->initator = $this->getRequest('initator');
		$this->create_time = $this->getRequest('create_time');
		$this->birth_place = $this->getRequest('birth_place');
		$this->add_time = $this->getRequest('add_time');
		$this->story = $this->getRequest('story');
		$this->official_web = $this->getRequest("official_web");
		$this->brand_classify = $this->getRequest("brand_classify");
		$this->character =  strtoupper($this->getRequest('first_character'));
		$this->view->brand = $this->getBrand();
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

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	private function getBrand(){
		return Brand::getInstance()->getBrand($this->id);
	}
	private function upBrand()
	{
		$newBrand = array();
		$newBrand['first_character'] = $this->character;
		$newBrand['create_time'] = $this->create_time;	
		$newBrand['story'] = $this->story;
		$newBrand['birth_place'] = $this->birth_place;
		$newBrand['initator'] = $this->initator;
		$newBrand['chi_name'] = $this->chi_name;
		$newBrand['eng_name'] = $this->eng_name;
		$newBrand['official_web'] = $this->official_web;
		$newBrand['brand_classify']=$this->brand_classify;	
		if($this->img_add !='' )$newBrand["img_add"]=$this->img_add;	
		return Brand::getInstance()->upBrand($newBrand,$this->id);
	}
	private function dialog($navTabId){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '成功',
						'navTabId'		=> $navTabId,
						'rel'			=> '',
						'callbackType'	=> 'closeCurrent',
						'forwardUrl'	=> '');
			echo json_encode($array);
			die();
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
