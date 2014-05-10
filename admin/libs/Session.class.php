<?php
namespace Gate\Libs;

use \Gate\Libs\Memcache;

class Session {

    private $user = NULL;
    private $mark = DEFAULT_COOKIE_NAME;
    private $markvalue = NULL;
    private $memHandle = NULL;

    public static function Singleton() {
        static $single = NULL;
        is_null($single) && $single = new self();
        return $single;
    }

    private function __construct() {
        $this->memHandle = Memcache::instance();
    }

    public function __get($field) {
        if (isset($this->user->$field))
            return $this->user->$field;
        return NULL;
    }

    public function set($user) {
        $this->user = $user;
    }

    public function load($COOKIE) {
        if (isset($COOKIE[$this->mark])) {
			// 容错，后期删除
			setcookie($this->mark, NULL, 0, DEFAULT_COOKIE_PATH, '.mei.hitao.com');

            $this->markvalue = $COOKIE[$this->mark];
            $value = $this->memHandle->get($this->markvalue);
            if(is_object($value)){
                $this->user = $value;
				if( !isset($value->id) && isset($value->user_id)){
					$this->user->id = $value->user_id;
				}
			}else{
				$this->destory();
			}
        }
        return $this->user;
    }

    private function store($user) {
        return $this->memHandle->set($this->markvalue, $user);
    }

    public function reflash($user) {
        $this->store($user);
    }

    public function marked($user) {
        $this->markvalue = \Gate\Libs\Utilities::getUniqueId();
        $is = $this->store($user);
		if($is){
			$is = setcookie($this->mark, $this->markvalue, time() + DEFAULT_COOKIE_EXPIRE, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN );
		}
        //set cookie
        return $is;
    }

    public function destory() {
        $this->store(NULL);
        $this->user = NULL;
        setcookie($this->mark, NULL, 0, DEFAULT_COOKIE_PATH, DEFAULT_COOKIE_DOMAIN);
    }

}
