<?php
require_once '../data/db.php';
function run(){
	init();	
}

function init(){
	$topicId = 34;
	$articleIds = array(
		22906,22899,22897,22873,22868,22855,22840,22837,22815,22808,22020,22802,22732,22787,22790,22797,22821,21916,22729,22762
	);
	changeTopicArticle($topicId,$articleIds);
}

function changeTopicArticle($topicId,$articleIds){
	$db = new db();	
	$sql = "SELECT article_id
		FROM `beauty_topic_article_vote`
		WHERE topic_id={$topicId} AND activity_id=1
		";	
	
	$res = $db->select($sql);	
	if($res != ''){
		foreach($res as $v){
			$oldArticleIds[] =  $v['article_id'];
		}
		$str = implode(',',$oldArticleIds);
		$sql = "
			DELETE FROM `beauty_topic_article_vote`
			WHERE article_id IN ({$str})
			";
		$res = $db->execute($sql);
	}
	foreach($articleIds as $v){
		$sql = "INSERT INTO `beauty_topic_article_vote` () VALUES ('',1,{$v},34,0) ";
		$db->execute($sql);
	}
}

run();
