<?php
namespace Gate\Libs;

use \Gate\Libs\Memcache;

class Session {

    private $user;
    private $mark;
    private $markvalue = NULL;
    private $memHandle = NULL;

    public static function Singleton() {
        static $single = NULL;
		is_null($single) && $single = new self();
        return $single;
    }

    private function __construct() {
		$this->mark = DEFAULT_COOKIE_NAME;
        $this->memHandle = Memcache::instance();
    }

    public function __get($field) {
        if (!empty($this->user))
            return $this->user->$field;
        return NULL;
    }

    public function set($user) {
        $this->user = $user;
    }

    public function load($COOKIE) {
        if (isset($COOKIE[$this->mark])) {

            $this->markvalue = $COOKIE[$this->mark];
            $value = $this->memHandle->get($this->markvalue);
			if( !$value){
				// 容错
				setcookie($this->mark, NULL, 0, DEFAULT_COOKIE_PATH, '.mei.hitao.com');
				setcookie($this->mark, NULL, 0, DEFAULT_COOKIE_PATH, '.lab.mei.hitao.com');
				setcookie($this->mark, NULL, 0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
			}
            $this->user = $value;
			if( !isset($value->id) && isset($value->user_id)){
				$this->user->id = $value->user_id;
			}
        }
        return $this;
    }

	/*
	 * 
	 */
    public function load_m($markvalue) {
        if (isset($COOKIE[$this->mark])) {
            $this->markvalue = $markvalue;
            $value = $this->memHandle->get($this->markvalue);
            $this->user = $value;
			if( !isset($value->id) && isset($value->user_id)){
				$this->user->id = $value->user_id;
			}
        }
        return $this;
    }

    private function store($user) {
        $this->memHandle->set($this->markvalue, $user);
    }

    public function reflash($user) {
        $this->store($user);
    }

	/*
	 * web登录
	 */
    public function marked($user) {
        $this->markvalue = \Gate\Libs\Utilities::getUniqueId();
        $this->store($user);
        //set cookie
        return setcookie($this->mark, $this->markvalue, time() + DEFAULT_COOKIE_EXPIRE, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN );
    }

	/*
	 */
	public function marked_m($user){
        $this->markvalue = \Gate\Libs\Utilities::getUniqueId();
        $this->store($user);
		return $this->markvalue;
	}

    public function destory() {
        $this->store(NULL);
        $this->user = NULL;
        setcookie($this->mark, NULL, 0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
		setcookie(HITAO_LOGIN_COOKIE, NULL, 0, DEFAULT_COOKIE_PATH, '.hitao.com');
		setcookie(HITAO_LOGIN_COOKIE_IS, NULL, 0, DEFAULT_COOKIE_PATH, SERVER_NAME);
    }

}
