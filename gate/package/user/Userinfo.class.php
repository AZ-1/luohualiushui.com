<?php
namespace Gate\Package\User;
use Gate\Package\Helper\DBUserProfileHelper;
use Gate\Package\Helper\DBUserConnectHelper;
use Gate\Package\Helper\DBUserExtinfoHelper;
use Gate\Package\Helper\DBUserStatisticHelper;
use Gate\Package\Helper\DBUserGradeHelper;
use Gate\Package\Helper\DBUserConnectBindHelper;
use Gate\Package\Helper\DBHotUserHelper;
use Gate\Package\Helper\DBHotUserCategoryHelper;
use Gate\Package\Helper\DBFeedbackHelper;
use Gate\Package\Helper\DBUserSkinHelper;
use Gate\Package\Article\Article;
use Gate\Package\Sphinx\User AS sphinxUser;
use Gate\Libs\Utilities;

class Userinfo{
	private static $instance;
	private $data = array();
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	 * 用户等级
	 */
	private static $grade;
	public function getGrade($id=null){
		if($id===0 || $id==='0'){ //容错
			$grade = new \stdClass;
			$grade->grade_id = 0;
			$grade->name = "";
			$grade->icon = '';
			return $grade;
		}
		 
		is_null(self::$grade) && self::$grade = DBUserGradeHelper::getConn()->field('id, id AS grade_id,name,icon')->fetchAssocAll();
		return $id!==null ? self::$grade[$id] : self::$grade;
	}

	public function getRecommendDaren($gradeId){
		return DBUserProfileHelper::getConn()->where('grade=:grade AND is_recommend=1',array("grade"=>$gradeId))->fetchAssocAll();
		
	}

	public function getUserIdsByGradeId($gradeId){
		return DBUserProfileHelper::getConn()->where('grade=:grade',array("grade"=>$gradeId))->fetchAssocAll();
		
	}
	
	public function checkRepeatRealname($realname, $userId){
		return DBUserProfileHelper::getConn()->field('user_id')-> where('realname=:realname AND user_id != :user_id', array('realname'=>$realname, 'user_id'=>$userId))->limit(1)->fetch();
	}

	public function getUser(){
		return DBUserProfileHelper::getConn()-> where('nickname=:nickname', array('nickname'=>'admin'))->limit(1)->fetch();
	}

	public function getUserInfoList(){
		return $this->getUserinfo('','',array(),$offset,$length);
	}
	public function getUserById($userId, $fields=''){
		$list =  $this->getUserByIds(array($userId), $fields, 0, 1);
		if(empty($list)){
			return FALSE;
		}
		return $list[$userId];
	}

	public function getExUserinfoById($userId,$fields=''){
		$exList = DBUserExtinfoHelper::getConn()->where('user_id=:id',array('id'=>$userId))->fetch();
		return $exList;	
	}

	public function getUserByIds($ids, $fields, $offset, $length){
		if(empty($ids)){
			return array();
		}
		return $this->getUserinfo($fields, 'user_id IN(:user_id)', array('user_id'=>$ids), $offset, $length);

	}

	/*
	 *
	 */
	public function getHotDaren(){
		$daRenId = DBHotUserHelper::getConn()->field('user_id')->limit(100)->fetchCol();
		if( count($daRenId) >8){
			$daRenId = Utilities::array_random($daRenId, 8);
		}
		$list = $this->getUserByIds($daRenId,'',0, 8); 
		return $list;
	}

	/*
	 * top  达人
	 */
	public function getTopDaren($length){
		$sql = "SELECT s.user_id,p.realname,s.fans_num,p.grade FROM beauty_user_profile AS p, beauty_user_statistic AS s WHERE p.user_id=s.user_id AND  p.grade>0 ORDER BY s.fans_num DESC LIMIT {$length}";
		$fansList = DBUserStatisticHelper::getConn()->fetchAssocAll($sql,array());
		//$fansList = DBUserStatisticHelper::getConn()->field('user_id, fans_num')->order('fans_num DESC')->limit($length)->fetchAssocAll();
		$userIds = array_keys($fansList);
		$list =  $this->getUserByIds($userIds,'user_id,realname,avatar_c,grade',0, $length); 
		foreach($userIds as $v){
			$list[$v]->fans_num = $fansList[$v]->fans_num;
			$data[] = $list[$v];
		}
		return $data;
	}

	public function getUserLoginType($user_id){
		return DBUserConnectHelper::getConn()->field('user_type')->where('user_id=:user_id',array('user_id'=>$user_id))->fetchCol();
	}

	public function getUserBoundInfo($user_id){
		return DBUserConnectBindHelper::getConn()->field('user_type , realname')->where('user_id=:user_id',array('user_id'=>$user_id))->fetchAll();
	}

	public function getDaren(){
		$daRenId = DBHotUserHelper::getConn()->field('user_id')->limit(10)->fetchCol();
		$list = $this->getUserByIds($daRenId,'',0, 10); 
		return $list;
	}

	/*
	 * 随机显示所有达人
	 * todo:达人需要单独一张表 
	 */
	public function getRandDarenList($loginUserId, $length){
		$userIds = DBUserProfileHelper::getConn()->field('user_id')->where('grade>0', array())->fetchCol();
		if(count($userIds)>$length){
			$userIds = Utilities::array_random($userIds, $length);
		}
		$list = $this->getUserByIds($userIds,'',0, $length); 
		// 是否关注
		$followUserIds = Follow::getInstance()->getFollowViewUserIds($loginUserId, $userIds);
		foreach($list as &$v){
			$v->is_follow = ($loginUserId && in_array($v->user_id, $followUserIds)) ? true : false;
		}

		return $list;
	}


	/*
	 * 达人
	 * todo:达人需要单独一张表 
	 */
	public function getDarenList($gradeId, $loginUserId, $offset, $length){
		$fields		= 'user_id,realname,grade,description,avatar_c,fans_num,article_num';
		if($gradeId==0){
			$where = 'grade>0';
			$param = array();
		}else{
			$where = 'grade=:grade';
			$param = array('grade'=>$gradeId);
		}
		$userList	= $this->getUserinfo($fields, $where, $param, $offset, $length);
		$userIds	= array();
		foreach($userList as $v){
			$userIds[] = $v->user_id;
		}

		// 登录用户是否关注
		$followUserIds = Follow::getInstance()->getFollowViewUserIds($loginUserId, $userIds);
		foreach($userList as &$v){
			$v->is_follow = ($loginUserId && in_array($v->user_id, $followUserIds)) ? true : false;
		}

		return $userList;
	}

	/*
	 * 达人统计
	 */
	public function getDarenListCount($gradeId){
		if($gradeId==0){
			$row = DBUserGradeHelper::getConn()->field('SUM(user_num) AS user_num')->fetch();
		}else{
			$row = DBUserGradeHelper::getConn()->field('user_num')->where('id=:id', array('id'=>$gradeId))->fetch();
		}
		return (int)$row->user_num;
	}

	public function editUserInfo($userId,$data){
		$this->setGuide($userId);
		return DBUserProfileHelper::getConn()->update($data,'user_id=:id',array('id'=>$userId));
	}

	public function editUserExInfo($userId,$data){
		return DBUserExtinfoHelper::getConn()->update($data,'user_id=:id',array('id'=>$userId));	
	}

	/*
	 * 设置引导
	 */
	public function setGuide($userId){
		return DBUserProfileHelper::getConn()->update(array('status'=>1),'user_id=:id AND status=0',array('id'=>$userId));
	}

	/*
	 * 封装公用数据
	 * user表相关
	 */
	public function getUserinfo($fields='', $where, $whereParams,  $offset, $length){
		$fields == '' && $fields = 'nickname, realname,description,grade, avatar_c, avatar_width, avatar_height,follow_num,fans_num,like_article_num, article_num, collect_topic_num, skin_type ';

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
			if(isset($v->grade)){
				$v->grade = $this->getGrade($v->grade);
				/*
				if($v->grade>0){
					$v->grade = $this->getGrade($v->grade);
				}else{
					$v->grade = new \stdClass;
					$v->grade->grade_id = 0;
					$v->grade->name = "";
					$v->grade->icon = '';
				}
				 */
			}
			/*
			if(strpos($fields, 'article_num') && !isset($v->article_num)){
				$v->article_num = 0;
			}
			if(strpos($fields, 'fans_num') && !isset($v->fans_num)){
				$v->fans_num = 0;
			}
			if(strpos($fields, 'like_article_num') && !isset($v->like_article_num)){
				$v->like_article_num = 0;
			}
			if(strpos($fields, 'follow_num') && !isset($v->follow_num)){
				$v->follow_num = 0;
			}
			 */

			// 肤质
			if(isset($v->skin_type)){
			//	$v->skin = $this->get
			}

		}
		return $list;
	}

	/*
	 * 达人引导
	 */
	public function getGuideDarenList(){
		$length = 100;
		$darenIds = DBHotUserHelper::getConn()->field('user_id')->limit($length)->fetchCol();
		$userList = $this->getUserByIds($darenIds,'',0, $length); 

		$articleList = Article::getInstance()->getGuideDarenArticleList($darenIds, $length);
		$data = array();
		foreach($userList as $vUser){
			$vUser->article = $articleList[$vUser->user_id];
			$vUser->article->description_pic = explode(',', $vUser->article->description_pic);
			$data[] = $vUser;
		}
		return $data;
	}

	public function setGuideDaren($userId){
		$row = DBUserExtinfoHelper::getConn()->field('user_id')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		if(!$row){
			return DBUserExtinfoHelper::getConn()->insert(array('user_id'=>$userId,'is_guide_follow'=>1));
		}else{
			return DBUserExtinfoHelper::getConn()->update(array('is_guide_follow'=>1), 'user_id=:user_id', array('user_id'=>$userId));
		}

	}

	public function setGuideTopic($userId){
		$row = DBUserExtinfoHelper::getConn()->field('user_id')->where('user_id=:user_id', array('user_id'=>$userId))->fetch();
		if(!$row){
			return DBUserExtinfoHelper::getConn()->insert(array('user_id'=>$userId,'is_guide_topic'=>1));
		}else{
			return DBUserExtinfoHelper::getConn()->update(array('is_guide_topic'=>1), 'user_id=:user_id', array('user_id'=>$userId));
		}

	}

	/*
	 * 肤质
	 */
	private static $skin;
	public function getSkinType($id=null){
		is_null(self::$skin) && self::$skin = DBUserSkinHelper::getConn()->field('id AS pk, id, name')->fetchAssocAll();
		return $id!==null ? self::$skin[$id] : self::$skin;
	}

	/*
	 *
	 */
	public function getDarenCategory(){
		return DBUserGradeHelper::getConn()->field('id, name')->fetchAll();
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
