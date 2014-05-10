<?php
namespace  Gate\Scripts\Data;

use Gate\Libs\Base\Upload; 
use Gate\Package\Helper\DBCosmeticGoodsHelper;
use Gate\Package\Helper\DBGoodsCommentHelper;
class updateGoodsCommentCount extends \Gate\Libs\Scripts
{
	public function __construct(){
		//header("Content-type: text/html; charset=utf-8");
	}
	public function run()
	{
		$Length = DBCosmeticGoodsHelper::getConn()->fetchCount();
		for($i=0;$i<$Length;$i++)
		{
			$goods = DBCosmeticGoodsHelper::getConn()->field('id')->fetch();
			if($goods)
			{
				$id = $goods->id; 
				$comment_count = DBGoodsCommentHelper::getConn()->where("goods_id=:g_id",array("g_id"=>$id))->fetchCount();	
				DBCosmeticGoodsHelper::getConn()->update(array("comment_count"=>$comment_count),'id=:id',array('id'=>$id));
			}

		}
	}
}
?>
