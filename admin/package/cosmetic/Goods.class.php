<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBCosmeticGoodsHelper;
use Gate\Package\Helper\DBBrandHelper;
use Gate\Package\Helper\DBCosmeticGoodsDetailInfoHelper;
use Gate\Package\Helper\DBFuntionalityHelper;
use Gate\Package\Cosmetic\Benefits;
class Goods{
	private static $instance;
	public static function getInstance(){
		is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
	}
	private function convert($proList)
	{
		if(empty($proList)) return array();
		$goodsList = array();
		$index = 0;
		foreach($proList AS $item)
		{
			$brand = DBBrandHelper::getConn()->where("id=:id",array("id"=>$item->brand_id))->fetch();
			if(empty($brand))
			{
				$brand->chi_name = '';
			}
			$goods = new \stdclass;
			$goods->pro_id = $item->id;
			$goods->pro_name = $item->pro_name;
			$goods->brand_name = $brand->chi_name;
			$goods->price = $item->price;
			$goods->specify = $item->specify;
			$goods->succession= $item->succession;
			$goods->create_time= $item->create_time;
			$label =  $item->label;
			$goods->funtionality = $this->labelConvert2Char($label);
			$classify = $item->classify_id;
			$goods->classify = $this->classifyConvert2Char($classify);
			$goodsList[$index++] = $goods;
		}
		return $goodsList;
	}
	private function labelConvert2Char($label)
	{
		$allBenefits = Benefits::GetAllBenefits();
		$ids = explode(',',$label);
		array_filter($ids);
		$info = '';
		foreach($ids AS $id)
		{
			foreach($allBenefits AS $k=>$v)
			{
				if($v == $id){
					$info.=$k."&nbsp;&nbsp;";
					break;
				}
			}
		}
		return $info;
	}
	private function classifyConvert2Char($classify)
	{
		$sql = "select a.classify_name AS Second, b.classify_name AS First from cosmetic_funtionality a join cosmetic_funtionality b on a.id=$classify AND a.pid=b.id AND a.is_del=0;";
		error_reporting(E_ALL^E_NOTICE);
		$classify_name = DBFuntionalityHelper::getConn()->fetch($sql);
		if(empty($classify_name))return '未知分类';
		return $classify_name->First."&nbsp;/&nbsp;".$classify_name->Second;
		
	}
	public function getGoodsInfo($id)
	{
		$pro = DBCosmeticGoodsHelper::getConn()->where("id=:id",array("id"=>$id))->fetch();
		return $pro;
	}
	public function upGoodsInfo($id,$new_info,$mult_ass)
	{
		DBCosmeticGoodsHelper::getConn()->update($new_info,'id=:id',array("id"=>$id)); 
		DBCosmeticGoodsDetailInfoHelper::getConn()->update(array("mult_assess"=>$mult_ass),'id=:id',array("id"=>$id));
		return true;	
	}
	public function delGoods($id)
	{
		return  DBCosmeticGoodsHelper::getConn()->update(array('is_del'=>1),'id=:id',array("id"=>$id));
	}
	public function getGoodsCount($pro_id=0,$pro_name = '', $brand_id=0,$classify=0,$label='',$price='' )
	{
		$where  = "is_del<>1";
		$params = array();
		if($pro_id>0)
		{
			$where .= " AND id=:pro_id";
			$params['pro_id']=$pro_id;
		}else{
			if($pro_name!='')
			{
				$where .=" AND pro_name regexp :pro_name ";
				$params['pro_name'] = $pro_name;
			}
			if($price!='')
			{
				$where .=" AND $price ";
			}
			if($classify!=0){
				$where .=" AND classify_id=:classify";
				$params['classify']=$classify;
			}
			if($label!=''){
				$where .=" AND label regexp :label";
				$params["label"]="$label";
			}
	 		if($brand_id!=0){
				$where .=" AND brand_id=:brand_id";
				$params['brand_id']=$brand_id;
			}
		}
		return  DBCosmeticGoodsHelper::getConn()->where($where,$params)->limit(0,1)->fetchCount();
	}
	public function getGoodsInvestigatedInfo($id)
	{
		$pro = DBCosmeticGoodsDetailInfoHelper::getConn()->where("id=:id",array("id"=>$id))->fetch();
		return $pro;
	}
	public function getAllGoods($offset=0,$limit_count=15,$pro_id=0,$pro_name = '', $brand_id=0,$classify=0,$label='',$price='' )
	{
		$where  = "is_del<>1";
		$params = array();
		if($pro_id>0)
		{
			$where .= " AND id=:pro_id";
			$params['pro_id']=$pro_id;
		}else{
			if($pro_name!='')
			{
				$where .=" AND pro_name regexp :pro_name ";
				$params['pro_name'] = $pro_name;
			}
			if($price!='')
			{
				$where .=" AND $price ";
			}
			if($classify!=0){
				$where .=" AND classify_id=:classify";
				$params['classify']=$classify;
			}
			if($label!=''){
				$where .=" AND label regexp :label";
				$params["label"]="$label";
			}
	 		if($brand_id!=0){
				$where .=" AND brand_id=:brand_id";
				$params['brand_id']=$brand_id;
			}
		}
 		$goodsList = DBCosmeticGoodsHelper::getConn()->where($where,$params)->order("create_time desc")->limit($offset,$limit_count)->fetchAll();
		return $this->convert($goodsList);	
	}
	public function saveGoodsInfo($goods,$assess)
	{
		$id = DBCosmeticGoodsHelper::getConn()->insert($goods);
		$new_info =array(
				"id"=>$id,
				"mult_assess"=>$assess
		);
		$res= DBCosmeticGoodsDetailInfoHelper::getConn()->insert($new_info);
		return $res;
	}
}
