<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Daren;
use Gate\Package\Brands\Brands as BrandsP;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;
class Add_brands extends \Gate\Libs\Controller{
    //protected $view_switch = FALSE;
	private $id;
	private $name;
	private $pic;
	private $logo;
	private $link_url;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		// 新增
			if($this->name){
				$up = $this->uploadImg($this->pic);
				if($up['error'] == ''){
					$this->logo = $up['url'];
					$isAdd = $this->addBrands();
				}
				if($isAdd){
				$this->forward('/daren/brands');
			}
		}
		// 显示
		// $this->view;

	}

	private function _init() {
		$this->name		= $this->getRequest('name',0);
		$this->pic		= 'logo'; 
		$this->link_url = $this->getRequest('link_url',0); 
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
    
	private function addBrands(){
		$data = array("name"=>$this->name,"logo"=>$this->logo,'link_url'=>$this->link_url);
		return BrandsP::getInstance()->addBrands($data);
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
