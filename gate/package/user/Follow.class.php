<?php
/*
 * 
 * @author
 */
namespace Gate\Package\User;
use Gate\Package\Helper\DBFollowHelper;
use Gate\Package\User\Userinfo;
use Gate\Package\User\Fans;
use Gate\Package\Helper\DBUserStatisticHelper;

class Follow{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getFollowList($userId, $offset, $length){
		$followUserIds = DBFollowHelper::getConn()->field('follow_user_id')->where('user_id=:user_id', array('user_id'=>$userId))->ORDER('follow_id DESC')->limit($offset, $length)->fetchCol();
		$list = Userinfo::getInstance()->getUserByIds($followUserIds, 'user_id, realname, description, avatar_c, grade, article_num, fans_num', 0, $length);
		return array_values($list);
	}

	public function getFollowListAll($userId, $offset, $length){
		$followUserIds = DBFollowHelper::getConn()->field('follow_user_id')->where('user_id=:user_id', array('user_id'=>$userId))->limit($offset, $length)->fetchCol();
		return Userinfo::getInstance()->getUserByIds($followUserIds, 'user_id, realname, description, avatar_c, grade, article_num, fans_num', 0, $length);
	}
	
	public function getFollowAllIdList($userId){
		$followList = DBFollowHelper::getConn()->field('follow_user_id')->where('user_id =:user_id', array('user_id'=>$userId))->fetchCol();
		return $followList;
	}


	// check user是否是在follow_user关注列表
	public function checkFollowList($loginUserId , $viewUserId){
		$followUserIds = $this->getFollowViewUserIds($loginUserId, array($viewUserId));
		if(empty($followUserIds)){
			return false;
		}
		return true;
	}

	/*
	 * 检查是否关注
	 * $viewUserIds 可以是 user_id数组 
	 * 返回已关注的用户
	 */
	public function getFollowViewUserIds($loginUserId, $viewUserIds){
		$followUserIds = DBFollowHelper::getConn()->field('follow_user_id')->where('user_id=:user_id AND follow_user_id IN(:follow_user_id)', array('user_id'=>$loginUserId, 'follow_user_id'=>$viewUserIds))->fetchCol();
		return $followUserIds ;
	}


	public function getFollowIds($userId, $offset, $length){
		return  DBFollowHelper::getConn()->field('follow_user_id')->where('user_id=:user_id', array('user_id'=>$userId))->limit($offset, $length)->fetchCol();
	}

	public function getFollowUserIds($userId){
		return  DBFollowHelper::getConn()->field('follow_user_id')->where('user_id=:user_id', array('user_id'=>$userId))->fetchCol();
	}

	/*
	 * 添加
	 */
	public function addFollow($loginUserId, $viewUserId){
		$row = DBUserStatisticHelper::getConn()->field('follow_num')->where('user_id=:uid', array('uid'=>$loginUserId))->fetch();
		if($row->follow_num>200){
			return false;
		}
		$row = DBFollowHelper::getConn()->field('follow_id')->where('user_id=:uid AND follow_user_id=:fuid', array('uid'=>$loginUserId, 'fuid'=>$viewUserId))->limit(1)->fetch();
		if($row){
			return $row->follow_id;
		}
		$data['user_id'] = $loginUserId;
		$data['follow_user_id'] = $viewUserId;
		$newId =  DBFollowHelper::getConn()->insert($data);
		if($newId){
			DBUserStatisticHelper::getConn()->increment('follow_num', array('user_id'=>$loginUserId));
			// add  fans
			Fans::getInstance()->addFans($loginUserId, $viewUserId);
			// 消息提醒
			$messageType = Message::getInstance()->getType();
			Message::getInstance()->addMessage($viewUserId, $messageType->fans);
		}
		return $newId;
	}

	/*
	 * 删除
	 */
	public function delFollow($id=0, $loginUserId=0, $viewUserId=0){
		$where = 'user_id=:user_id';
        $sqlData['user_id']		= $loginUserId;
		if(!$viewUserId){
			$where .= ' AND follow_id=:follow_id';
			$sqlData['follow_id']		= $id;
		}else{
			$where .= ' AND follow_user_id=:follow_user_id';
			$sqlData['follow_user_id']	= $viewUserId;
		}
		$isD = DBFollowHelper::getConn()->delete($where, $sqlData);
		
		if($isD){
			DBUserStatisticHelper::getConn()->decrement('follow_num', array('user_id'=>$loginUserId));
			// del fans
			Fans::getInstance()->delFans(0, $loginUserId, $viewUserId);
		}
		return $isD;
	}

	/*
	 * 用户的关注数量
	 */
	public function getFollowNum($userId){
		$row = DBUserStatisticHelper::getConn()->field('follow_num')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		return (int)$row->follow_num;
	}

}
