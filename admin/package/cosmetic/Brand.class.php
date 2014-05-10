<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBBrandHelper;
class Brand{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	 *添加品牌
	 * @iuthor	 wangxu	
	 * @time		2014/2/18
	 * @params		$detail array
	 */
	public function getBrandCount($name='',$id=0)
	{
		if($id!=null){
			if(!is_numeric($id)) return 0;
			if($id >0)return DBBrandHelper::getConn()->where("id=:id and is_del<>1 ",array("id"=>$id))->fetchCount();
		}
		if($name != '' &&  $name != null)
		{
			return DBBrandHelper::getConn()->where("is_del<>1 and chi_name LIKE :chi_name ",array('chi_name'=>'%'.$name.'%') )->fetchCount();
		}
		return DBBrandHelper::getConn()->fetchCount();	
	}
	public function upBrand($newBrand,$id)
	{
		 return DBBrandHelper::getConn()->update($newBrand,"id=:id",array('id'=>$id));
	}

	public function getBrand($id)
	{
		return DBBrandHelper::getConn()->where("id=:id and is_del<>1 ",array("id"=>$id))->fetch();
	}
	public function getAllBrandName()
	{
		return DBBrandHelper::getConn()->where(" is_del<>1 ",array())->field("id,chi_name,eng_name")->order("first_character")->fetchAll();	
	}
	public function getBrandList($limit_offset=0, $limit_count=20, $name='',$id=0 )
	{
		if($id!=null){
			if(!is_numeric($id)) return array();
			if($id>1)
			{
				return DBBrandHelper::getConn()->where("is_del<>1 and  id=:id ",array('id'=>$id) )->limit(0,1)->fetchAll();
			}
		}
		if($name !='' && $name!=null )
		{
			return DBBrandHelper::getConn()->where("is_del<>1 and chi_name LIKE :chi_name ",array('chi_name'=>"%$name%") )->order('add_time desc')->limit($limit_offset,$limit_count)->fetchAll();
		}
		return DBBrandHelper::getConn()->where(" is_del<>1 ",array())->order("add_time desc")->limit($limit_offset,$limit_count)->fetchAll();	
	}
	public function addBrand($character, $chi_name,$eng_name,$brand_classify,$initator,$img_add,$create_time,$story,$official_web,$birth_place)
	{
		trim($chi_name);
		if($chi_name == '')return FALSE;
		$newBrand = array(
			'first_character' => $character,
			'chi_name' => $chi_name,
			'eng_name' => $eng_name,
			'initator' => $initator,
			'create_time' => $create_time,
			'story' => $story,
			'official_web' => $official_web,
			'birth_place' => $birth_place,
			"brand_classify"=>$brand_classify,
			'img_add' => $img_add
		);
		return DBBrandHelper::getConn()->insert($newBrand);
			
	}	
	public function delBrand($id)
	{
        return DBBrandHelper::getConn()->update(array('is_del'=>1),"id=:id",array("id"=>$id));
	}
}
