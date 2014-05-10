<?php
/*
 * 广告管理
 * @author wanghaihong
 */
namespace Gate\Package\Brands;
use Gate\Package\Helper\DBBrandsHelper;

class Brands{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getBrandsList(){
		$list = DBBrandsHelper::getConn()->field('brands_id,name,logo,link_url')->fetchAll();
		return $list;
	}
	public function addBrands($data){
		$list = DBBrandsHelper::getConn()->insert($data);
		return $list;
	}
	public function delBrands($id){
		$isDel = DBBrandsHelper :: getConn() -> delete('brands_id=:id' , array('id' => $id));
		return $isDel;
	}

	public function getBrands($id){
		return DBBrandsHelper::getConn()->where('brands_id=:id' , array('id' => $id))->fetch();
	}


	public function upBrands($id,$name,$logo,$link_url){
		if($logo == ''){
			return DBBrandsHelper::getConn()->update(array("name"=>$name, 'link_url'=>$link_url),'brands_id=:id',array("id"=>$id));
		}
		return DBBrandsHelper::getConn()->update(array("name"=>$name,"logo"=>$logo , 'link_url'=>$link_url),'brands_id=:id',array("id"=>$id));
	}

}
