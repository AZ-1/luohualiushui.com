<?php

require_once 'db.php';

function run(){
	init();
}
function init(){
	getUserPrizeByUserId();

}


function getUserPrizeByUserId(){
	$sql = "SELECT id , mobile , add_date
		FROM beauty_activity_groupon
		";
	$db = new db();
	$list = $db->select($sql);
	foreach($list as $v){
		$file = fopen('./groupon.csv','a+');
		$col = "\n";
		fwrite($file,$v['id'].','.$v['mobile'].','.date('Y-m-d h:i:s').$col);
		fclose($file);
	}
}


run();
?>
