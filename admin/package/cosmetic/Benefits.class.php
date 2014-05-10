<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBCosmeticBenefitsHelper;

class Benefits{
	private static $instance;
	private static $msAllBenefits = null;
	public static function getInstance(){
		is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public static function GetAllBenefits()
	{
		if(is_null(self::$msAllBenefits)){
			$allBenefits =  DBCosmeticBenefitsHelper::getConn()->where("is_del=0",array() )->fetchAll();
			foreach($allBenefits as $item)
			{
				self::$msAllBenefits[$item->des_info] = $item->id;
			}
		}
		return self::$msAllBenefits;
	}
	 
	public function getBenefitsCount($id)
	{
		if(is_numeric($id) && $id>0)
		{
			$len =  DBCosmeticBenefitsHelper::getConn()->where("pid=:pid AND　is_del=false",array('pid'=>$id) )->fetchCount();
			return $len;
		}
		return 0;	
	}

	public function upBenefits($newname,$pid,$id)
	{
		$newname = trim($newname);
		if($newname == '' || $newname==null)return false;
		$info = $this->getBenefitsDetail($id);
	    if($info->pid == 0) $pid = 0;	
		return DBCosmeticBenefitsHelper::getConn()->update(array("des_info"=>$newname,"pid"=>$pid),"id=:id",array('id'=>$id));
	}

	public function getBenefitsDetail($id)
	{
		return DBCosmeticBenefitsHelper::getConn()->where("id=:id",array('id'=>$id))->fetch();
	}	
	public function getSubBenefitsName($pid,$offset=0,$count=200)
	{
		$res = DBCosmeticBenefitsHelper::getConn()->where("is_del=false and pid=:pid",array('pid'=>$pid))->limit($offset,$count)->fetchAll();
		return $res;
	}	
  
	public function delBenefits($id,$pid)
	{
		return	DBCosmeticBenefitsHelper::getConn()->update(array('is_del'=>1),"id=:id",array("id"=>$id));
	}

	public function addBenefits($pid,$name)
	{
		$name = trim($name);
		if($name == '' || $name == null) return false;
		$newItem = array("des_info"=>$name,"pid"=>$pid);
		return DBCosmeticBenefitsHelper::getConn()->insert($newItem);
	}	
}
