<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBGoodsBasicInfoHelper;
use Gate\Package\Helper\DBGoodsCommentHelper;
use Gate\Libs\Utilities;
class Cosmetic{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getCommentListById($id,$offset,$length){
		$goods = DBGoodsBasicInfoHelper::getConn()->field('pro_name')->where('id=:id',array('id'=>$id))->fetch();	
		if($goods){
			$commentList = DBGoodsCommentHelper::getConn()->where('goods_id=:id AND is_del=0',array('id'=>$id))->limit($offset,$length)->fetchAll();
			foreach($commentList as $v){
				$v->content = Utilities::zaddslashes($v->content);
				$v->info = $goods;
			}
			return $commentList;
		}
		return $goods;
	}

	public function getCommentCount($id,$key){
		if($id){
			return DBGoodsCommentHelper::getConn()->where('goods_id=:id AND is_del=0',array('id'=>$id))->fetchCount();
		}
	
		$goods = DBGoodsBasicInfoHelper::getConn()->field('id,pro_name')->where('pro_name LIKE :keyword',array('keyword'=>$key.'%'))->fetchAll();
		foreach($goods as $v){
			$goods_id[] = $v->id;
		}
		if(!empty($goods)){
			$commentList = DBGoodsCommentHelper::getConn()->where('goods_id IN(:id) AND is_del=0',array('id'=>$goods_id))->fetchCount();
			return $commentList;

		}
		return 0;

	}

	public function getCommentByID($id){
		return DBGoodsCommentHelper::getConn()->where("id=:id",array("id"=>$id))->fetch();
	}
	public function getCommentListByKeyword($keyword , $offset,$length){
		$goods = DBGoodsBasicInfoHelper::getConn()->field('id,pro_name')->where('pro_name LIKE :keyword',array('keyword'=>$keyword.'%'))->fetchAll();
		if(!empty($goods)){
			foreach($goods as $v){
				$goods_id[] = $v->id;
			}
			$commentList = DBGoodsCommentHelper::getConn()->where('goods_id IN(:id) AND is_del=0',array('id'=>$goods_id))->limit($offset,$length)->fetchAll();
			foreach($commentList as $v){
				$v->content = Utilities::zaddslashes($v->content);
				foreach($goods as $vv){
					if($v->goods_id == $vv->id){
						$v->info = $vv;
					}
				}
			}
			return $commentList;

		}
		return array();
	}
	public function delComment($id){
		return DBGoodsCommentHelper::getConn()->update(array("is_del"=>1),"id=:id",array('id'=>$id));
	}
	public function upComment($id,$content){
		return DBGoodsCommentHelper::getConn()->update(array("content"=>$content),"id=:id",array('id'=>$id));
	}
}
