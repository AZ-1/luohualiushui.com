<?php
/*
 * 专题
 * @author xujiantao
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBSpecialHelper;
use Gate\Libs\Utilities;

class Special{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getSpecialContent($specialId){
		return DBSpecialHelper::getConn()->where('special_id=:id AND is_online=1', array('id'=>$specialId))->fetch();
	}
}
