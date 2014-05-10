<?
$i = 1;
while(1){
	$url = "http://game.hiapk.com/classified/list_144_".$i.".html";
	if(!getPage($url)){
		exit;
	}
	$i++;
}
function getPage($url){
	$output = curlPost($url);
	if($output == null || $output == "" || $output == false){
		return false;
	}
	preg_match_all('/<div class="recimg">.*<a target="_blank" href="(.*)">.*<div class="detail clearfix">.*<img.* alt="(.*)" src="(.*)"/Uis',$output,$href);
	//var_dump($href);
	foreach($href[1] as $item=>$url){
		$output = curlPost($url,"",1);
		preg_match('/<div class="article_con">.*<\/div>/Uis',$output,$articleAll);
		//var_dump($articleAll);
		$str=preg_replace("/<(\/?img.*?)>/si","",$articleAll[0]); //过滤img标签 
		$str=preg_replace("/<(\/?a.*?)>/si","",$str); //过滤img标签 
		$str=preg_replace("/<(\/?div.*?)>/si","",$str); //过滤div标签 
		$str=preg_replace("/(<p>?\s*<strong>.*<\/strong>.*<\/p>)/Usi","",$str);
		$str=preg_replace("/(<p style=\"text-align: center;.*\">.*<\/p>)/Usi","",$str);//描述
		preg_match_all('/<p>.*<\/p>/Uis',$articleAll[0],$p);//匹配所有没有内嵌样式p标签里的内容
		preg_match('/<strong>.*大小.*<\/strong>(.*)<\/p>/Uis',$articleAll[0],$fileSize);
		preg_match('/<strong>.*适用固件.*<\/strong>(.*)<\/p>/Uis',$articleAll[0],$phoneVersion);
		preg_match('/<strong>.*版本.*<\/strong>(.*)<\/p>/Uis',$articleAll[0],$apkVersion);
		if(empty($fileSize[1]) || $fileSize[1] == NULL || $fileSize[1] == ""){
			preg_match('/<strong>.*大小：(.*)<\/strong><\/p>/Uis',$articleAll[0],$fileSize);
		}
		if(empty($phoneVersion[1]) || $phoneVersion[1] == NULL || $phoneVersion[1] == ""){
			preg_match('/<strong>.*适用固件：(.*)<\/strong><\/p>/Uis',$articleAll[0],$phoneVersion);
		}
		if(empty($apkVersion[1]) || $apkVersion[1] == NULL || $apkVersion[1] == ""){
			preg_match('/<strong>.*版本：(.*)<\/strong><\/p>/Uis',$articleAll[0],$apkVersion);
		}
		preg_match_all('/<a.*>/Uis',$articleAll[0],$a);//匹配所有a标签	

		preg_match('/<p style=".*">\W.*<a href="(.*)"/Uis',$output,$apkHref);//$apk_href[0][1]
		$apk = curlPost($apkHref,"",1);
		//exec('wget '.$apk_href);
		preg_match_all('/<p style="text-align: center;.*">\W.*<img.* src="(.*)"/Uis',$output,$img);//所有img图片  $img[1][*]
		echo "\n";
		echo $href[2][$item];
		echo $str;
		var_dump($fileSize[1]);
		var_dump($phoneVersion[1]);
		var_dump($apkVersion[1]);
		var_dump($apkHref[1]);
		var_dump($img[1]);

	}
	return true;

}
function getPath($root){
    $rt = mt_rand(0,255);
    $path = dechex($rt);
    $path .= '/';
    $rt = mt_rand(0,255);
    $path .= dechex($rt);
    $root .= $path;
    if( !file_exists($root)){
        if(!mkdir($root, 0777, true)){
            return false;
        }
    }
    return $path . '/';
}
/*
 * curl 方式抓取
 */
function curlPost($url, $post='', $autoFollow=0){
	$ch = curl_init();
	$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/20100101 Firefox/17.0 FirePHP/0.7.1';
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	// 2. 设置选项，包括URL
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:192.168.2.11', 'CLIENT-IP:192.168.2.11'));  //构造IP
	curl_setopt($ch, CURLOPT_REFERER, "http://www.gosoa.com.cn/");   //构造来路
	if($autoFollow){
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  //启动跳转链接
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);  //多级自动跳转
		$res = curl_getinfo($ch , CURLINFO_EFFECTIVE_URL);
		return $res;
	}
	//
	if($post!=''){
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	// 3. 执行并获取HTML文档内容
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
?>
