<?php

require_once 'db.php';

function run(){
	init();
}
function init(){
	$num =	getUserPrizeByUserName();	
	getUserPrizeByUserId($num);

}


function getUserPrizeByUserId($num){
	$prize_arr = array('1'=>'1000元',
				'2'=>'100元',
				'3'=>'50元',
				'4'=>'10元',
				'5'=>'5元',
				'6'=>'2元',
				'7'=>'1元',
				'8'=>'0元'
				);
	$sql = "SELECT source_user_name,user_id,prize
		FROM beauty_activity_lottery
		WHERE activity_id=1 AND source_user_name=''
		GROUP BY user_id
		";
	$db = new db();
	$mid = $db->select($sql);
	foreach($mid as $v){
		$ids[] = $v['user_id'];
	}
	$id = implode(',',$ids);
	$sql = "SELECT user_id,prize,create_time
		FROM beauty_activity_lottery
		WHERE activity_id=1 AND source_user_name='' AND user_id IN($id)
		ORDER BY user_id
		";
	$list = $db->select($sql);
	$user_ids = array();
	foreach($list as $v){
		$user_ids[] = $v['user_id'];
	}
	$user_ids_str = implode(',',$user_ids);
	$sql = "SELECT realname,user_id
		FROM beauty_user_profile
		WHERE user_id IN($user_ids_str)
		";
	$user_info = $db->select($sql);
	$sql = "SELECT user_id,user_type
		FROM beauty_user_connect
		WHERE user_id IN($user_ids_str)
		";
	$user_con = $db->select($sql);
	$loginType = array('QQ_','Sina_','Taobao_');
	foreach($list as $k=>$v){
		$prize_list = explode(',',$v['prize']);
		$prize = array();
		foreach($prize_list as $vv){
			if($vv != ''){
				$prize[] = $prize_arr[$vv];
			}
		}
		$prize_str = implode(',',$prize);
		$list[$k]['prize'] = $prize_str;
		foreach($user_con as $uv){
			if($uv['user_id'] == $v['user_id']){
				foreach($user_info as $uvv){
					if($uvv['user_id']==$uv['user_id']){
						if(substr($uvv['realname'],-6,1)=='_'){
							$uvv['realname'] = substr($uvv['realname'],0,strlen($uvv['realname'])-6);
						}
						$list[$k]['source_user_name'] = $loginType[$uv['user_type']].$uvv['realname'];
					}
				}
			}
		}
		$file = fopen('./prizeList.csv','a+');
		$col = "\n";
		fwrite($file,$k+$num.','.$list[$k]['source_user_name'].','.$list[$k]['prize'].','.$list[$k]['create_time'].$col);
		fclose($file);
	}
}

function getUserPrizeByUserName(){
	$prize_arr = array('1'=>'1000元',
				'2'=>'100元',
				'3'=>'50元',
				'4'=>'10元',
				'5'=>'5元',
				'6'=>'2元',
				'7'=>'1元',
				'8'=>'0元'
				);

	$sql = "SELECT source_user_name
		FROM beauty_activity_lottery
		WHERE activity_id=1 AND source_user_name!=''
		GROUP BY source_user_name
		";
	$db = new db();
	$mid = $db->select($sql);
	$user_names = array();
	foreach($mid as $v){
		$user_names[] = $v['source_user_name'];
	}
	$user_name = implode("','",$user_names);
	$sql = "SELECT source_user_name,prize,create_time
		FROM beauty_activity_lottery
		WHERE activity_id=1 AND source_user_name IN('$user_name') AND source_user_name!=''
		ORDER BY source_user_name
		";
	$list = $db->select($sql);
	foreach($list as $k=>$v){
		$prize_list = explode(',',$v['prize']);
		$prize = array();
		foreach($prize_list as $vv){
			if($vv != ''){
				$prize[] = $prize_arr[$vv];
			}
		}
		$prize_str = implode(',',$prize);
		$list[$k]['prize'] = $prize_str;
		$file = fopen('./prizeList.csv','a+');
		$col = "\n";
		fwrite($file,$k.','.$list[$k]['source_user_name'].','.$list[$k]['prize'].','.$v['create_time'].$col);
		fclose($file);
		$res = $k;
	}
	return $k;

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
