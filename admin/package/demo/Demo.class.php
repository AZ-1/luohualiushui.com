<?php
/*
 * Package 业务注释
 * @作者
 */
// 命名空间，每个Package文件都要写,首字母大写
namespace Gate\Package\Demo;

// 引用其他libs，package，插件等
use \Gate\Package\Helper\DBDolphinHelper;
use \Gate\Package\Helper\DBUserProfileHelper;
// Helper里是package 需要的相关数据的访问接口,每个helper 对应一个数据源
use \Gate\Package\Helper\MemcacheHelper;

/*
 * package名称:和文件名相同,首字母大写
 */
class Demo{

	private static $instance;

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
	public static function getDataInfo($twitter_id = 1){

		if (empty($twitter_id)) {
			return FALSE;
		}

        $cacheHelper = MemcacheHelper::instance();
		$cacheKey = "Demo:{$twitter_id}";
        $result = $cacheHelper->get($cacheKey);
		if (!empty($result)) {
			return $result;
		}

		$result = self::getDataInfoNoCache($twitter_id);

		if (!empty($result)) {
			$cache_time = 500;
			$cacheHelper->set($cacheKey, $result, $cache_time);
		}

		return $result;
	}

	/**
     * 查询page view
     */
	private static function getDataInfoNoCache($twitter_id = 1){

		if (empty($twitter_id)) {
			return FALSE;
		}

		$params = array(
			'_twitter_id' => $twitter_id,
		);
		
		$tname = 'Test';
		// 单例模式访问数据库
		$result = DBGateHelper::getConn()->field('id, name')->where('name=:tname AND id IN(:tid)', array('tname'=>$tname,'tid'=>$twitter_id))->order('id DESC')->limit('10, 30')->fetchAll();
		return $result;
	}
	

	private function db(){
		// 可调用一张表
		DBGateHelper::getConn()->from();

		// select 
		/*
		 fetch			返回单条
		 fetchCount		返回一个统计数字
		 fetchAll		返回多条数据 
		 fetchAssocAll	按第一列为键名
		 fetchCol		返回第一列
		*/
		 // 复杂sql语句用法
		 $sql		= "SELECT * FROM gate, user WHERE gate.id=user.id AND gate.name=:gateName";
		 $params	= array('gateName'=>'蜘蛛侠');
		 DBGateHelper::getConn()->fetchAll($sql, $params);
		

		// insert
		$data = array('id'=>1, 'name'=>'蜘蛛侠');
		DBGateHelper::getConn()->insert($data); //如果需要指定某个表，第二个参数填写表名
		DBGateHelper::getConn()->insertIgnore($data); // INSERT IGNORE INTO
		DBGateHelper::getConn()->insertUpdate($inData, $upData); // ON DUPLICATE KEY UPDATE

		// update
		$data	= array('name'=>'蜘蛛侠');
		$where	= 'id=:gid';
		$params = array('gid'=>$id);
		DBGateHelper::getConn()->update($data, $where, $params); //如果需要指定某个表，第四个参数填写表名


		// delete
		$where		= 'id=:gid';
		$params		= array('gid'=>$id);
		DBGateHelper::getConn()->delete($where, $params);  //如果需要指定某个表，第三个参数填写表名


		// 自增 +1
		DBGateHelper::getConn()->increment('num', array('id'=>5)); // id==5的行的字段num,自增1, 如果需要指定某个表，第三个参数填写表名

		// 自减 -1
		DBGateHelper::getConn()->decrement('num', array('id'=>5)); // 
	}

	/*
	 * 也可直接写sql语句
	 */
	public function getTableFields($table){
		$sql = "
			SELECT COLUMN_NAME, DATA_TYPE, COLUMN_COMMENT
			FROM information_schema.`COLUMNS`
			WHERE TABLE_SCHEMA = :database 
			AND TABLE_NAME = :table_name";

		return DBUserProfileHelper::getConn()->fetchAll($sql, array('database'=>DBUserProfileHelper::_DATABASE_, 'table_name'=>$table));  //查询方法的第一个参数是sql， 第二个参数是 sql内值
	}
}
