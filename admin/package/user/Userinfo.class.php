<?php
namespace Gate\Package\User;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Package\Helper\DBUserConnectHelper;
use Gate\Package\Helper\DBUserExtinfoHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Package\Helper\DBUserGradeHelper;
use Gate\Package\Helper\DBUserDarenHelper;
use Gate\Package\Helper\DBUserIdentityHelper;
use Gate\Package\Helper\DBHotUserHelper;
use Gate\Package\Helper\DBFeedbackHelper;
use Gate\Package\Helper\DBArticleHelper;

class Userinfo{
	private static $instance;
	private static $grade;
	private static $identity;
	private $data = array();
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	public function getDarenTotalNum(){
		return DBUserProfileHelper::getConn()->where('is_delete=0 AND grade>0',array())->fetchCount();
	}
	public function getUserTotalNum(){
		return DBUserHelper::getConn()->fetchCount();
	}
	public function getHotDarenList($offset,$length){
		return DBHotUserHelper::getConn()->field('hot_id,user_id')->limit($offset,$length)->fetchAll();
		//is_null(self::$identity) && self::$identity = DBUserDarenHelper::getConn()->field('id, id AS identity_id,identity')->fetchAssocAll();
		//return $id ? self::$identity[$id] : self::$identity;
	}

	public function getUserLoginType($user_ids){
		if(empty($user_ids)){
			return FALSE;
		}
		return DBUserConnectHelper::getConn()->where('user_id IN(:user_ids)',array('user_ids'=>$user_ids))->fetchAssocAll();
	}

	public function getHotDarenNum(){
		return DBHotUserHelper::getConn()->fetchCount();
	}

	public function getHotDaren($hot_id){
		return DBHotUserHelper::getConn()->where('hot_id=:id',array('id'=>$hot_id))->fetch();
	}

	/*
	 * 
	 */
	public function addHotDaren($userIds){
		$rs = array('status'=>FALSE);
		if( !is_array($userIds)){
			$userIds = array($userIds);
		}

		// 检查是否已存在
		$oldUserIds = DBHotUserHelper::getConn()->field('user_id')->where('user_id IN(:uid)', array('uid'=>$userIds))->fetchCol();
		if( !empty($oldUserIds)){
			$userIds = array_diff($userIds, $oldUserIds);
		}
		if(empty($userIds)){
			$rs['error'] = '达人已存在';
			return $rs;
		}

		// 检查用户id是否存在
		$userIds = DBUserProfileHelper::getConn()->field('user_id')->where('user_id IN(:uid)', array('uid'=>$userIds))->fetchCol();

		if(empty($userIds)){
			$rs['error'] = '用户不存在';
			return $rs;
		}

		foreach($userIds as $vuid){
			DBHotUserHelper::getConn()->insert(array('user_id'=>$vuid));
		}
		$rs['status'] = true;
		return $rs;
	}
	public function delHotDaren($hot_id){
		return DBHotUserHelper::getConn()->delete('hot_id=:id',array('id'=>$hot_id));
	}
	public function upHotDaren($hot_id,$user_id){
		return DBHotUserHelper::getConn()->update(array('user_id'=>$user_id),'hot_id=:id',array('id'=>$hot_id));
	}

	/*
	 * 推荐达人
	 */
	public function recommendDaren($userId, $is){
		return DBUserProfileHelper::getConn()->update(array('is_recommend'=>$is),'user_id=:user_id',array('user_id'=>$userId));
	}

	/*
	 * 用户等级
	 */
	public function getIdentityList(){
		return DBUserIdentityHelper::getConn()->field('id,identity')->fetchAll();
	}
	public function getIdentity($id){
		is_null(self::$identity) && self::$identity = DBUserIdentityHelper::getConn()->field('id, id AS identity_id,identity')->fetchAssocAll();
		return $id ? self::$identity[$id] : self::$identity;
	}
	public function addIdentity($identity){
		return DBUserIdentityHelper::getConn()->insert(array('identity'=>$identity));
	}
	public function delIdentity($id){
		return DBUserIdentityHelper::getConn()->delete('id=:id',array('id'=>$id));
	}
	public function upIdentity($id,$identity){
		return DBUserIdentityHelper::getConn()->update(array('identity'=>$identity),'id=:id',array('id'=>$id));
	}
	public function getGradeList($id=0){
		return $this->getGrade($id);
	}
	public function addGrade($name,$value){
		return DBUserGradeHelper::getConn()->insert(array("name"=>$name,"value"=>$value));
	}	
	public function delGrade($id){
		return  DBUserGradeHelper::getConn()->delete('id=:id',array('id'=>$id));
	}	
	public function upGrade($id,$name,$value){
		return DBUserGradeHelper::getConn()->update(array("name"=>$name,"value"=>$value),"id=:id",array("id"=>$id));
	}	
	public function getGrade($id){
		is_null(self::$grade) && self::$grade = DBUserGradeHelper::getConn()->field('id, id AS grade_id,name,icon')->fetchAssocAll();
		return $id ? self::$grade[$id] : self::$grade;
	}
	public function delDaren($id){
		$row = DBUserProfileHelper::getConn()->field('grade')->where("user_id=:id",array("id"=>$id))->fetch();
		$isUp = DBUserProfileHelper::getConn()->update(array("grade"=>0),"user_id=:id",array("id"=>$id));
		if($isUp){
			DBHotUserHelper::getConn()->delete("user_id=:id",array("id"=>$id));
			// 统计 旧的自减
			DBUserGradeHelper::getConn()->decrement('user_num', array('id'=>$row->grade));
			return true;
		}
		return false;
	}	
	public function addDaren($grade){
		foreach($grade as $g=>$v){
			if($v != '-1'){
				// 统计 新的自增
				DBUserGradeHelper::getConn()->increment('user_num', array('id'=>$v));

				DBUserProfileHelper::getConn()->update(array("grade"=>$v),"user_id=:id",array("id"=>$g));
			}
		}
	}	
	public function delUser($id){
		return DBUserProfileHelper::getConn()->update(array("is_delete"=>1),"user_id=:id",array("id"=>$id));
	}	
	public function noDelUser($id){
		return DBUserProfileHelper::getConn()->update(array("is_delete"=>0),"user_id=:id",array("id"=>$id));
	}	

	public function upUser($id,$identity,$newId,$unbind){
				$identity = intval($identity);
				if($newId){
					$bind = $this->bindUser($id,$newId);
					if($bind['status'] == 300){
						return $bind;
					}
				}elseif($unbind){
					$this->userUnbind($id,$unbind);
				}
				return DBUserProfileHelper::getConn()->update(array("identity"=>$identity),"user_id=:id",array("id"=>$id));
			}


	/*
	 * 将第三方登录的fromUserId 替换成 旧数据的 toUserId
	 */
	public function bindUser($toUserId,$fromUserId){
		$result = array();
		$userId = DBUserConnectHelper::getConn()->field('user_id')->where('old_user_id=:id',array('id'=>$fromUserId))->fetch();
		if($userId){
			$userName = DBUserProfileHelper::getConn()->field('realname')->where('user_id=:id',array('id'=>$userId->user_id))->fetch();
			$result['status']   = 300;
			$result['message']  = "该ID已被用户".$userName->realname."(".$userId->user_id.")"."绑定,请解除绑定再操作!";
		}elseif(!DBUserConnectHelper::getConn()->field('user_id')->where('user_id=:id',array('id'=>$fromUserId))->fetch()){
			$result['status']   = 300;
			$result['message']  = "您要绑定的ID不存在,请核对后再操作!";
		}else{
			$isUp = DBUserConnectHelper::getConn()->update(array("user_id"=>$toUserId, 'old_user_id'=>$fromUserId),"user_id=:user_id",array("user_id"=>$fromUserId));
			if($isUp){
				// 昵称调整
				$fromUser	= DBUserProfileHelper::getConn()->field('realname, avatar_c')->where('user_id=:id',array('id'=>$fromUserId))->fetch();
				DBUserProfileHelper::getConn()->update(array('realname'=>$fromUser->realname . '_bind'), 'user_id=:id', array('id'=>$fromUserId));
				DBUserProfileHelper::getConn()->update(array('realname'=>$fromUser->realname, 'avatar_c'=>$fromUser->avatar_c), 'user_id=:id', array('id'=>$toUserId));

				$result['status']   = 200;
				$result['message']  = "操作成功";
			}
		}
		return $result;
	}

	/*
	 * 
	 */
	public function upDaren($userId,$grade){
		// 统计 新的自增
		DBUserGradeHelper::getConn()->increment('user_num', array('id'=>$grade));

		$row = DBUserProfileHelper::getConn()->field('grade')->where("user_id=:id",array("id"=>$userId))->fetch();
		// 统计 旧的自减
		if($row->grade > 0){
			DBUserGradeHelper::getConn()->decrement('user_num', array('id'=>$row->grade));
		}

		return DBUserProfileHelper::getConn()->update(array("grade"=>$grade),"user_id=:id",array("id"=>$userId));
	}	
	public function getUser(){
		return DBUserProfileHelper::getConn()-> where('nickname=:nickname', array('nickname'=>'admin'))->limit(1)->fetch();
	}
	public function addUser($data){
		return DBUserProfileHelper::getConn()->insert($data);
	}
	public function getUserInfoTotalNum(){
		return DBUserProfileHelper::getConn()->fetchCount();
	}
	public function getUserInfoList($offset,$length){
		$userList = $this->getUserinfo('','1=1',array(),$offset,$length);
		return $userList;
	}
	public function getUserIdByName($name)
	{
		$id = DBUserProfileHelper::getConn()->field('user_id')->where("realname=:name",array('name'=>$name))->limit(1)->fetch();
		if(empty($id))return false;
		return $id;
	}
	public function getUserById($userId){
		$list =  $this->getUserByIds(array($userId), '', 0, 1);
		if(empty($list)){
			return FALSE;
		}
		$list[$userId]->isBind = 0;
		$row = DBUserConnectHelper::getConn()->field('old_user_id')->where("user_id=:userId",array('userId'=>$userId))->limit(1)->fetch();
		if($row){
			$list[$userId]->isBind = 1;
			$list[$userId]->old_user_id = $row->old_user_id;
		}
		return $list[$userId];
	}

	public function getUserByIds($ids, $fields, $offset, $length){
		if(empty($ids)){
			return array();
		}
		return $this->getUserinfo($fields, 'user_id IN(:user_id)', array('user_id'=>$ids), $offset, $length);

	}

	public function getSearchDaren($keyword, $offset=0, $length=20){
		$searchDaren = DBUserProfileHelper::getConn()-> where('realname like :realname', array('realname'=>"%$keyword%"))->limit($offset ,$length)->fetchAll();
		$ids = array();
		foreach($searchDaren as $sd){
			$ids[] = $sd->user_id;
		}
		$list =  $this->getUserByIds($ids,'',0,100);
		return $list;
	}

	public function getDaren($offset,$length , $keyword=''){
		$list = $this->getUserinfo('user_id,grade,nickname,realname,is_recommend,is_delete,last_login_time','grade>0',array(),$offset,$length);
		$userIds = array();
		foreach($list as $l){
			$userIds[] = $l->user_id;
		}
		$list	= $this->getUserByIds($userIds ,'' ,  0 , $length);
		return $list;
	}
	public function getUserStatisticList($offset=0,$length=100,$only_daren = false)
	{
		$sql = "select S.user_id AS ID, realname AS NAME, article_num AS PUBLISHS, comment_num AS COMMENTS ,like_article_num AS LIKES,article_num+comment_num+like_article_num AS COUNT  from beauty_user_statistic S , beauty_user_profile P where P.user_id=S.user_id ";
		if($only_daren)
		{
			$sql = $sql.' AND grade > 0 ';
	    }
        $sql = $sql . " order by COUNT DESC limit $offset,$length  ";
		$sql = $sql.' ;';
		$list = DBUserStatisticHelper::getConn()->limit($offset,$length)->fetchAll($sql);
		return $list;

	}
	public function getUserStatisticTotalNum($only_daren = false)
	{

		$sql = "select count(S.user_id) as num  from beauty_user_statistic S , beauty_user_profile P where P.user_id=S.user_id";
	
		if($only_daren)
		{
			$sql = $sql." AND grade > 0 ";
	    }
		$sql = $sql.' ;';
		//	var_dump($sql);
		error_reporting(0);
		$row = DBUserStatisticHelper::getConn()->fetch($sql);
		return $row->num;
	}
	public function userUnbind($id , $old){
		return DBUserConnectHelper::getConn()->update(array('user_id'=>$old,'old_user_id'=>0),'user_id=:id',array('id'=>$id));
	}

	/*
	 * 封装公用数据
	 * user表相关
	 */
	public function getUserinfo($fields='', $where='1=1', $whereParams,  $offset, $length){
		$fields == '' && $fields = 'nickname, realname,identity,description,grade, avatar_c, avatar_width, avatar_height,follow_num,fans_num,like_article_num, article_num,is_delete ,create_time , last_login_time';

		$pkId			= 'user_id';
		$dbHelper		= array('DBUserProfileHelper','DBUserExtinfoHelper', 'DBUserStatisticHelper', 'DBUserConnectHelper');
		$namespace		= '\Gate\Package\Helper\\';
		foreach($dbHelper as &$vh){
			$vh = $namespace . $vh;
		}

		$arrFields		= explode(',', str_replace(' ', '', $fields));
		$data			= array();
		// 第一个表数据，决定排序
		$dbFields	= explode(',', str_replace(' ', '', $dbHelper[0]::_FIELDS_));
		$dbFields	= array_intersect($dbFields, $arrFields);
		$data[0]	= $dbHelper[0]::getConn()->field($pkId . ',' . implode(',', $dbFields) )->where($where, $whereParams)->order($pkId . ' DESC')->limit($offset, $length)->fetchArrAll();
		unset($dbHelper[0]);
		if(empty($data[0])){
			return array();
		}

		$ids = array();
		foreach($data[0] as $v){
			$ids[] = $v[$pkId];
		}
		foreach($dbHelper as $k=>$vh){
			$dbFields	= explode(',', str_replace(' ', '', $vh::_FIELDS_));
			$dbFields	= array_intersect($dbFields, $arrFields);
			if( !empty($dbFields)){
				$data[]		= $vh::getConn()->field($pkId . ',' . implode(',', $dbFields) )->where($pkId.' IN(:id) ', array('id'=>$ids))->fetchArrAll();
			}
		}

		$list = array();
		// 合并数组
		foreach($data as $vFieldList){
			foreach($vFieldList as $v){
				$list[$v[$pkId]] = isset($list[$v[$pkId]]) ? $list[$v[$pkId]] + $v : $v;
			}
		}

		// 转换为对象模式
		$result = array();
		foreach($list as &$v){
			$v =  (object)$v;
			// 等级
			isset($v->grade)&& $v->grade > 0 && $v->grade = $this->getGrade($v->grade); 
			isset($v->identity)&& $v->identity > 0 && $v->identity = $this->getIdentity($v->identity); 
		}
		return $list;
	}

   // 取短时间内的注册用户 select create_time from beauty_user_profile where create_time > $start and create_time < $end;
	public function getUserSearchedByDur($start,$end)
	{
		$params = array(
			'start' => $start,
			'end' => $end
			 );
        $result = DBUserProfileHelper::getConn()->field('count(*) AS count_user')->where('create_time>:start AND create_time<:end', $params)->fetch();
	    return $result;	
	}


   // 获取用户在段时间内发布的文章数
	public function getArticlesByIDANDTime($user_id,$start_time,$end_time)
	{
		$params = array(
			'uid' => $user_id,		
			'start' => $start_time,
			'end' => $end_time
		);
        $result = DBArticleHelper::getConn()->field('count(*) AS Articles ')->where('user_id=:uid AND create_time>:start AND create_time<:end', $params)->fetch();
		return $result->Articles;	
	}


	public function addFeedback($data){
		return DBFeedbackHelper::getConn()->insert($data);		
	}

	public function delFeedback($id){
		return DBFeedbackHelper::getConn()->delete('id=:id',array('id'=>$id));
	}

	public function getFeedbackNum(){
		return DBFeedbackHelper::getConn()->fetchCount();
	}

	public function getFeedback($id){
		$feedback	= DBFeedbackHelper::getConn()->where('id=:id',array('id'=>$id))->fetch();
		$user_id;
		if($feedback->user_id){
			$user_id = $feedback->user_id;
		}
		if(empty($user_id)){
			return $feedback;
		}
		$feedback->userInfo = $this->getUserById($user_id);
		return $feedback;
	}

	public function getFeedbackList($offset,$length){
		$feedbackList	= DBFeedbackHelper::getConn()->limit($offset,$length)->order('id DESC')->fetchAll();
		$user_ids		= array();
		foreach($feedbackList as $fbl){
			if($fbl->user_id){
				$user_ids[] = $fbl->user_id;
			}
		}
		if(empty($user_ids)){
			return $feedbackList;
		}
		$userInfo = $this->getUserByIds($user_ids,'',0,count($user_ids));
		foreach($feedbackList as $fbl){
			foreach($userInfo as $ui){
				if($ui->user_id == $fbl->user_id){
					$fbl->userInfo = $ui;
				}
			}
		}
		return $feedbackList;
	}
}
