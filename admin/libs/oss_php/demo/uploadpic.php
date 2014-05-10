<?php
/**
 * 加载sdk包以及错误代码包
*/
require_once '../sdk.class.php';

// $oss_sdk_service = new ALIOSS("g2Cejn8kn0XozpdI","8MawMslJRVb2yAAP75pjeJ1RkfYTy5","hitao.oss.aliyuncs.com");
$oss_sdk_service = new ALIOSS();
//设置是否打开curl调试模式
$oss_sdk_service->set_debug_mode(FALSE);

/**
 * 测试程序
 */
try{
	/**
	 * Service相关操作
	 */
	get_service($oss_sdk_service);
	
	
	/**
	 * Bucket相关操作  --- 主目录
	 */
	
	/**
	 * Object相关操作
	 */
	
	
}catch (Exception $ex){
	die($ex->getMessage());
}
	

//获取bucket列表
function get_service($obj){
	$response = $obj->list_bucket();
	_format($response);
}

//通过路径上传文件
function upload_by_file($obj){
	$bucket = 'phpsdk1349849394';
	$object = 'netbeans-7.1.2-ml-cpp-linux.sh';
	$file_path = "D:\\TDDOWNLOAD\\netbeans-7.1.2-ml-cpp-linux.sh";

	$response = $obj->upload_file_by_file($bucket,$object,$file_path);
	_format($response);
}


//删除object
function delete_object($obj){
	$bucket = 'phpsdk1349849394';
	$object = 'myfoloder-1349850939/';
	$response = $obj->delete_object($bucket,$object);
	_format($response);
}
