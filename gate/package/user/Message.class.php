<?php
/*
 * 消息系统
 * @author
 */
namespace Gate\Package\User;
use Gate\Package\Helper\DBMessageHelper;

class Message{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
	}



	/*
	 * 查看消息
	 */
	public function getMessageNum($userId){
		$msg = new \stdClass;
		$list = DBMessageHelper::getConn()->field('type,num')->where('user_id=:user_id', array('user_id'=>$userId))->fetchAssocAll();
		$type = $this->getType();
		$msg->like_num = isset($list[$type->like]) ? $list[$type->like]->num : 0;
		$msg->comment_num = isset($list[$type->comment]) ? $list[$type->comment]->num : 0;
		$msg->fans_num = isset($list[$type->fans]) ? $list[$type->fans]->num : 0;

		return $msg;
	}

	/*
	 * 新增消息
	 */
	public function addMessage($userId, $type){
		$row = DBMessageHelper::getConn()->field('id')->where('user_id=:user_id AND type=:type', array('user_id'=>$userId, 'type'=>$type))->fetch();
		if($row){
			return DBMessageHelper::getConn()->increment('num', array('id'=>$row->id));
		}else{
			return DBMessageHelper::getConn()->insert(array('user_id'=>$userId, 'type'=>$type, 'num'=>1));
		}
	}

	/*
	 * 删除消息
	 */
	public function delMessage($userId, $type=null){
		$where = 'user_id =:user_id';
		$param = array('user_id'=>$userId);
		if($type!==null){
			$typeValue = $this->getType()->$type;
			$where .= ' AND type=:type';
			$param['type'] = $typeValue;
		}
		return DBMessageHelper::getConn()->delete($where, $param);
	}

	/*
	 * 消息类型
	 */
	public function getType(){
		$type = new \stdClass();
		$type->like		= 1;
		$type->comment	= 2;
		$type->fans		= 3;	
		return $type;
	}
}
