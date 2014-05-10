<?php
namespace Gate\Package\User;
use Gate\Helper\DBUserProfileHelper;

class Register{
	private static $instance;
	private $data = array();
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	

}
