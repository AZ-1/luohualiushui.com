<?php
/*
 * 
 * @author wuhui
 * @time  2014/02/25
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBGoodsBasicInfoHelper;
use Gate\Package\Helper\DBRankingListHelper;

use Gate\Package\Cosmetic\Funtionality;
use Gate\Package\Cosmetic\GoodsBasicInfo;

class Ranking{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	

	//获得pid=0的所有数量
	public function getCount(){
		return DBRankingListHelper::getConn()->where('pid=0' , array())->fetchCount();
	}

	//显示
	public function showRank($offset , $length){
		$CR		= DBRankingListHelper::getConn()->limit($offset , $length)->fetchAssocAll();
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
	//得到所有pid=0
	public function getTopRank(){
		$CR		= DBRankingListHelper::getConn()->where('pid=0' , array())->fetchAssocAll();
		return $CR;
	}

	//得到子项
	public function getSubRank($id){
		$CR		= DBRankingListHelper::getConn()->where('pid=:pid' , array("pid"=>$id))->fetchAll();
		return $CR;
	}

	//添加排行
	public function addRank($data){
		return DBRankingListHelper::getConn()->insert($data);
	}

	//修改排行
	public function updateRank($data , $id){
		return DBRankingListHelper::getConn()->update($data,"id=:id",array('id'=>$id));
	}
	
	//删除排行
	public function delRank($id){
		return DBRankingListHelper::getConn()->update(array("is_del"=>1),"id=:id",array('id'=>$id));
	}

	//得到一个排行信息
	public function getRank($id){
		return DBRankingListHelper::getConn()->where('id=:id' , array('id'=>$id))->fetch();
	}
	
	//得到分类列表
	public function getFunList(){
		return Funtionality::getInstance()->getFunList();
	}

	public function getGoodsByClassifyId($classify_id , $offset , $length , $search){
		return GoodsBasicInfo::getInstance()->getGoodsByClassifyId($classify_id , $offset , $length , $search);
	}
	
	public function getGoodsByClassifyIdNum($classify_id , $search){
		return GoodsBasicInfo::getInstance()->getGoodsByClassifyIdNum($classify_id , $search);
	}

	public function updateGoodsOutputPriority($id , $data){
		return GoodsBasicInfo::getInstance()->updateGoodsOutputPriority($id , $data);
	}

}
