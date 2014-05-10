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
use Gate\Package\Helper\DBCosmeticBannerHelper;
class Banner{
	private static $instance;
	public static function getInstance(){
		is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
	}

    /*
	*  输入格式化
	*/
	private function format()
	{
		return value;
	}

    /*
	* 删除焦点图 
	*/
	public function delBanner($id)
	{
		return DBCosmeticBannerHelper::getConn()->update(array("is_del"=>1),'id=:id',array("id"=>$id));
	}
    /*
    * 获取描述信息 
    *
    * */
	private function getInfo($data , $level = 0 )
	{
		$desc  = '';
		$info =  json_decode($data);
		$desc .= $level===0 ? "滑动展示（大图）" : "列表展示(小图)";
		$desc .= "点击后";
		 if($info->type == "ProductDetailsActivity"){
			$data = explode(',',$info->params);
			 $desc .= "显示宝贝  $data[0]";
			 return $desc;
		 }else	if($info->type == 'URL'){
			 $desc .= "转到: $info->value";
			 return $desc;
		 }
		$desc .= "转至分类: $info->params";
		return $desc;
	} 
   

	/*
	* 取得图片及 点击后的调转信息
    */
	public function getPic($id)
	{
		$item = DBCosmeticBannerHelper::getConn()->where("is_del=0 AND id=:id",array("id"=>$id))->fetch();	
		$obj = new \stdClass;
		$obj->order = $item->displayOrder;	
		$obj->id = $item->id;	
		$obj->pid = $item->pid;	
		$obj->img = $item->img_src;	
		$obj->info = $this->getInfo($item->forwardAddress,$item->pid);	
		return $obj;
	}

	/*
	* 保存焦点图 
    */
	public function savePic($info,$id)
	{
		return $item = DBCosmeticBannerHelper::getConn()->update($info,"id=:id",array("id"=>$id));	
	}

	/*
	* 新建焦点图 
    */
	public function insertPic($info)
	{
		return $item = DBCosmeticBannerHelper::getConn()->insert($info);	
	}
	/*
	* 取得所有的 图片及 点击后的调转信息
    */
	public function getAllPic()
	{
		$pics =  DBCosmeticBannerHelper::getConn()->where("is_del=0",array())->fetchAll();	
		$ret_data = array();
		$i = 0;
		foreach( $pics as $item)
		{
			$obj = new \stdClass;
			$obj->order = $item->displayOrder;	
			$obj->id = $item->id;	
			$obj->img = $item->img_src;	
			$obj->info = $this->getInfo($item->forwardAddress,$item->pid);	
			$ret_data[$i] = $obj;
			$i++;
		}
		return $ret_data;
	}
}
