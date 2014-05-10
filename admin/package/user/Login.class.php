<?php
/*
 * haihong
 */
namespace Gate\Package\User;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Libs\Session;

class Login{
	private static $instance;
	private $data = array();
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	
	public function checkLogin($nickname, $password){
		$params = array('nickname'=>$nickname);
		$userinfo = DBUserProfileHelper::getConn()->field('user_id, nickname, realname, cookie, avatar_c, salt, password, identity')->where('nickname=:nickname', $params)->limit(1)->fetch();
		if(!$userinfo){
			return 40002;
		}
		$encryptPassword = $this->getEncryptPassword($userinfo->salt, $password);
		if($userinfo->password!=$encryptPassword){
			return 40003;
		}
		unset($userinfo->password, $userinfo->salt);
		// session
		return Session::Singleton()->marked($userinfo);
	}

	/*
	 * 加密密码
	 */
	public function getEncryptPassword($salt, $password){
        return sha1($salt . sha1($password));
	}
}
