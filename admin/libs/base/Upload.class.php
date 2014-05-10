<?php
namespace Gate\Libs\Base;
use Gate\Libs\Oss_php\ALIOSS;
use Gate\Libs\Base\Download;
/**
 * 上传
 * @author: KevinChen
 */
class Upload{
	/*
	 * 表单上传图片
	 * $FILES_name: 文件域的变量名称
	 * $saveDir:  保存地址完整路径
	 * $saveName: 保存文件名
	 */
	public static function uploadImage($FILES_name, $saveDir, $saveName){
		$limitWidth = null;
		$max_size = 1024*1024*2;	 // 2M
		$data['error']	= '';
		$data['path']	= '';
		$data['name']	= '';
		if(empty($_FILES)){
			$data['error'] = '未上传文件域';
			return $data;
		}
		if(!isset($_FILES[$FILES_name])){
			$data['error'] = '文件域名称不存在';
			return $data;
		}
		$file = $_FILES[$FILES_name];

		$error = $file['error'];
		if ($error != UPLOAD_ERR_OK) {
			if ($error == UPLOAD_ERR_NO_FILE) {
				$data['error'] = '没有文件被上传';
			} else if ($error == UPLOAD_ERR_INI_SIZE) {
				$data['error'] = '上传文件超过了服务器中设置的最大值';
			} else if ($error == UPLOAD_ERR_FORM_SIZE) {
				$data['error'] = '上传文件超过了表单中设置的最大值';
			} else if ($error == UPLOAD_ERR_PARTAL) {
				$data['error'] = '文件只上传了部分';
			} else {
				$data['error'] = "未知上传错误";
			}
			return $data['error'];
		} elseif ($file['size'] < 0 || empty($file['tmp_name'])) {
			$data['error'] = '格式有误';
		} elseif ($file['size'] > $max_size) {
			$data['error'] = '图太大了';
		//} elseif (!is_uploaded_file($file['tmp_name'])) {
		//	$data['error'] = '文件非post上传';
		//	learstatcache() 函数会缓存某些函数的返回信息，以便提供更高的性能。但是有时候，比如在一个脚本中多次检查同一个文件，而该文件在此脚本执行期间有被删除或修改的危险时，你需要清除文件状态缓存，以便获得正确的结果。要做到这一点，就需要使用 clearstatcache() 函数。
		} else {
			// 建立文件夹
			if (!file_exists($saveDir) && !mkdir($saveDir, 0755, true)) {
				$data['error'] = '文件夹保存失败';
				return $data;
			}
			$pathParts = pathinfo($file['name']);
			$saveName .= '.' . $pathParts['extension'];
			$savePath = $saveDir . '/' . $saveName;
			$ok = move_uploaded_file($file['tmp_name'], $savePath);
			if (!$ok) {
				$data['error'] = '文件保存失败';
				return $data;
			}

			/*
			// 图片宽限制
			if (!is_null($limitWidth)) {
				$size = getimagesize( $savePath);
				if($size[0]>$limitWidth){
					unlink($savePath);
					$data['error'] = '图片宽度必须小于900像素!';
					return $data;
				}
			}
			 */
			$data['path']	= $savePath;
			$data['name']	= $saveName;
		}
		return $data;
	}

	/*
	 * 上传到阿里云
	 */
	private static $oss_sdk_service;
	public static function ossServer($file_path, $dir=''){
		if($file_path==''){
			return false;
		}
		if(!self::$oss_sdk_service){
			self::$oss_sdk_service = new ALIOSS();
			//设置是否打开curl调试模式
			self::$oss_sdk_service->set_debug_mode(FALSE);
		}

		//try{
			$obj = self::$oss_sdk_service;
			$bucket		= 'hitaopic';
			$object		= $dir . basename($file_path);;
			
			// 锐化
			//$this->beautyImage($file_path);

			$response = $obj->upload_file_by_file($bucket,$object,$file_path);
			if(isset($response->header['x-oss-request-url'])){
				return $response->header['x-oss-request-url'];
			}
			return false;

			//print_r($response);
			//_format($response);
		//}

				
		//}catch (Exception $ex){
		//	die($ex->getMessage());
		//}

	}

	/*
	 * 表单提交图片到第三方
	 */
	public static function cloudFormImg($FILES_name, $saveDir, $saveName, $ossDir='mei/pic'){
		$imageInfo = self::uploadImage($FILES_name, $saveDir, $saveName);
		if($imageInfo['error']==''){
			$newFileUrl = self::ossServer($imageInfo['path'], $ossDir);
			if($newFileUrl){
				$imageInfo['url'] = $newFileUrl;
			}else{
				$imageInfo['error'] = '云存储失败，请稍后重试';
			}
			unlink($imageInfo['path']);
			unset($imageInfo['path']);
		}
		return $imageInfo;
	}

	/*
	 * 保存文件到第三方
	 * 支持 url 
	 */
	public static function cloudFile($file_path, $dir='mei/pic'){
		// url 先下载
		if(strpos($file_path,'http://')!==false || strpos($file_path, 'ftp://')!==false || strpos($file_path, 'https://')!==false){
			$fileInfo = pathinfo($file_path);
			$file_path = Download::image($file_path, ARTICLE_PIC_DIR.'/'.$fileInfo['basename']);
			if(!$file_path){
				return false;
			}
		}
		$ossRs = self::ossServer($file_path, $dir);
		$newFileUrl = self::ossServer($file_path, $dir);
		if($newFileUrl){
			unlink($file_path);
		}
		return $newFileUrl;
	}
}
