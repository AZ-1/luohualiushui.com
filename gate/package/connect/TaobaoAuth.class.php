<?php
namespace Gate\Package\Connect;

/**
 * @package connect 淘宝互联授权及获取用户信息
 * 采用OAuth2.0授权
 */

Use \Gate\Package\User\Login;

/**
 * @since 2012-06-20
 * @version 1.0
 */
class TaobaoAuth extends ConnectLib {
	private static $instance = NULL;
	
	/** 
     * @return Object
     */
    public static function getInstance(){
        if (empty(self::$instance)) {
            self::$instance = new self(); 
        }   
        return self::$instance;
    }
   
    /**
	 * 用户授权流程
	 */
	public function taobaoAuth($type, $params = array()) {
    }

	private function _checkRedirect($type, $refer, $frm) {
    }

    /** 
     * 用户授权成功，获取用户信息
     * @param $user_id int 
     * @param array $params 包括httpRequest信息，包括oauth_code,santorini_mm等信息  
     */
	public function taobaoLogin($userId=0, $params = array()) {
		/*
        $c = new TopClient(TAOBAO_APPKEY_AUTH, TAOBAO_APPSECRET_AUTH);
		$accessToken = $c->getAccessToken($params['code'], $callback);
		$userInfo = $c->getUserInfo($accessToken);
		pr($userInfo);
		 */

		if( !isset($_COOKIE[HITAO_LOGIN_COOKIE])){
			setcookie("ORIGION_REFER", $params['request']->referer,  0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
			echo '<script>location.href="http://ecman.hitao.com/passport-login.html";</script>';
			exit();
		}

		$arr = explode('::', $_COOKIE[HITAO_LOGIN_COOKIE]);
		$auth = $arr[0];
		$userInfo['realname']	= $auth;

		// 登陆
		return Login::getInstance()->connectLogin($userInfo, $auth, 'taobao');
	}

}
