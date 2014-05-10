<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Cosmetic;
use Gate\Package\Cosmetic\Goods AS GS;
use Gate\Package\Cosmetic\Benefits AS  Benefit;
use Gate\Package\Cosmetic\Brand;
use Gate\Package\Cosmetic\Funtionality AS Fun;

class Goods extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private $pageNum;
	private $length = 15;
	private $pro_id;
	private $pro_name;
	private $brand_id;
	private $classify;
	private $benefits;
	private $price;
	public function run() {
		if (!$this->_init()) {return FALSE;}
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
	private function _init() {
		$this->pageNum = $this->getRequest("pageNum",1);
		$this->view->pageNum = $this->pageNum;
		$this->getAllFun();
		$this->getAllBrands();
		$this->getAllBenefits();
		$this->setSearchCond();	
		$this->getAllGoods();
		$this->page();
		return $this->_check();
	}
	private function getAllBrands()
	{
		$this->view->allBrands = Brand::getInstance()->getAllBrandName();
	}
	private function setSearchCond()
	{
		$this->classify = $this->getRequest("pro_classify",1);
		$this->pro_name = $this->getRequest("pro_name");
		$this->brand_id = $this->getRequest("brand_id",1);
		if($this->brand_id == 0)$this->brand_id = $this->getRequest("eng_brand_id",1);
		$this->pro_id = $this->getRequest("pro_id",1);
		$this->view->pro_id	= $this->getRequest("pro_id",1);
		$price = $this->getRequest("price");
		$this->view->price_id = 0;
		if($price!="")
		{
			$value = explode(',',$price);
			$this->view->price = $price;
			if(count($value)>1)
			{
				if($value[0]==1001){
					$this->price = "price>=$value[0]";
					$this->view->price_id=5;
				}else{
					$this->price = "price>=$value[1] AND price <=$value[0]";
					if($value[0]==1000){
						$this->view->price_id=4;
					}else if($value[0]==500){
						$this->view->price_id = 3;
					}else if($value[0]==250){
						$this->view->price_id = 2;
					}else if($value[0]==100){
						$this->view->price_id = 1;
					}

				}
			}
		}
		$this->classify = $this->getRequest("classify",1);
		$this->benefits = $this->getRequest("benefits");
		$this->view->classify_id = $this->classify;
		$this->view->benefits_id = $this->benefits;
		$this->view->pro_name = $this->pro_name;
		$this->view->selected_brand_id = $this->brand_id;
	}
	private function getAllGoods()
	{
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;  
		$offset = ($this->pageNum-1) * $this->length;
		$goodsList = GS::getInstance()->getAllGoods($offset,20,(int)$this->pro_id,$this->pro_name,(int)$this->brand_id,(int)$this->classify,$this->benefits,$this->price);
		$this->view->goodsList = $goodsList;	
	}
	private function getRequest($param, $isInt=0){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===1 ? 0 : '');
	}

	private function _check(){
		return TRUE;
	}
	//得到所有分类

	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum==0?1:$this->pageNum;
		$page->totalNum = GS::getInstance()->getGoodsCount((int)$this->pro_id,$this->pro_name,(int)$this->brand_id,(int)$this->classify,$this->benefits,$this->price);
		$this->view->page = $page;
	}
}
