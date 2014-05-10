<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Goods AS GS;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;
use Gate\Package\Cosmetic\Banner AS BA;
use Gate\Package\Cosmetic\Benefits AS  Benefit;
use Gate\Package\Cosmetic\Brand;
use Gate\Package\Cosmetic\Funtionality AS Fun;
use Gate\Package\Cosmetic\Ranking as Rank;
class Edit_banner extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private $pageNum;
	private $length = 15;
	private $is_up;
	private $id;
	private $img_add;
	public function run() {
		if (!$this->_init())
		{
			return FALSE;
		}
		if($this->is_up == 1){
			$res = $this->collectAndSaveInfo();	
			$this->dialog($res);
		}	
	}

	private function dialog($isAdd){
			$error = '';
			$mess = "失败";
			$url = "cosmetic/Banner";
			$callback = "forward";
			$state = '300';
			if($isAdd){
					$mess="成功";
					$url = "/cosmetic/Banner";
					$callback = "closeCurrent";
					$state = '200';
				}
				$rs = new \stdClass;                                                                                                                                                       
				$rs->statusCode     = $state;
				$rs->message        = $mess;
				$rs->navTabId       = '';
				$rs->rel            ='';
				$rs->callbackType   = $callback;
				$rs->forwardUrl     = $url;
				echo json_encode($rs);
				die();     
	}
	private function _init() {
		$this->id = $this->getRequest("edit_id",1);
		$this->is_up = $this->getRequest("is_up");
		$this->getAllBenefits();
		$this->getAllFun();
		$this->getAllRank();
		$this->getAllBrands();
		$infos = BA::getInstance()->getPic($this->id);
		$this->view->info = $infos->info;
		$this->view->img = $infos->img;
		$this->view->location = $infos->pid;
		$this->view->banner_id = $this->id;
		$this->view->order = $infos->order;
		$data = $this->getRequest("value");
		$this->page();
		return $this->_check();
	}

	private function getAllBrands()
	{
		$this->view->allBrand = Brand::getInstance()->getAllBrandName();
	}
	private function collectAndSaveInfo()
	{
		$type = $this->getRequest("type");
		$order = $this->getRequest("order",1);
		$location = $this->getRequest("location",1);
		$newPic = array();
		$newPic['pid'] = (int)$location;
		$info = array();
		if($order != 0)$newPic['displayOrder']=$order;
		$info["type"] = $type;
		switch($type)
		{
			case "SearchActivity":
			{
				$info["value"] = $this->getAllBenefits($type);
				$info["params"] = "null";
				$break;
			}
			case "URL":
			{
				$info["value"] = "null";
				$info["params"] = "null";
				$break;
			}
			case "BrandActivity":
			{
				$info["value"] = "null";
				$info["params"] = "null";
				$break;
			}
			case "EffectActivity":
			{
				$info["value"] = "null";
				$info["params"] = "null";
				$break;
			}
			case "CategoryActivity":
			{
				$info["value"] = "null";
				$info["params"] = "null";
				$break;
			}
			default :
			{
				$_ = $this->getRequest($type);
				$data = explode(',',$_);
				$info["value"] = $data[0];
				$info["params"] = $data[1];
				$break;
			}
		}
		$newPic['forwardAddress'] = json_encode($info);
		$id = $this->id;
		$this->getImgInfo();
		$newPic['img_src'] = $this->img_add;
		return 	BA::getInstance()->savePic($newPic,$id);
	}
	private function getAllBenefits()
	{
		$allClassify = array();
		$allFirstClassify = Benefit::getInstance()->getSubBenefitsName(0);
		$i=0;
		foreach($allFirstClassify as $item)
		{
			$id = $item->id;
			$secClassify = Benefit::getInstance()->getSubBenefitsName($id);
			$obj = new \stdClass;
			$obj->base = $item;
			$obj->child = $secClassify;
			$allClassify[$i++] = $obj;
		}
		$this->view->allBenefits = $allClassify;
	}
	private function getAllFun()
	{
		$allClassify = array();
		$allFirstClassify = Fun::getInstance()->getSubFunName(0);
		$i=0;
		foreach($allFirstClassify as $item)
		{
			$id = $item->id;
			$secClassify = Fun::getInstance()->getSubFunName($id);
			$obj = new \stdClass;
			$obj->base = $item;
			$obj->child = $secClassify;
			$allClassify[$i++] = $obj;
		}	
		$this->view->allClassify = $allClassify;
	}
	private function getAllRank()
	{
		$allClassify = array();
		$allFirstClassify = Rank::getInstance()->getSubRank(0);
		$i=0;
		foreach($allFirstClassify as $item)
		{
			$id = $item->id;
			$secClassify = Rank::getInstance()->getSubRank($id);
			$obj = new \stdClass;
			$obj->base = $item;
			$obj->child = $secClassify;
			$allClassify[$i++] = $obj;
		}	
		$this->view->allRanking = $allClassify;
	}
	private function _check() {
		return True;
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum==0?1:$this->pageNum;
		$page->totalNum = 0;
		$this->view->page = $page;
	}
	private function getImgInfo()
	{
		$img_add = $this->getRequest('enter_img_add');
		if(strlen($img_add)<5){
			$up = $this->uploadImg("select_img_add");
			if($up['error'] == ''){
				$this->img_add = $up['url'];
			}
		}else{
			$url = $this->uploadImg2Cloud($img_add);
			if($url) $this->img_add = $url;		
		}
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
