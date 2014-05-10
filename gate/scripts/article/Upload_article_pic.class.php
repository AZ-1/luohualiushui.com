<?php
/**
 *
 * */
namespace Gate\Scripts\Article;
use Gate\Libs\Oss_php\ALIOSS;
/*
$base = __DIR__;
include($base .'/script_base.php');
include($base .'/ImageImagick.class.php');
include($base .'/../oss_php/sdk.class.php');
 */

class Upload_article_pic extends \Gate\Libs\Scripts{
	private $oss_sdk_service;
	public function __construct(){
		//header("Content-type: text/html; charset=utf-8");
		$this->init();
	}
	
	public function init(){
		$this->oss_sdk_service = new ALIOSS();
		//设置是否打开curl调试模式
		$this->oss_sdk_service->set_debug_mode(FALSE);
		//设置开启三级域名，三级域名需要注意，域名不支持一些特殊符号，所以在创建bucket的时候若想使用三级域名，最好不要使用特殊字符
		//$this->oss_sdk_service->set_enable_domain_style(TRUE);
	}

	public function run(){
		echo "----------- pic  开始上传  -----  \n";
		$length = 1000;
		
		try{
			$show_pic = '/home/work/mei.hitao.com/data/article/pic/edbe6f5dd89d8b172b8e7262559faf77.jpg';
			$newUrl = $this->upload_by_file($this->oss_sdk_service, $show_pic);
			var_dump($newUrl);
			
		}catch (Exception $ex){
			die($ex->getMessage());
		}
		echo "----------- pic  上传完毕  -----  \n";
		echo "---------------------------------------------\n";

	}


	private function saveGoods($goodsId, $url){
		$sql="UPDATE goods SET show_pic='{$url}' WHERE goods_id = {$goodsId}";
		return $this->execute($sql);
	}

	private function delTmpGoods($id){
		$sql="DELETE FROM tmp_goods_pic WHERE goods_id = {$id}";
		return $this->execute($sql);
	}

	/*
	 * 数据
	 */
	private function getGoods($length){
		$sql="SELECT goods_id FROM tmp_goods_pic LIMIT {$length}";
		$tglist = $this->fetchAll($sql);
		if(empty($tglist)){
			return array();
		}
		$ids = array();
		foreach($tglist as $v){
			$ids[] = $v->goods_id;
		}
		$strIds = implode(',', $ids);
		$sql="SELECT goods_id,show_pic  FROM goods WHERE goods_id IN({$strIds}) AND show_pic!=''";
		$glist = $this->fetchAll($sql);

		return $glist;
	}

	//获取bucket列表
	function get_service($obj){
		$response = $obj->list_bucket();
		$this->_format($response);
	}

	//创建bucket
	function create_bucket($obj){
		$bucket = 'hitaopic';
		$acl = ALIOSS::OSS_ACL_TYPE_PRIVATE;
		//  $acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ;
		//$acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ_WRITE;

		$response = $obj->create_bucket($bucket,$acl);
		$this->_format($response);
	}


	/*
	 * 通过路径上传文件
	 */
	function upload_by_file($obj, $pic_url){
		if($pic_url==''){
			return false;
		}
		$urlInfo	= parse_url($pic_url);
		$bucket		= 'hitaopic';
		$object		= 'test.jpg';
		$file_path  = __DIR__ . '/../../public/' . $urlInfo['path'];
		$file_path = $pic_url;
		
		// 锐化
		//$this->beautyImage($file_path);

		$response = $obj->upload_file_by_file($bucket,$object,$file_path);

			//$this->get_object($this->oss_sdk_service, $object);

		if(isset($response->header['x-oss-request-url'])){
			return $response->header['x-oss-request-url'];
		}else{
			return false;
		}

		//print_r($response);
		//_format($response);
	}


	//通过multi-part上传整个目录(新版)
	function batch_upload_file($obj){
		$options = array(
			'bucket'    => 'hitaopic',
			'object'    => 'picture',
			'directory' => 'D:\alidata\www\logs\aliyun.com\oss',
		);
		$response = $obj->batch_upload_file($options);
	}

	//格式化返回结果
	function _format($response) {
		echo '|-----------------------Start---------------------------------------------------------------------------------------------------'."\n";
		echo '|-Status:' . $response->status . "<br/>";
		echo '|-Body:' ."<br/>"; 
		echo $response->body . "<br/>";
		echo "|-Header:<br/>";
		print_r ( $response->header );
		echo '-----------------------End-----------------------------------------------------------------------------------------------------'."\n\n";
	}

	/*
	 * 锐化
	 */
	public function beautyImage($pic_url){
		$ximage = new ImageImagick();
		$ximage->load($pic_url);
		$ximage->sharpen(2);
		$isSave = $ximage->saveTo($pic_url);
		return $isSave;
	}
		
	//获取object
	function get_object($obj, $object){
		$bucket = 'hitaopic';
		
		$options = array(
			ALIOSS::OSS_FILE_DOWNLOAD => "tt.jpg",
			//ALIOSS::OSS_CONTENT_TYPE => 'txt/html',
		);	
		
		$response = $obj->get_object($bucket,$object,$options);
		$this->_format($response);
	}

}
