<?php
/*
 * 
 * @author ydq
 */
namespace Gate\Package\Activity;
use Gate\Libs\Utilities;
use Gate\Package\Helper\DBActivityLotteryHelper;
use Gate\Package\User\Userinfo;

class Prize{
	private static $instance;
	private $login_type = array('QQ_','Sina_','Taobao_');
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
	}

	public function getLotteryList($offset , $length ,$user_name=''){
		if($user_name != ''){
			$lottery_list = DBActivityLotteryHelper::getConn()->where('source_user_name=:user_name AND activity_id=1',array('user_name'=>$user_name))->fetchAll();
		}else{
			$sql = "SELECT source_user_name,user_id 
				FROM `beauty_activity_lottery`
				WHERE activity_id=1
				GROUP BY source_user_name,user_id
				LIMIT {$offset},{$length}
				";
			//$lottery_list = DBActivityLotteryHelper::getConn()->fetchAll($sql ,array());
			$ids = DBActivityLotteryHelper::getConn()->fetchAll($sql ,array());
			$user_ids = array();
			$user_names = array();
			foreach($ids as $v){
				if(empty($v->source_user_name)){
					$user_ids[] = $v->user_id;
				}else{
					$user_names[] = $v->source_user_name;
				}
			}
			if(!empty($user_names)){
				$lottery_list = DBActivityLotteryHelper::getConn()->where('source_user_name IN (:user_names) AND activity_id=1',array('user_names'=>$user_names))->order('source_user_name ASC')->fetchAll();
			}else{
				$lottery_list = array();
			}
			if(!empty($user_ids)){
				$type = array('QQ_','Sina_','Taobao_');
				$list_p = DBActivityLotteryHelper::getConn()->where('user_id IN (:user_ids) AND activity_id=1 AND source_user_name=""',array('user_ids'=>$user_ids))->order('user_id ASC')->fetchAll();
				$userInfo = Userinfo::getInstance()->getUserByIds($user_ids,'',0,count($user_ids));
				$loginType = Userinfo::getInstance()->getUserLoginType($user_ids);
				foreach($userInfo as $v){
					if(substr($v->realname,-6,1)=='_'){
						$v->realname = substr($v->realname,0,strlen($v->realname)-6);
					}
					if(isset($loginType[$v->user_id])){
						$v->loginType = $type[$loginType[$v->user_id]->user_type];
					}else{
						$v->loginType = null;
					}
					foreach($list_p as $vv){
						$v->prize = $vv->prize;
						$v->create_time = $vv->create_time;
					}
				}
				$lottery_list = array_merge($lottery_list,$userInfo);
			}
		}
		return $lottery_list;
	}

	public function getTotoleLotteryNum(){
		 return DBActivityLotteryHelper::getConn()->where('activity_id=1',array())->fetchCount();
	}
	
	public function getShoppingList($offset , $length ,$user_name=''){
		 $prizeList = array('1'=>'20元优惠','2'=>'35元优惠','3'=>'80元优惠');
		if($user_name != ''){
			$List = DBActivityLotteryHelper::getConn()->where('activity_id=2 AND source_user_name=:user_name',array('user_name'=>$user_name))->limit($offset,$length)->fetchAll();
		}else{
			$List = DBActivityLotteryHelper::getConn()->where('activity_id=2',array())->limit($offset,$length)->fetchAll();
		}
		 foreach($List as $v){
			 $prize = explode(',',$v->prize);
			 $prizeN = array();
			foreach($prize as $vv){
				if($vv != ''){
					$prizeN[] = $prizeList[$vv];
				}
			}
			 $prizeS = implode(',',$prizeN);
			 $v->prize = $prizeS;
		 }
		return $List;
	}

	public function getTotalShoppingNum(){
		 return DBActivityLotteryHelper::getConn()->where('activity_id=:activity_id',array('activity_id'=>2))->fetchCount();
	}

	public function getTotalShoppingIdNum($user_name){
		 
		$sql = "SELECT source_user_name 
				FROM `beauty_activity_lottery` where activity_id=:activity_id and  source_user_name=:user_name
				GROUP BY source_user_name";
	 return DBActivityLotteryHelper::getConn()->fetchCount($sql,array('activity_id'=>2,'user_name'=>$user_name));
	}

}


?>
