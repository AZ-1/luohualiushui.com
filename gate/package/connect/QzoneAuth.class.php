<?php
namespace Gate\Package\Connect;

/**
 * @package connect QQ授权及获取用户信息
 * 采用OAuth2.0授权
 */

Use \Gate\Libs\Utilities;
Use \Gate\Libs\Memcache;
Use \Gate\Package\User\Login;

Use \Gate\Package\Oauth\QzoneClient;
Use \Gate\Package\Oauth\QzoneOauth;

/**
 * @since 2012-06-20
 * @version 1.0
 */
class QzoneAuth extends ConnectLib {
	private static $instance = NULL;
	
    /**
     * 用户授权流程
     * @param $type = qzone
     * @param array $params 包括httpRequest信息，包括oauth_code,santorini_mm等信息 
	 */
	public function qzoneAuth($type, $params = array()) {
		$callback = $this->getCallback('qzone', $params);

		if (!empty($params['frm'])) {
			$callback .= "&r=" . $params['frm'];
		}
		$queryStr = isset($params['queryCookie']) ? $params['queryCookie'] : "";
		setcookie('MEI_QUERY', FALSE, $_SERVER['REQUEST_TIME']-3600, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
		$this->_checkRedirect($type, $params, BASE_URL );

		$state = Utilities::getUniqueId();
		$result = $this->getQzoneOauth($callback, $state, array(QZONE_ID, QZONE_KEY), 'default', $params['ip']);
		return $result;
    }

	private function _checkRedirect($type, $params, $host ) {
		setcookie("ORIGION_REFER", $params['request']->referer,  0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);

		$refer = isset($params['request']->referer) ? $params['request']->referer : '';
		$frm = isset($params['request']->GET['frm']) ? $params['request']->GET['frm'] : '';
		$frm360 = isset($params['frm']) ? $params['frm'] : '';

        $callback = 'http://' . $host . 'connect/auth/' . $type . '?baseUrl=' . $host;
		//frm 以fk_作为前缀的都是从浮层点击的，跳向首页
		//frm 以tk_作为前缀的都是弹点击的。
		if (strpos('prefix' . $frm, 'fk_') || (strpos("pop" . $frm, 'tk_') && strpos("pop" . $frm, 'gad'))) {
			setcookie("ORIGION_REFER", 'home', 0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
		}
        elseif (strpos('prefix' . $frm360, 'share/share?url=')) {
            setcookie("ORIGION_REFER", $params['frm'], 0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
        }
		elseif (!strpos($params['request']->referer, 'logon') && !strpos($params['request']->referer, 'register')) {
			setcookie("ORIGION_REFER", $params['request']->referer,  0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
		}
        return TRUE;
    }

	//TODO
    /** 
     * 用户授权成功，获取用户信息
     * @param $user_id int 
     */
	public function qzoneLogin($type, $params = array()) {
		$callback = $this->getCallback('qzone', $params);
		if (!empty($params['frm'])) {
			$callback .= "&r=" . $params['frm'];
		}
		$result = $this->qzoneAccess($callback, array(QZONE_ID, QZONE_KEY), $params['code'], $params['ip']);

		$access_token = $result['qzone_access_keys']['access_token'];
		$ttl = $result['qzone_access_keys']['expires_in'];
		$auth = $result['qzone_openid']['openid'];
		
		$start = microtime(true);
		$userInfo = $this->getQzoneInfo($access_token, $auth);

		if (empty($userInfo)  || empty($auth)) {
			$failInfo = array();
            $failInfo['error'] = '获取Qzone信息失败';
            return $failInfo;
		}
		$userInfo['realname'] = $userInfo['nickname'];

		// 登陆
		return Login::getInstance()->connectLogin($userInfo, $auth, 'qzone');
	}


	public function getQzoneInfo($access_token = NULL, $openId = NULL, $keyInfo = array(QZONE_ID, QZONE_KEY)) {
        if (empty($access_token) || empty($openId)){
            return FALSE;
        }    
        $aKey = $keyInfo[0];
        $sKey = $keyInfo[1];
        $userInfo = array();
        $qc = new QzoneClient($aKey, $sKey, $access_token, $openId);
		$userInfo = $qc->get_user_info();
        return $userInfo;
    }    


	public function getQzoneOauth($callbackUrl, $state, $keyInfo = array(QZONE_ID, QZONE_KEY), $scope = 'default', $ip = '', $display = '') {
        $aKey = $keyInfo[0];
        $sKey = $keyInfo[1];
        $o = new QzoneOauth($aKey, $sKey, NULL, NULL, $ip);
        $authorUrl = $o->getAuthorizeUrl($callbackUrl, $response_type = 'code', $scope, $state, $display);
		$result = array();
		$result['redirectUrl'] = $authorUrl;
		$result['flag'] = 1;
		$result['result'] = FALSE;
		return $result;
    }


	/**    
     * qzoneOAuth2.0方式授权
     * @param string $callbackUrl 跳转回的URL
     * @param array  $keyInfo array(APPID, APPKEY)
     *
     */
    public function qzoneAccess($callbackUrl, $keyInfo = array(QZONE_ID, QZONE_KEY), $authCode = NULL, $ip = '') {
        if (empty($authCode)) {
            return FALSE;
        }

        $aKey = $keyInfo[0];
        $sKey = $keyInfo[1];
        $type = 'code';
        $keys = array();
        $keys['code'] = $authCode;
		$params = array();
        $params['state'] = md5(uniqid(rand(), TRUE));
        $o = new QzoneOauth($aKey, $sKey, NULL, NULL, $ip);
        $accessKeys = $o->getAccessToken($callbackUrl, $type, $keys, $params['state']);
		/*
		 * <code>
		 *    Array (
         * 	      [access_token] => C77B688321C4B99A1F4314C0F56E4470
         *        [expires_in] => 7776000
         *    ) 
		 * </code>
		 */
        $params['qzone_access_keys'] = $accessKeys;
        $openIdKeys = $o->getOpenId();
		/*
		 * <code>
		 *    Array (
         * 	      [client_id] => 100210915
         *        [openid] => B310D52746854C14D0B713DE73A8F678
         *    ) 
		 * </code>
		 */
        $params['qzone_openid'] = $openIdKeys;
		$params['result'] = TRUE;
        return $params;
	}
}
