<?php

require_once 'db.php';
require_once 'qiniuService.php';
/*
 *	抓取百度Android应用
 */
function run(){
	getPage();
}
function getPage(){
	/*
	 * 1 - 系统安全 2 - 壁纸美化 3 - 聊天通讯 4 - 生活实用 5 - 书籍阅读 6 - 学习办公 7 - 其它软件
	 * 1 - 休闲益智 2 - 角色冒险 3 - 动作格斗 4 - 策略游戏 5 - 飞行射击 6 - 体育竞速 7 - 其它游戏
	 * cid:软件分类 s:软件排序 1=最受欢迎 2=更新时间 3=用户评分
	 */
	for($j=2;$j<=2;$j++){
			if($j == 1){
				$cid = array(
					'501' => 1,
					'502' => 2,
					'503' => 3,
					'504' => 4,
					'505' => 5,
					'507' => 6,
					'500' => 7,
					'506' => 8, //影音图像
					'508' => 9, //网络社区
					'509' => 10, //地图导航
					'510' => 11, //理财购物
						
				);
			}else{
				$cid = array(
					'401' => 1,
					'402' => 2,
					'403' => 3,
					'404' => 4,
					'406' => 5,
					'405' => 6,
					'400' => 7,
					'407' => 8, //卡片棋牌
					'408' => 9, //经营养成	
				);
			}
			foreach($cid as $k => $v){
			for($i=0;$i<=67;$i++){
				if($j == 1){
					$url = 'http://as.baidu.com/a/software?cid='.$k.'&pn='.$i.'&s=3';
				}else{
					$url = 'http://as.baidu.com/a/asgame?cid='.$k.'&pn='.$i.'&s=1';
				}
				$output = curlPost($url); 
				preg_match_all('/class="hover-link" href="(.*)"\>/Uis' , $output , $links);
				foreach($links[1] as $item){
					$output = curlPost($item);
					preg_match('/class="info-top"(.*)id="app-logo"/Uis' , $output , $appiconurlhtml);
					preg_match('/src="(.*)"/Uis' , $appiconurlhtml[1] , $appiconurl);
					preg_match('/id="appname" .*\>(.*)\<\/span/Uis' , $output , $appname);
					preg_match('/class="params-size"\>(.*)\<\/span/Uis' , $output , $appsize);
//					preg_match('/id="down_as_durl"(.*)\<\/td\>/Uis' , $output , $appurlhtml);
					preg_match('/name="durl".*value="(.*)"\/\>/Uis' , $output , $appurl);
//					preg_match('/id=href="(.*)"/Uis' , $appurlhtml[1] , $appurl);
					preg_match('/class="brief-des" style="display:none;"\>(.*)\<\/div\>/Uis' , $output , $appdes);
					preg_match('/class="params-download-num"\>(.*)\<\/span\>/Uis' , $output , $appdowncount);
					preg_match('/class="screen cls data-screenshots" .*\>(.*)\<\/ul\>/Uis' , $output , $appimgurlhtml);
					preg_match_all('/src="(.*)"/Uis' , $appimgurlhtml[1] , $appimgurl);
					preg_match('/name="pname" value="(.*)"\/\>/Uis' , $output , $apppkgname);
					preg_match('/name="vname" value="(.*)"\/\>/Uis' , $output , $version);
					preg_match('/name="updatetime" value="(.*)"\/\>/Uis' , $output , $updatetime);

					$apppkgname = $apppkgname[1];
					$version = $version[1];
					$updatetime = $updatetime[1];
					$appiconurl = $appiconurl[1];
					$appname = $appname[1];
					if(strpos($appsize[1] , 'MB')){
						$appsize = str_replace('MB' , '' , $appsize[1]);
						$appsize = (int)$appsize * 1024 * 1024;
					}
					if(strpos($appsize[1] , 'KB')){
						$appsize = str_replace('KB' , '' , $appsize[1]);
						$appsize = (int)$appsize * 1024; 
					}
					if(strpos($appsize[1] , 'GB')){
						$appsize = str_replace('GB' , '' , $appsize[1]);
						$appsize = (int)$appsize * 1024 * 1024 * 1024;
					}
					$apptype1 = $j;
					$apptype2 = $v;
					$appurlhtml = $appurlhtml[1];
					$appurl = $appurl[1];
					$appdes = strip_tags($appdes[1]);
					$appdes = str_replace('收起' , '' , $appdes);
					$appdowncount = $appdowncount[1];
					$appdowncount = str_replace('+' , '' , $appdowncount);
					$appdowncount = str_replace('万' , '0000' , $appdowncount);
					$appdowncount = str_replace('千' , '000' , $appdowncount);
					if($appdowncount >= 10000){
						$appdowncount = ceil($appdowncount / 100);
						$downcountalias = $appdowncount;
						$appdowncount = ceil($appdowncount / 10000) . '万';
					}else{
					    $appdowncount = $appdowncount;
						$downcountalias = $appdowncount;
					}
					$appimgurl = implode(',' , $appimgurl[1]);
					$pic_name = pathinfo($appiconurl);
					$pic_name = $pic_name['basename'];
					//$res = strpos($appurl , '.apk');
					if($appurl != ""){
						$db = new db();
						$sql = "select idx from apkgame where apppkgname = '$apppkgname'";
						$isHas = $db -> select($sql);
						if(empty($isHas)){
							$table = 'apkdatas';
							$path = '/home/appmarket/public/appstore/icon/';
	/*						$name = pathinfo($appurl);
							$name = $name['basename'];
							exec('wget -O /home/appmarket/public/appstore/apk/'. $name . ' ' . $appurl);
							unset($out);
							exec('aapt d badging /home/appmarket/public/appstore/apk/'.$name , $out , $status);
							preg_match("/name='(.*)'/Uis" , $out[0] , $apppkgname);	
							$apppkgname = $apppkgname[1];
							if($status == 1 || empty($apppkgname)){
								continue;
							}

							unlink('/home/appmarket/public/appstore/apk/'. $name);*/	
							$dir = getPath($path);
							exec('wget -O '.$path.$dir.$pic_name .' ' . $appiconurl);
							$qiniu = new QiniuService();
							$qiniu -> uploadFile('d9game' , $pic_name , $path.$dir.$pic_name);
							$data = array(
								'appname'      => $appname,
								'appiconurl'   => 'http://d9game.u.qiniudn.com/'.$pic_name,
								'appsize'      => $appsize,
								'apptype1'     => $apptype1,
								'apptype2' 	   => $apptype2,
								'appurl' 	   => $appurl,
								'appdes' 	   => $appdes,
								'appdowncount' => $appdowncount,
								'appimgurl'	   => $appimgurl,
								'pageurl'	   => $item,
								'apppkgname'   => $apppkgname,
								'version'	   => $version,
								'updatetime'   => $updatetime,
								'downcountalias' => $downcountalias
							);
							$data = array_map('mysql_escape_string', $data);
							$strFields = implode(',', array_keys($data));
							$strval = "'".implode("','" , $data). "'";
							$sql = "insert into apkgame ($strFields) values($strval)";
							$isIn = $db -> execute($sql);
							if($isIn){
							//	echo $url."\n";
								//$url = mysql_escape_string($url);
								//$sql = "insert IGNORE into getDataError (last_url) values('".$url."')";
								//$isIn = mysql_query($sql);

							}else{
								$url = mysql_escape_string($url);
								$sql = "insert into getDataError (error_url) values('".$url."')";
								$isIn = mysql_query($sql);
							}	
						}else{
							$idx = $isHas[0]['idx'];
							$sql = "update apkgame set appurl='$appurl' , updatetime='$updatetime' where idx='$idx'";	
							$db -> execute($sql);
							echo "更新数据！" . 'id = ' . $idx . "\n";
						}
					}
				}
			}
		}
	}
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


run();
?>
