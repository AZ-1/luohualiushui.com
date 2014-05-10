<?php
require_once '../data/db.php';
function run(){
	init();	
}

function init(){
	getCommentList();
}

function getCommentList(){
	$file = fopen('./comment.csv','r');
	$comment = fgetcsv($file);
	while(!empty($comment)){
		$commentList[] = $comment[1];
		$comment = fgetcsv($file);
	}
	$db = new db();
	foreach($commentList as $v){
		$sql = "INSERT INTO `beauty_tmp_comment` (comment) VALUES('$v')";
		$db->execute($sql);
	}
}

run();
