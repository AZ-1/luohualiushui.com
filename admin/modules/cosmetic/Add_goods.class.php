<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Goods AS GS;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;
use Gate\Package\Cosmetic\Benefits AS  Benefit;
use Gate\Package\Cosmetic\Funtionality AS Fun;
use Gate\Package\Cosmetic\Brand;

class Add_goods extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
	//protected $view_switch = FALSE;
	private $brand_id;
	private $classify_id;
	private $label;
	private $pro_name;
	private $price;
	private $succession;
	private $img_add;
    private $mult_assess;
	private $is_up;
	public function run() {
		if (!$this->_init()) {return FALSE;}
			if($this->is_up){
			$isAdd = $this->saveGoodsInfo();
			$this->dialog(true);
		}
	}
	private function dialog($isAdd){
			$error = '';
			$mess = "失败";
			$url = "cosmetic/Add_goods";
			$callback = "forward";
			$state = '300';
			if($isAdd){
					$mess="成功";
					$url = "/cosmetic/goods";
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
	private function getAllBrands()
	{
		$this->view->allBrands = Brand::getInstance()->getAllBrandName();
	}
	private function saveGoodsInfo()
	{
		$newGoods = array();
		$newGoods['brand_id']=$this->brand_id;
		$newGoods['label']=$this->label;
		$newGoods['classify_id']=$this->classify_id;
		$newGoods['pro_name']=$this->pro_name;
		$newGoods['price']=$this->price;
		$newGoods['succession']=$this->succession;
		$newGoods['img_add']=$this->img_add;
		GS::getInstance()->saveGoodsInfo($newGoods,$this->mult_assess);
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
	private function collectInfo()
	{
		$this->brand_id = $this->getRequest("brand_id",1);
		$label = $this->getRequest("label");
		if($label!='')	foreach($label AS $k=>$v)
		{
			$this->label.=$v.",";
		}
		$this->pro_name = $this->getRequest("pro_name");
		$this->price = $this->getRequest("price");
		$this->succession = $this->getRequest("succession");
		$this->classify_id = $this->getRequest("classify_id");
		$assess_pro = $this->getRequest("pro_mult_assess");
		$assess_detail = $this->getRequest("detail_mult_assess");
		$assess_skin = $this->getRequest("skin_mult_assess");
		$assess_age = $this->getRequest("age_mult_assess");
		$this->mult_assess = "{{".$assess_pro."},{".$assess_detail."},{".$assess_skin."},{".$assess_age."}}";
	  // $this->mult_assess=preg_replace('/(\s*)/',',',$this->mult_assess);
	 //  $this->mult_assess=preg_replace('/[nrt]/',',',$this->mult_assess);
	}
	private function _init() {
		$this->getAllFun();
		$this->getAllBenefits();
		$this->getImgInfo();
		$this->getAllBrands();
		$this->collectInfo();
		$this->is_up = $this->getRequest("isUp");
		return $this->_check();
	}
	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}
	//得到所有分类

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

	private function getImgInfo()
	{
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
	}
}
