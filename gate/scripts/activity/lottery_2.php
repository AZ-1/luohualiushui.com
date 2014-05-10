<?php
require_once '../data/db.php';
function run(){
	$prize_arr = array(
		array('prize_id'=>1 , 'prize_chance'=>0, 'prize_num'=>50000 ,'activity_id'=>2),
		array('prize_id'=>2 , 'prize_chance'=>0 , 'prize_num'=>20000,'activity_id'=>2),
		array('prize_id'=>3 , 'prize_chance'=>0 , 'prize_num'=>10000,'activity_id'=>2),
	);
	init($prize_arr);	
}

function init($prize){
	insertPrize($prize);
}

function insertPrize($prize){
	$db = new db();
	foreach($prize as $v){
		$sql = "INSERT INTO `beauty_activity_prize_info` (`id`,`prize_id`,`prize_chance`,`prize_num`,`activity_id`) VALUES(NULL,'{$v['prize_id']}','{$v['prize_chance']}','{$v['prize_num']}','{$v['activity_id']}')";
		$db->execute($sql);
	}
}


run();
