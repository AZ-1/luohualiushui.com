<?php
/*
 * haihong
 */
namespace Gate\Package\User;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Package\Helper\DBUserConnectHelper;
use Gate\Package\Helper\DBUserExtinfoHelper;
use Gate\Package\Helper\DBUserConnectBindHelper;

use \Gate\Package\User\Register;
use \Gate\Package\User\Userinfo;

use Gate\Libs\Session;
use Gate\Libs\Utilities;

class Login{
	const USER_FIELDS = 'user_id, nickname, realname, grade, avatar_c, status, is_delete,identity';
	private static $instance;
	private $data = array();
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function isLogin($userId=0){
		if( Session::Singleton()->id){
			return true;
		}
		return false;
	}

	public function getLoginUser(){
		return Session::Singleton();
	}

	public function getLoginUserId(){
		return Session::Singleton()->id;
	}
	
	/*
	 * 用户名密码登录
	 */
	private function defaultLogin($nickname, $password){
		$msg = array('message'=>'', 'status'=>false);
		$params = array('nickname'=>$nickname);
		$userinfo = DBUserProfileHelper::getConn()->field(self::USER_FIELDS . ',password')->where('nickname=:nickname', $params)->limit(1)->fetch();

		$encryptPassword = $this->getEncryptPassword($userinfo->salt, $password);
		if($userinfo->password!=$encryptPassword){
			$msg['message'] =  '用户名或密码错误';
			return $msg;
		}
		return $this->checkLogin($userinfo);
	}

	/*
	 * BYID Login
	 */
	public function loginById($id){
		$params = array('user_id'=>$id);
		$userinfo = DBUserProfileHelper::getConn()->field(self::USER_FIELDS)->where('user_id=:user_id', $params)->limit(1)->fetch();
		return $this->checkLogin($userinfo);
	}

	/*
	 * 第三方登录
	 */
	public function connectLogin($userInfo, $auth, $connectType, $isMobile=false){
		// 绑定
		if($this->isLogin()){
			return $this->connectBind($auth, $userInfo['realname'],$connectType);
		}

		$userType = $this->getUserType($connectType);
		// 查看默认第三方
		$connRow = DBUserConnectHelper::getConn()->field('user_id')->where('auth=:auth AND user_type=:user_type', array('auth'=>$auth, 'user_type'=>$userType))->limit(1)->fetch();
		if( !$connRow){
			// 绑定第三方
			$connRow = DBUserConnectBindHelper::getConn()->field('user_id')->where('auth=:auth AND user_type=:user_type', array('auth'=>$auth, 'user_type'=>$userType))->limit(1)->fetch();
		}
		if( !$connRow){
			$addUser = $connectType.'AddUser';
			$userInfo['user_type'] = $userType;
			$userId = Register::getInstance()-> $addUser($userInfo, $auth);
		}else{
			$userId = $connRow->user_id;
		}
		$row = DBUserProfileHelper::getConn()->field(self::USER_FIELDS)->where('user_id=:user_id', array('user_id'=>$userId))->limit(1)->fetch();
		return $this->checkLogin($row, $isMobile);
	}

	/*
	 * 第三方绑定
	 */
	public function connectBind($auth, $realname, $connectType){
		$msg['status']  = 1;
		$msg['url']		= BASE_URL . 'user/edit_bound/is_bind/1';
		$msg['message'] = '绑定成功';
		if( !Session::Singleton()->id){
			$msg['message'] = '绑定失败';
			$msg['status']	= false;
			return  $msg;
		}
		$loginUserId = Session::Singleton()->id;

		// 检查是否已绑定 默认第三方登录
		$connectRow = DBUserConnectHelper::getConn()->field('user_id')->where('user_id=:user_id AND auth=:auth', array('user_id'=>$loginUserId, 'auth'=>$auth))->fetch();
		if($connectRow){
			return $msg;
		}
		// 检查是否已绑定 更多第三方登录
		$bindRow = DBUserConnectBindHelper::getConn()->field('user_id')->where('user_id=:user_id AND auth=:auth', array('user_id'=>$loginUserId, 'auth'=>$auth))->fetch();
		if($bindRow){
			return $msg;
		}
		
		$data = array(
			'auth'		=> $auth, 
			'user_id'	=> $loginUserId,
			'user_type'	=> $this->connectType($connectType),
			'realname'	=> $realname
		);
		$isIn = DBUserConnectBindHelper::getConn()->insert($data);
		if($isIn){
			return $msg;
		}

		$msg['message'] = '绑定失败';
		$msg['status']	= false;
		return  $msg;
	}


	/*
	 * 
	 */
	public function getEncryptPassword($salt, $password){
        return sha1($salt . sha1($password));
	}

	private function checkLogin($userinfo, $isMobile=false){
		$msg = array('message'=>'', 'status'=>false);
		if(!$userinfo){
			$msg['message'] =  '用户名不存在';
			return $msg;
		}

		if($userinfo->is_delete){
			$msg['message'] =  '用户账号被停用';
			return $msg;
		}
		
		$updata = array(
			'last_login_ip' => Utilities::getClientIp(),
			'last_login_time' => time()
		);
		DBUserProfileHelper::getConn()->update($updata, 'user_id=:user_id', array('user_id'=>$userinfo->user_id));
		$userinfo->grade = Userinfo::getInstance()->getGrade($userinfo->grade);
		unset($userinfo->is_delete);

		// session
		if($isMobile){ //手机登录，返回
			$markvalue = Session::Singleton()->marked_m($userinfo);
			if($markvalue){
				$msg['sid'] = $markvalue;
				$isM = true;
			}
		}else{
			$isM = Session::Singleton()->marked($userinfo);
		}
		if(!$isM){
			$msg['message'] = 'session异常';
			return $msg;
		}

		$msg['userinfo'] = $userinfo;
		$msg['status'] = true;
		$msg['message'] = '登录成功';
		return $msg;
	}

	private function getUserType($type){
		return $this->connectType($type);
	}

	/*
	 */
	public function isGuideRealname($userId){
		$row = DBUserProfileHelper::getConn()->field('status')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		if($row->status == 0){ //是否引导 status = 1 完成引导
			return false;
		}
		return true;
	}

	public function isGuideFollow($userId){
		$row = DBUserExtinfoHelper::getConn()->field('is_guide_follow')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		return $row->is_guide_follow;
	}

	public function isGuideTopic($userId){
		$row = DBUserExtinfoHelper::getConn()->field('is_guide_topic')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		return $row->is_guide_topic;
	}
	
	/*
	 *
	 */
	private function connectType($type=''){
		$connect = array(
			'qzone'		=> 0, 
			'weibo'		=> 1,
			'taobao'	=> 2,
		);
		return $type!='' ? $connect[$type] : $connect;
	}

}
