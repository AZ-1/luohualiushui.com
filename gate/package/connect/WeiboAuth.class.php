<?php
namespace Gate\Package\Connect;

/**
 * @package connect 新浪微博互联授权及获取用户信息
 * 采用OAuth2.0授权
 * @since 2012-06-20
 * @version 1.0
 */
Use \Gate\Libs\Utilities;
Use \Gate\Libs\Memcache;
Use \Gate\Package\Oauth\SaeTOAuth;
Use \Gate\Package\Oauth\SaeTClient;
Use \Gate\Package\User\Login;

class WeiboAuth extends ConnectLib {
    /**
	 * 用户授权流程
     * @param $refer $_SERVER['HTTP_REFERER']
     * @param $frm isset($_GET['frm']) ? $_GET['frm'] : '';
     * @param $type = sina
     * @param array $params 包括httpRequest信息，包括oauth_code,santorini_mm等信息 
	 */
 	public function weiboAuth($type, $params = array()) {
		$callback = $this->getCallback('weibo', $params);

		if (!empty($params['frm'])) {
			$callback .= "?r=" . $params['frm'];
		}

		$this->_checkRedirect($type, $params);
		$result = $this->getWeiboOath($callback, $params['state']);
		return $result;
	}

    //去新浪登录
	private function getWeiboOath($callbackUrl, $fromMobile = FALSE){
            $o = new SaeTOAuth(WEIBO_AKEY, WEIBO_SKEY);
            $aurl = $o->getAuthorizeURL($callbackUrl, 'code', $fromMobile);
			$result = array();
			$result['redirectUrl'] = $aurl;
			$result['result'] = FALSE;
			$result['flag'] = 1;
            return $result;
	}

    private function _checkRedirect($type, $params) {
        $frm = isset($params['request']->GET['frm']) ? $params['request']->GET['frm'] : ''; 
		$frm360 = isset($params['frm']) ? $params['frm'] : ''; 

        //frm 以fk_作为前缀的都是从浮层点击的，跳向首页
        if (strpos('prefix' . $frm, 'fk_') || (strpos("pop" . $frm, 'tk_') && strpos("pop" . $frm, 'gad'))) {
            setcookie('ORIGION_REFER', 'home', 0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
        }
        elseif (strpos('prefix' . $frm360, 'share/share?url=')) {
            setcookie("ORIGION_REFER", $params['frm'], 0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
        }
        elseif (!strpos($params['request']->refer, 'logon') && !strpos($params['request']->refer, 'register')) {
            setcookie('ORIGION_REFER', $params['request']->refer, 0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
        }
    }

    /** 
	 * 用户授权成功，获取用户信息
     * @param $user_id int 
     * @param array $params 包括httpRequest信息，包括oauth_code,santorini_mm等信息  
     */
	public function weiboLogin($userId, $params = array()) {
		$callback = $this->getCallback('weibo', $params);
		if (!empty($params['frm'])) {
			$callback .= "&r=" . $params['frm'];
		}
		$accessToken = $this->weiboAccess($callback, $params['code']);
		if(!isset($accessToken['weibo_access_keys']['access_token'])){
			$failInfo = array();
			$failInfo['error'] = '微博登录失败';
			return $failInfo;
		}
		$userInfo = $this->getWeiboInfo($accessToken['weibo_access_keys']);

		if (empty($userInfo)) {
			$failInfo = array();
			$failInfo['error'] = '获取微博信息失败';
			return $failInfo;
		}

		$auth		= $userInfo['id'];
		$userInfo['realname']	= $userInfo['name'];
		// 登陆
		return Login::getInstance()->connectLogin($userInfo, $auth, 'weibo');
	}

	private function weiboAccess($callbackUrl, $requestCode){
            $keys['code'] = $requestCode; //$_REQUEST['code'];
            $keys['redirect_uri'] = $callbackUrl;
            $o = new SaeTOAuth(WEIBO_AKEY, WEIBO_SKEY);
            $last_key = $o->getAccessToken('code', $keys);
			$result['weibo_access_keys'] = $last_key;
			$result['weibo_access_keys_for_app'] = $last_key;
			$result['result'] = TRUE;
			return $result;
	}


	/**
     * 从新浪微博获取当前用户信息
     */
    private function getWeiboInfo( $weibo_access_keys) {
        $wbc = $this->getWeiboClient(0, $weibo_access_keys);
        if ($wbc === FALSE) {
            return FALSE;
        }
        $ret = $wbc->verify_credentials();
        if (isset($ret['error'])) {
            return FALSE;
        }
        else {
			$uid = $ret['uid'];
			$userInfo = $wbc->show_user($uid);
			if (isset($userInfo['error'])) {
				return FALSE;
			}
            return $userInfo;
        }
    }

    private function getWeiboClient($userId = 0, $weibo_access_keys = array()) {
        $session_key = 'weibo_access_keys';
        if (isset($weibo_access_keys['access_token'])) {
            $wbc = new SaeTClient(WEIBO_AKEY, WEIBO_SKEY, $weibo_access_keys['access_token']);
			return $wbc;
        }
        return FALSE;
    }

}
