<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBCosmeticUserHelper;
use Gate\Package\Helper\DBCosmeticCommentHelper;

class Comment{
	private static $instance;
	public static function getInstance(){
		is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
	}

}
