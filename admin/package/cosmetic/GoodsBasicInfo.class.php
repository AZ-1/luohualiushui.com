<?php
/*
 * 
 * @author wuhui
 * @time  2014/02/25
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBGoodsBasicInfoHelper;


class GoodsBasicInfo{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	
	public function getGoodsByClassifyId($classify_id , $offset , $length , $search){
		$where = 'classify_id=:cid';
		$params = array(
			'cid'=>$classify_id
		);
		if(isset($search['title']) && $search['title'] != ''){
			$where .= ' AND pro_name LIKE :pro_name';
			$params['pro_name'] = '%'.$search['title'].'%';
		}
		return DBGoodsBasicInfoHelper::getConn()->where($where,$params)->order('output_priority DESC')->limit($offset , $length)->fetchAll();
	}
	
	public function getGoodsByClassifyIdNum($classify_id , $search){
		$where = 'classify_id=:cid';
		$params = array(
			'cid'=>$classify_id
		);
		if(isset($search['title']) && $search['title'] != ''){
			$where .= ' AND pro_name LIKE :pro_name';
			$params['pro_name'] = '%'.$search['title'].'%';
		}
		return DBGoodsBasicInfoHelper::getConn()->where($where , $params)->fetchCount();
	}

	public function updateGoodsOutputPriority($id , $data){
		return DBGoodsBasicInfoHelper::getConn()->update($data , 'id=:id' , array('id'=>$id));	
	}

}
