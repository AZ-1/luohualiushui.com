<?php
//$uriMapping = array(
//	'/u\/(/d+)/'		=> array('/person/index','aid=:1'),
//	'/u/[:1]/fav'	=> array('/article/index','aid=:1'),
//);

	$uri = $_SERVER['REQUEST_URI'];

	$rules = array(
		'#^/u/(\d+)/?$#'                     =>   '/home/index/type/1/uid/:1',
		'#^/u/(\d+)/pageNum/(\d+)?$#'		 =>   '/home/index/type/1/uid/:1/pageNum/:2',

		//我的喜欢
		'#^/u/(\d+)/fav/?$#'                 =>   '/home/Like_article',
		'#^/u/(\d+)/fav/pageNum/(\d+)?$#'    =>   '/home/Like_article/pageNum/:2',

		//我的话题	
		'#^/u/(\d+)/topic/?$#'               =>   '/home/topic',
		'#^/u/(\d+)/topic/pageNum/(\d+)?$'   =>   '/home/topic/pageNum:2',

		//我的粉丝
		'#^/u/(\d+)/fans/?$#'       =>   '/home/fans',

		//我的喜欢
		'#^/u/(\d+)/follow/?$#'     =>   '/home/follow',
		
		'#^/u/(\d+)/review/?$#'     =>   '/home/message',
		
		'#^/u/(\d+)/ifav/?$#'       =>   '/home/favourite'
		
		//'/^blog\/(\d+)\/(\d+)$/' => 'Blog/achive?year=:1&month=:2',
		//'/^blog\/(\d+)_(\d+)$/'  => 'blog.php?id=:1&page=:2',
	);

	$realUrl = '';
	foreach($rules as $key=>$val){
		$isPreg = preg_match($key, $uri, $arr);
		if($isPreg){
			unset($arr[0]);
			foreach($arr as $k=>$v){
				$realUrl = str_replace(':'. $k, $v, $val);
			}
			break;
		}
	}
	return $realUrl;
?>
