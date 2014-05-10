<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBFuntionalityHelper;

class Funtionality{
	private static $instance;
	public static function getInstance(){
		is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getFunCount($id)
	{
		if(is_numeric($id) && $id>0)
		{
			$len =  DBFuntionalityHelper::getConn()->where("pid=:pid AND　is_del=false",array('pid'=>$id) )->fetchCount();
			return $len;
		}
		return 0;	
	}

	public function upFun($newname,$pid,$id)
	{
		$newname = trim($newname);
		if($newname == '' || $newname==null)return false;
		return DBFuntionalityHelper::getConn()->update(array("classify_name"=>$newname,"pid"=>$pid),"id=:id",array('id'=>$id));
	}

	public function getFunDetail($id)
	{
		return DBFuntionalityHelper::getConn()->where("id=:id",array('id'=>$id))->fetch();
	}	
	public function getSubFunName($pid,$offset=0,$count=200)
	{
		$res = DBFuntionalityHelper::getConn()->where("is_del=false and pid=:pid",array('pid'=>$pid))->limit($offset,$count)->fetchAll();
		return $res;
	}	
  
	public function delFun($id,$pid)
	{
		return	DBFuntionalityHelper::getConn()->update(array('is_del'=>1),"id=:id",array("id"=>$id));
	}

	public function addFun($pid,$name)
	{
		$name = trim($name);
		if($name == '' || $name == null) return false;
		$newItem = array("classify_name"=>$name,"pid"=>$pid);
		return DBFuntionalityHelper::getConn()->insert($newItem);
	}

	public function getFunList(){
		$CR = DBFuntionalityHelper::getConn()->fetchAssocAll();
		foreach($CR as $k=>$v){
			if(!isset($v->child) || empty($v->child)){
				$v->child = array();
				if($v->pid != 0){
					$CR[$v->pid]->child[$k] = $v;
					unset($CR[$k]);
				}
			}
		}
		return $CR;
	}	
}
