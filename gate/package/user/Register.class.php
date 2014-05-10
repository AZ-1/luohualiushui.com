<?php
namespace Gate\Package\User;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Package\Helper\DBUserExtinfoHelper;
use Gate\Package\Helper\DBUserConnectHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Package\Helper\DBUserGradeHelper;

Use \Gate\Libs\Utilities;

class Register{
	private static $instance;
	private $data = array();
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	 * qq新用户
	 */
	public function qzoneAddUser($userInfo, $auth){
		$proData['active_code']		= Utilities::getUniqueId(); // 激活码
		$proData['nickname']		= $auth;
		$proData['realname']		= $this->checkName($userInfo['nickname'], $proData['active_code']);
		$proData['password']		= '';
		$proData['cookie']			= Utilities::getUniqueId();
		$proData['avatar_c']		= (isset($userInfo['figureurl_qq_2']) && !empty($userInfo['figureurl_qq_2']) ) ? $userInfo['figureurl_qq_2'] : $userInfo['figureurl_qq_1'];
		$proData['invite_code']		= Utilities::getUniqueId(); // 邀请码

		$exData['gender']			= $userInfo['gender']=='男' ? 1 : 0;

		$connData['auth']			= $auth;
		$connData['user_type']		= $userInfo['user_type'];
		$connData['status']			= 0;

		return $this->addUser($proData, $exData, $connData);
	}

	/*
	 * sina微博
	 */
	public function weiboAddUser($userInfo, $auth){
		$proData['active_code']		= Utilities::getUniqueId(); // 激活码
		$proData['nickname']		= $auth;
		$proData['realname']		= $this->checkName($userInfo['name'], $proData['active_code']);
		$proData['password']		= '';
		$proData['cookie']			= Utilities::getUniqueId();
		$proData['avatar_c']		= (isset($userInfo['avatar_hd']) && !empty($userInfo['avatar_hd']) ) ? $userInfo['avatar_hd'] : $userInfo['profile_image_url'];
		$proData['invite_code']		= Utilities::getUniqueId(); // 邀请码
		$proData['description']		= $userInfo['description'];

		$exData['gender']			= $userInfo['gender']=='m' ? 1 : 0;

		$connData['auth']			= $auth;
		$connData['user_type']		= $userInfo['user_type'];
		$connData['status']			= 0;

		return $this->addUser($proData, $exData, $connData);
	}

	/*
	 * hitao 主站登录 
	 * taobao
	 */
	public function taobaoAddUser($userInfo, $auth){
		$proData['active_code']		= Utilities::getUniqueId(); // 激活码
		$proData['nickname']		= $auth;
		$proData['realname']		= $this->checkName($userInfo['realname'], $proData['active_code']);
		$proData['password']		= '';
		$proData['cookie']			= Utilities::getUniqueId();
		$proData['avatar_c']		= 'http://mei.hitao.com/static/images/default.gif';
		$proData['invite_code']		= Utilities::getUniqueId(); // 邀请码
		$proData['description']		= '';

		$exData['gender']			= 0;

		$connData['auth']			= $auth;
		$connData['user_type']		= $userInfo['user_type'];
		$connData['status']			= 0;

		return $this->addUser($proData, $exData, $connData);
	}

	/*
	 * 机器人
	 */
	public function robotAddUser($username, $avatar){
		$proData['active_code']		= Utilities::getUniqueId(); // 激活码
		$proData['nickname']		= $this->checkName($username, $proData['active_code']);
		$proData['realname']		= $username . '_'. substr($proData['active_code'], 0, 5);
		$proData['password']		= 'ghost';
		$proData['cookie']			= Utilities::getUniqueId();
		$proData['avatar_c']		= $avatar;
		$proData['invite_code']		= Utilities::getUniqueId(); // 邀请码
		$proData['description']		= '';

		$exData['gender']			= 0;

		return $this->addUser($proData, $exData);
	}
	
	public function checkName($name, $activeCode){
		$status = DBUserProfileHelper::getConn()->field('user_id')->where('realname=:name', array('name'=>$name))->fetch();

		$realName = $name;
		if(!empty($status)){
			$realName = $name.'_'.substr($activeCode, 0, 5);
		}
		return $realName;
	}

	/*
	 * 
	 */
	public function addUser($proData, $exData, $connData=array()){
		if(isset($proData['last_login_ip']) && $proData['last_login_ip']!=''){
			$proData['last_login_ip'] = ip2long($proData['last_login_ip']);
		}
		$newId = DBUserProfileHelper::getConn()->insert($proData);
		if($newId && isset($proData['user_id'])){
			$newId = $proData['user_id'];
		}
		if($newId){
			$exData['user_id']		= $newId;
			$stData['user_id']		= $newId;

			DBUserExtinfoHelper::getConn()->insert($exData);
			DBUserStatisticHelper::getConn()->insert($stData);

			if(!empty($connData)){
				$connData['user_id']	= $newId;
				DBUserConnectHelper::getConn()->insert($connData);
			}
		}
		return $newId;
	}
}
