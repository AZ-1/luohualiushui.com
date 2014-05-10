<?php
/*
 *	抽奖 
 * @author
 */
namespace Gate\Package\Activity;
use Gate\Package\User\Userinfo;
use Gate\Package\Helper\DBActivityLotteryHelper;
use Gate\Package\Helper\DBActivityPrizeInfoHelper;
use Gate\Package\Helper\DBActivityGrouponHelper;

class Prize{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getGrouponNum(){
		return DBActivityGrouponHelper::getConn()->fetchCount();
	}

	public function getUserFavour($user_name,$favour_id){
		$res = DBActivityLotteryHelper::getConn()->where('source_user_name=:user_name AND activity_id=2',array('user_name'=>$user_name))->fetch();
		if(!$res){
			$data = array(
				'source_user_name'=>$user_name,
				'user_id'=>0,
				'lottery_times'=>0,
				'prize'=>$favour_id,
				'activity_id'=>2
			);
			$r =  DBActivityLotteryHelper::getConn()->insert($data);
			if($r){
				$num = DBActivityPrizeInfoHelper::getConn()->field('prize_num')->where('prize_id=:prize_id AND activity_id=2',array('prize_id'=>$favour_id))->fetch();
				$num = $num->prize_num - 1;
				DBActivityPrizeInfoHelper::getConn()->update(array('prize_num'=>$num),'prize_id=:prize_id AND activity_id=2',array('prize_id'=>$favour_id));
			}
			return $r;
		}
		$prizeList = explode(',',$res->prize);
		if(in_array($favour_id,$prizeList)){
			return false;
		}
		$prizeList[] = $favour_id;
		$prize = implode(',' , $prizeList);
		$r = DBActivityLotteryHelper::getConn()->update(array('prize'=>$prize),'source_user_name=:user_name AND activity_id=2' ,array('user_name'=>$user_name) );
		if($r){
			$num = DBActivityPrizeInfoHelper::getConn()->field('prize_num')->where('prize_id=:prize_id AND activity_id=2',array('prize_id'=>$favour_id))->fetch();
			$num = $num->prize_num - 1;
			DBActivityPrizeInfoHelper::getConn()->update(array('prize_num'=>$num),'prize_id=:prize_id AND activity_id=2',array('prize_id'=>$favour_id));
		}
		return $r;
	}

	public function getUserLotteryTimes($user_name){
		$list = DBActivityLotteryHelper::getConn()->where('source_user_name=:user_name AND activity_id=1', array('user_name'=>$user_name))->fetchAll();
		if(empty($list)){
			return $this->setUserLotteryTimes($user_name);
		}

		$pre_time =	$list[count($list)-1]->create_time;
		$pre_m = substr($pre_time , 5 , 2);
		$pre_d = substr($pre_time , 8 , 2);
		$now_m = date('m',time());
		$now_d = date('d',time());
		if($now_m>$pre_m || $now_d > $pre_d){
			return false;
		}
		if($list){
			return $list[count($list)-1];
		}
	}

	public function setUserLotteryTimes($user_name){
		$source_name = '';
		if(isset($_COOKIE['_HL'])){
			$source_name = $_COOKIE['_HL'];
		}
		return DBActivityLotteryHelper::getConn()->insert(array('source_user_name'=>$user_name , 'lottery_times'=> 10 , 'prize'=>'' ,'source_user_name'=>$source_name));
	}

	public function getUserPrize($user_name , $prize , $id){
		$prize_info = DBActivityLotteryHelper::getConn()->field('lottery_times , prize')->where('source_user_name like :user_name AND activity_id=1' , array('user_name'=>$user_name))->fetchAll();
		$prize_info = $prize_info[count($prize_info)-1];
		if($prize_info->lottery_times == 0){
			return false;
		}
		$old_prize = $prize_info->prize;
		$prize_arr = explode(',' , $old_prize);
		$prize_arr[] = $prize;
		$prize_list = implode(',' , $prize_arr);
		$times = $prize_info->lottery_times - 1;
		$where = 'source_user_name=:user_name AND id = :id AND activity_id=1' ;
		$params =  array('user_name'=>$user_name , 'id'=>$id);
		$res =  DBActivityLotteryHelper::getConn()->update(array('lottery_times'=>$times , 'prize'=>$prize_list) , $where ,$params);
		if($res && $prize != 8){
			$num = DBActivityPrizeInfoHelper::getConn()->field('prize_num')->where('prize_id=:prize_id AND activity_id=1',array('prize_id'=>$prize))->fetch();
			$num = $num->prize_num - 1;
			DBActivityPrizeInfoHelper::getConn()->update(array('prize_num'=>$num),'prize_id=:prize_id AND activity_id=1',array('prize_id'=>$prize));
		}
		return $res;
	}

	public function getPrizeList($activity_id){
		return DBActivityPrizeInfoHelper::getConn()->where('activity_id = :activity_id' , array('activity_id'=>$activity_id))->fetchAll();
	}

	public function getUserPrizeList($user_name){
		return DBActivityLotteryHelper::getConn()->where('activity_id=2 AND source_user_name=:user_name',array('user_name'=>$user_name))->fetch();
	}
	
	public function getUpdatePhoneNumber($data){
		return DBActivityLotteryHelper::getConn()->from('beauty_activity_groupon')->insert($data);
	}
}
