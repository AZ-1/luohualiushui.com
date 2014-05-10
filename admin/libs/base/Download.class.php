<?php
namespace Gate\Libs\Base;
use Gate\Libs\Base\Curl;
/**
 * 下载
 */
class Download{
	/*
	 * saveName: 完整路径+文件名
	 */
	 public static function image($url, $saveName){
		$curl = new Curl();
		$curlHead = $curl->head($url);
		if($curlHead['http_code']!=200){
			return false;
		}
		$content = $curl->get($url);
		//$content = file_get_contents($url);

		if(!$content){
			return false;
		}
		$pathParts = pathinfo($url);
		$saveInfo = pathinfo($saveName);// 图片上传： 检测后缀
		if(!isset($saveInfo['extension']) || $saveInfo['extension']==''){

			if(isset($pathParts['extension']) && $pathParts['extension']!=''){
				$saveName .= '.'.$pathParts['extension'];
			}else{
				$saveName .= '.jpg';
			}
		}
		$isSave = file_put_contents($saveName, $content);
		if($isSave){
			return $saveName;
		}
		return false;

/*

		$ip = '192.168.11.10';
		$refer = '';

        $ch = curl_init();
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/20100101 Firefox/17.0 FirePHP/0.7.1';
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'. $ip, 'CLIENT-IP:'.$ip));  //构造IP
        curl_setopt($ch, CURLOPT_REFERER, $refer);   //构造来路
		//
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
 */
		
	}
}
