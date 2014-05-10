<?php
/*
 * 话题
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBSpecialHelper;
use Gate\Package\User\Userinfo;

class Special{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getSpecialList($offset, $length){
		return DBSpecialHelper::getConn()->field('special_id,title,is_online,create_time')->where('is_delete=0', array())->order('special_id DESC')->limit($offset ,$length)->fetchAll();
	}

	public function addSpecial($data){
		return DBSpecialHelper::getConn()->insert($data);
	}
	
	public function getSpecialFind($id){
		return DBSpecialHelper::getConn()->where('special_id=:id', array('id'=>$id))->fetch();
	}

	public function updateSpecial($data, $id){
		return DBSpecialHelper::getConn()->update($data, 'special_id=:id', array('id'=>$id));	
	}

	public function getSearchList($keyword){
		return DBSpecialHelper::getConn()->field('special_id,title,is_online,create_time')->where('title LIKE :titleval', array('titleval'=>"%$keyword%"))->fetchAll();
	}

	public function getSpecialCount(){
		return DBSpecialHelper::getConn()->fetchCount();
	}

	public function setSpecialOnline($id, $status){
		return DBSpecialHelper::getConn()->update($status, 'special_id=:id', array('id'=>$id));
	}
}
