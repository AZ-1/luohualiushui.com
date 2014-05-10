<?php
namespace  Gate\Scripts\Data;

use Gate\Libs\Base\Upload; 
use Gate\Package\Helper\DBCosmeticGoodsHelper;

class ReplaceImgInGoods extends \Gate\Libs\Scripts
{
	public function __construct(){
		//header("Content-type: text/html; charset=utf-8");
	}
	public function run()
	{
		$sql = "select count(*) AS LEN from cosmetic_goods_basic_info" ;
		$Length = DBCosmeticGoodsHelper::getConn()->fetch($sql);
		$len = $Length->LEN;
		for($i=0;$i<$len;$i++)
		{
			//if($i<1905)continue;
			echo "\ncurrent position=*******************$i**************\n";
			$sql = "select id,img_add from cosmetic_goods_basic_info LIMIT $i,1";
			$info = DBCosmeticGoodsHelper::getConn()->fetch($sql);
			if($info)
			{
				$img = $info->img_add;
//				echo "$img";
				if(strpos($img,"400x400"))
				{
					$img.=".jpg";
				}
				if($img == '')
				{
					echo "NOTICE: get nothing from  DB \n";
					continue;
				}
				if($i%5 == 0)sleep(1);
				try{
					$img_url = Upload::cloudFile($img);
					if($img_url){
						$res_state = DBCosmeticGoodsHelper::getConn()->update(array("img_add"=>$img_url),"id=:id",array("id"=>$info->id));
						if($res_state)
						{
							echo "SUCCESS: new URL $img_url\n";
						}else{
							echo "ERROR: updating ERROR: while storing $img\n";
						}
					}else{
						echo "ERROR : reading $img from DB , getting $img_url from aliyun\n";
					}

				}catch(Exception $e)
				{
					
				}
			}

		}
	}
}
?>
