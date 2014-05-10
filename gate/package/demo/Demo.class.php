<?php
/*
 * Package 业务注释
 * @作者
 */
// 命名空间，每个Package文件都要写,首字母大写
namespace Gate\Package\Demo;

// 引用其他libs，package，插件等
// Helper里是package 需要的相关数据的访问接口,每个helper 对应一个数据源
use \Gate\Package\Helper\DBDemoHelper;


/*
 * package名称:和文件名相同,首字母大写
 */
class Demo{

	private static $instance = null;

	private $data = array();

	/*
	 * 单例模式访问本package 的接口
	 */
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/**
     * 查询page view
     */
	public function getDemoInfo(){

		// 单例模式访问数据库
		//$result = DBGateHelper::getConn()->field('id, name')->where('name=:tname AND id IN(:tid)', array('tname'=>$tname,'tid'=>$twitterId))->order('id DESC')->limit('10, 30')->fetchAll();
		$result = DBDemoHelper::getConn()->field('id, name , pwd')->order('id DESC')->limit('10, 30')->fetchAll();
		return $result;
	}
	

	
}
?>
