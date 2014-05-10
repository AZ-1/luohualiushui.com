<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Goods AS GS;
use Gate\Libs\Base\Upload;
use Gate\Libs\Utilities;
use Gate\Package\Cosmetic\Benefits AS  Benefit;
use Gate\Package\Cosmetic\Funtionality AS Fun;
use Gate\Package\Cosmetic\Brand;

class Edit_goods extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
	//protected $view_switch = FALSE;
	private $pageNum;	
	private $brand_id;
	private $classify_id;
	private $label;
	private $pro_name;
	private $price;
	private $succession;
	private $img_add;
	private $is_up;
	private $mult_assess;
	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->is_up){
			$isU  = $this->upGoodsInfo();
			$this->dialog($isU);
		}
	}
	private function dialog($isU){
			$error = '';
			$mess = "失败";
			$url = "cosmetic/goods";
			$callback = "forward";
			$state = '300';
			if($isU){
					$mess="成功";
					$url = "/cosmetic/goods?pageNum=$this->pageNum";
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
	private function split($_data)
	{
		$data = explode(',',$_data);
		$value = '';
		foreach($data AS $_)
		{
			$value .= $_."\r\n";
		}
		return $value;
	}
	private function upGoodsInfo()
	{
		$id = $this->getRequest("pro_id",1);
		$newGoods = array();
		$newGoods['brand_id']=$this->brand_id;
		$newGoods['label']=$this->label;
		$newGoods['classify_id']=$this->classify_id;
		$newGoods['pro_name']=$this->pro_name;
		$newGoods['price']=$this->price;
		if($this->img_add!='')$newGoods['img_add']=$this->img_add;
		$newGoods['succession']=$this->succession;
		return	GS::getInstance()->upGoodsInfo($id,$newGoods,$this->mult_assess);
	}
	private function processInfo($_info)
	{
		preg_match_all("|{.*}|U",$_info,$info); 
		$i = 0;
		if(count($info[0])>0)foreach($info[0] AS $mess)
		{
			if($i++ > 3)break;
			$mess = preg_replace("|}|","",$mess);
			$mess = preg_replace("|{|","",$mess);
			if(strpos($mess,'实地') || strpos($mess,'外观') )
			{
				$this->view->pro_assess_content = $this->split($mess);
			}
			if(strpos($mess,'吸收性') || strpos($mess,'性价比') || strpos($mess,"延展性") )
			{
				$this->view->detail_assess_content =  $this->split($mess);
			}
			if(strpos($mess,'混合性') || strpos($mess,'外观')|| strpos($mess,"干性") )
			{
				$this->view->skin_assess_content =  $this->split($mess);
			}
			if(strpos($mess,'岁') )
			{
				$this->view->age_assess_content =$this->split($mess);
			}
		}
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
		$this->price = $this->getRequest("pro_price");
		$this->succession = $this->getRequest("pro_succession");
		$this->classify_id = $this->getRequest("classify_id");
		$assess_pro = $this->getRequest("pro_mult_assess");
		$assess_detail = $this->getRequest("detail_mult_assess");
		$assess_skin = $this->getRequest("skin_mult_assess");
		$assess_age = $this->getRequest("age_mult_assess");
		$this->mult_assess = "{{".$assess_pro."},{".$assess_detail."},{".$assess_skin."},{".$assess_age."}}";
	   // $this->mult_assess=preg_replace('/(\s*)/',',',$this->mult_assess);
	   // $this->mult_assess=preg_replace('/[nrt]/',',',$this->mult_assess);
	}
	private function getGoodsInfo()
	{
		$id = $this->getRequest("pro_id",1);
		$this->view->pro_id = $id;
		$info = GS::getInstance()->getGoodsInfo((int)$id);
		if($info)
		{
			$this->view->selected_classify_id =(int)$info->classify_id;
			$this->view->selected_brand_id =(int)$info->brand_id;
			$this->view->pro_name =$info->pro_name;
			$this->view->pro_succession =$info->succession;
			$this->view->pro_price =$info->price;
			$moreInfo = GS::getGoodsInvestigatedInfo($info->id);
			$this->processInfo($moreInfo->mult_assess);
			$this->view->pro_img_add = $info->img_add;
			$label = $info->label;
			$allLabel = explode(',',$label);
			array_filter($allLabel);
			$this->view->allLabel = $allLabel;
		}
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
	private function _init() {
		$this->pageNum = $this->getRequest("pageNum");
		$this->is_up = $this->getRequest("isUp");
		$this->view->pageNum = $this->pageNum;
		$this->view->pro_name = $this->pro_name;
		$this->getAllFun();
		$this->getAllBenefits();
		$this->getAllGoods();
		$this->getAllBrands();
		$this->getGoodsInfo();
		$this->collectInfo();
		$this->getImgInfo();
		return $this->_check();
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
	private function getAllGoods()
	{
		$goodsList = GS::getInstance()->getAllGoods();
		$this->view->goodsList = $goodsList;	
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

}
