<?php
require_once '../data/db.php';
function run(){
	$prize_arr = array(
		array('prize_id'=>1 , 'prize_chance'=>1 , 'prize_num'=>6 ,'activity_id'=>1),
		array('prize_id'=>2 , 'prize_chance'=>10 , 'prize_num'=>60 ,'activity_id'=>1),
		array('prize_id'=>3 , 'prize_chance'=>50 , 'prize_num'=>300 ,'activity_id'=>1),
		array('prize_id'=>4 , 'prize_chance'=>100 , 'prize_num'=>600 ,'activity_id'=>1),
		array('prize_id'=>5 , 'prize_chance'=>150 , 'prize_num'=>900 ,'activity_id'=>1),
		array('prize_id'=>6 , 'prize_chance'=>500 , 'prize_num'=>3000 ,'activity_id'=>1),
		array('prize_id'=>7 , 'prize_chance'=>1250 , 'prize_num'=>7500 ,'activity_id'=>1),
		array('prize_id'=>8 , 'prize_chance'=>2939 , 'prize_num'=>9999999 ,'activity_id'=>1),
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
