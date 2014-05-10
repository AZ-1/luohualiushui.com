<?php
/*
 * 
 * @author
 */
namespace Gate\Package\User;
use Gate\Package\Helper\DBFansHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Package\User\Message;

class Fans{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getFansList($userId, $offset, $length){
		$fansUserIds = DBFansHelper::getConn()->field('fans_user_id')->where('user_id=:user_id', array('user_id'=>$userId))->ORDER('fans_id DESC')->limit($offset, $length)->fetchCol();
		$list = Userinfo::getInstance()->getUserByIds($fansUserIds, 'user_id, realname, description, avatar_c, grade, article_num, fans_num', 0, $length);
		return array_values($list);
	}

	/*
	 * 用户的粉丝数量
	 */
	public function getFansNum($userId){
		$row = DBUserStatisticHelper::getConn()->field('fans_num')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		return (int)$row->fans_num;
	}


	/*
	 * 添加
	 * 登录者作为粉丝
	 */
	public function addFans($loginUserId, $viewUserId){
		$row = DBFansHelper::getConn()->field('fans_id')->where('user_id=:uid AND fans_user_id=:fuid', array('uid'=>$viewUserId, 'fuid'=>$loginUserId))->limit(1)->fetch();
		if($row){
			return $row->fans_id;
		}

		$data['user_id'] = $viewUserId;
		$data['fans_user_id'] = $loginUserId;
		$isF =  DBFansHelper::getConn()->insert($data);
		if($isF){
			DBUserStatisticHelper::getConn()->increment('fans_num', array('user_id'=>$viewUserId));
			// 消息提醒
			$messageType = Message::getInstance()->getType();
			Message::getInstance()->addMessage($viewUserId, $messageType->fans);
		}
		return $isF;
	}

	/*
	 * 删除
	 */
	public function delFans($id=0, $loginUserId=0, $viewUserId=0){
		$where = 'user_id=:user_id';
        $sqlData['user_id']		= $viewUserId;
		if(!$viewUserId){
			$where .= ' AND fans_id=:fans_id';
			$sqlData['fans_id']		= $id;
		}else{
			$where .= ' AND fans_user_id=:fans_user_id';
			$sqlData['fans_user_id']	= $loginUserId;
		}
		$isD = DBFansHelper::getConn()->delete($where, $sqlData);
		
		if($isD){
			DBUserStatisticHelper::getConn()->decrement('fans_num', array('user_id'=>$viewUserId));
		}
		return $isD;
	}

}
