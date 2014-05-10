<?php

require_once 'db.php';

function run(){
	init();
}
function init(){
	
	// 用户文章统计
	getUserArticleCount();
	getUserArticleCheckCount();
	getUserArticleInCount();

	// 话题统计
	getTopicArticleCount();
	getTopicUserCount();

	// 标签的文章的统计
	getTagArticleCount();

	// 分类文章数据统计
	getCataArticleCount();
	getCataArticleCheckCount();
	getCataArticleInCount();

	// 用户粉丝，关注的统计
	getUserFollowCount();
	getUserFansCount();

	

}
/*
 *
// 未删除的文章总数
 */
function getUserArticleCount(){
	$sql = "select user_id from beauty_user_statistic";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $user){
		$user_id = $user['user_id'];
		$sql = "select count(*) as num from beauty_article  where is_delete=0 and  user_id = ".$user_id;
		$article_num = $db -> select($sql);
		$num = $article_num[0]['num'];
		$sql = "update beauty_user_statistic set article_num = ".$num." where user_id =".$user_id;
		$is = $db -> execute($sql);
		if($is){
			echo "{$user_id}\n";
		}
	}
}

/*
 * 
 * 未审核和已通过的文章总数
 */
function getUserArticleCheckCount(){
	$sql = "select user_id from beauty_user_statistic";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $user){
		$user_id = $user['user_id'];
		$sql = "select count(*) as num from beauty_article where is_delete=0 AND is_check!=1  and  user_id = ".$user_id;
		$article_num = $db -> select($sql);
		$num = $article_num[0]['num'];
		$sql = "update beauty_user_statistic set article_num_check = ".$num." where user_id =".$user_id;
		$is = $db -> execute($sql);
		if($is){
			echo "{$user_id}\n";
		}
	}
}

/*
 *
 * 审核通过的 文章总数
 */
function getUserArticleInCount(){
	$sql = "select user_id from beauty_user_statistic";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $user){
		$user_id = $user['user_id'];
		$sql = "select count(*) as num from beauty_article where is_delete=0 AND is_check=2 AND user_id = ".$user_id;
		$article_num = $db -> select($sql);
		$num = $article_num[0]['num'];
		$sql = "update beauty_user_statistic set article_num_in = ".$num." where user_id =".$user_id;
		$is = $db -> execute($sql);
		if($is){
			echo "{$user_id}\n";
		}
	}
}


/*
 * 话题的文章统计
 */
function getTopicArticleCount(){
	$sql = "select topic_id from beauty_topic";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $topic){
		$topic_id = $topic['topic_id'];
		$sql = "select count(*) as num from beauty_topic_article where topic_id = ".$topic_id;
		$article_num = $db -> select($sql);
		$num = $article_num[0]['num'];
		$sql = "update beauty_topic set article_num = ".$num." where topic_id =".$topic_id;
		$is = $db -> execute($sql);
		var_dump($is);
	}
}

/*
 * 话题关注的统计
 */
function getTopicUserCount(){
	$sql = "select topic_id  from beauty_topic";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $v){
		$id = $v['topic_id'];
		$sql = "select count(*) as num from beauty_topic_user where topic_id={$id}";
		$row = $db -> select($sql);
		$num = $row[0]['num'];
		$sql = "update beauty_topic set user_num = ".$num." where topic_id =".$id;
		$is = $db -> execute($sql);
		if($is){
			echo '话题的关注:'.$id . "\n";
		}
	}
}

// 未删除的文章总数
function getCataArticleCount(){
	$sql = "select id from beauty_category";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $cata){
		$cata_id = $cata['id'];
		$sql = "select count(*) as num from beauty_article where is_delete=0   and  category_id = ".$cata_id;
		$article_num = $db -> select($sql);
		$num = $article_num[0]['num'];
		$sql = "update beauty_category set article_num = ".$num." where id =".$cata_id;
		$is = $db -> execute($sql);
		var_dump($is);
	}
}

/*
 * 未审核和已通过的文章总数
 */
function getCataArticleCheckCount(){
	$sql = "select id from beauty_category";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $cata){
		$cata_id = $cata['id'];
		$sql = "select count(*) as num from beauty_article where is_delete=0 AND is_check!=1 and category_id = ".$cata_id;
		$article_num = $db -> select($sql);
		$num = $article_num[0]['num'];
		$sql = "update beauty_category set article_num_check = ".$num." where   id =".$cata_id;
		$is = $db -> execute($sql);
		var_dump($is);
	}
}


/*
 * 审核通过的 文章总数
 */
function getCataArticleInCount(){
	$sql = "select id from beauty_category";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $cata){
		$cata_id = $cata['id'];
		$sql = "select count(*) as num from beauty_article where is_delete=0 AND is_check=2  and   category_id = ".$cata_id;
		$article_num = $db -> select($sql);
		$num = $article_num[0]['num'];
		$sql = "update beauty_category set article_num_in = ".$num." where id =".$cata_id;
		$is = $db -> execute($sql);
		var_dump($is);
	}
}

/*
 * 标签的文章的统计
 */
function getTagArticleCount(){
	$sql = "select tag_id from beauty_tag";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $v){
		$tag_id = $v['tag_id'];
		$sql = "select count(*) as num from beauty_tag_article where tag_id={$tag_id}";
		$row = $db -> select($sql);
		$num = $row[0]['num'];
		$sql = "update beauty_tag set article_num_in = ".$num." where tag_id =".$tag_id;
		$is = $db -> execute($sql);
		if($is){
			echo '标签的文章的统计-审核通过:'.$tag_id . "\n";
		}
	}
}


/*
 * 关注的统计
 */
function getUserFollowCount(){
	$sql = "select user_id  from beauty_user_profile";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $v){
		$user_id = $v['user_id'];
		$sql = "select count(*) as num from beauty_follow where user_id={$user_id}";
		$row = $db -> select($sql);
		$num = $row[0]['num'];
		$sql = "update beauty_user_statistic set follow_num = ".$num." where user_id =".$user_id;
		$is = $db -> execute($sql);
		if($is){
			echo '关注:'.$user_id . "\n";
		}
	}
}

/*
 * 粉丝的统计
 */
function getUserFansCount(){
	$sql = "select user_id  from beauty_user_profile";
	$db = new db();
	$list = $db -> select($sql);
	foreach($list as $v){
		$user_id = $v['user_id'];
		$sql = "select count(*) as num from beauty_fans where user_id={$user_id}";
		$row = $db -> select($sql);
		$num = $row[0]['num'];
		$sql = "update beauty_user_statistic set fans_num = ".$num." where user_id =".$user_id;
		$is = $db -> execute($sql);
		if($is){
			echo '粉丝:'.$user_id . "\n";
		}
	}
}



/*
 * 喜欢的统计
 */

/*
 * 转发的统计
 */


run();
?>
