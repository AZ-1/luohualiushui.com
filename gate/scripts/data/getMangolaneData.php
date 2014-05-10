<?php

require_once 'db.php';

/*
 * 抓取http://www.mangolane.net/ 衣服的数据
 * 抓取http://www.xinxianguang.com/index.php?app=home&mod=Tag&act=index&node_id=1&order=new 衣服的数据
 * 抓取http://soxihuan.com/services/service.php?m=share&a=book&cate=clothes&sort=hot1&page=1&pindex=3&_=1389594652771 衣服的数据
 * 抓取http://www.fandongxi.com/square.fan?n=clothing&page=1 衣服的数据
 * 抓取http://m.pclady.com.cn/goods_list_or/tt5_6_9_10_11/t/ 衣服的数据
 */

function run(){
	var_dump(getMangolaneData());
	//getSoxihuanData();
	//getXinxiangguangData();
	//getFandongxiData();
	//getPcladyData();
}

function insertMysql($ids,$source){
	$i = 0;
	$db = new db();
	foreach($ids as $id){
		$sql = "select id form item_source where num_iid = $id;";
		$res = $db->select($sql);
		if(!$res){
			$sql = "insert into item_source('num_iid,source') values($id,$source);";
			$isIn = $db -> execute($sql);
			if($isIn){
				echo "新添加一个商品ID,ID为$id\n";
				$i++;
			}
		}
	}
	echo "一共添加了$i个商品ID\n";
}

function getPcladyData(){
	$ids		= array();
	$i = 1;
	while(1){
		$url		= "http://m.pclady.com.cn/goods_list_or/tt5_6_9_10_11/t/?pageNo=$i";
		echo "正在抓取$url\n";
		$output		= curlPost($url);
		preg_match_all('/<p class="pPic">.*<a.*href="(.*)"/Uis',$output,$links);
		if(empty($links[1])){
			echo "抓取完成\n";
			return $ids;
		}
		foreach($links[1] as $item){
			$output = curlPost($item);	
			preg_match('/<p class="pTao">.*<a data-itemid="(.*)" /Uis' , $output , $href);
			$ids[] = $href[1];
		}
		$i++;
	}
}

function getFandongxiData(){
	$ids		= array();
	$i = 1;
	while(1){
		$url		= "http://www.fandongxi.com/square.fan?n=clothing&page=$i";
		echo "正在抓取$url\n";
		$output		= curlPost($url);
		preg_match_all('/<div class="mbs pic_item".*>.*<a.*href="(.*)"/Uis',$output,$links);
		if(empty($links[1])){
			echo "抓取完成\n";
			return $ids;
		}
		foreach($links[1] as $item){
			$output = curlPost($item,$post='', $autoFollow=1);	
			var_dump($output);die();
			preg_match('/<span class="compLike" data-item="(.*)" /Uis' , $output , $href);
			var_dump($href);
			$ids[] = $href[1];
		}
		$i++;
	}
}

function getSoxihuanData(){
	$ids		= array();
	$i = 1;
	while(1){
		$serves		= "http://soxihuan.com";
		$ajax		= "/services/service.php?m=share&a=book&cate=clothes&sort=hot1&page=$i&pindex=3&_=1389594652771";
		$url		= $serves.$ajax;
		echo "正在抓取$url\n";
		$output		= curlPost($url);
		preg_match_all('/<li>.*<a.*href="(.*)" /Uis',$output,$links);
		if(empty($links[1])){
			echo "抓取完成\n";
			return $ids;
		}
		foreach($links[1] as $item){
			$output = curlPost($serves.$item);	
			preg_match('/<div class="shop_info fl">.*<a href=.*id=(.*)&/Uis' , $output , $href);
			$ids[] = $href[1];
		}
		$i++;
	}
}

function getXinxiangguangData(){
	$ids		= array();
	$i = 1;
	while(1){
		$url		= "http://www.xinxianguang.com/index.php?app=home&mod=Tag&act=index&node_id=1&order=new&p=$i";
		echo "正在抓取$url\n";
		$output		= curlPost($url);
		preg_match_all('/class="ipic"><a href=(.*) /Uis',$output,$links);
		if(empty($links[1])){
			echo "抓取完成\n";
			return $ids;
		}
		foreach($links[1] as $item){
			$output = curlPost($item);	
			preg_match('/<p class="buy">.*<a.* href=.*id=(.*)" /Uis' , $output , $href);
			$ids[] = $href[1];
		}
		$i++;
	}
}

function getMangolaneData(){
	$ids		= array();
	$i = 1;
	while(1){
		$url		= "http://www.mangolane.net/tag/1?&p=$i";
		echo "正在抓取$url\n";
		$output		= curlPost($url);
		preg_match_all('/class="ipic"><a href=(.*) /Uis',$output,$links);
		if(empty($links[1])){
			echo "抓取完成\n";
			return $ids;
		}
		foreach($links[1] as $item){
			$output = curlPost($item);	
			preg_match('/<p class="buy">.*<a.* href=.*id=(.*)" /Uis' , $output , $href);
			$ids[] = $href[1];
		}
		var_dump($ids);
		$i++;
	}
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
