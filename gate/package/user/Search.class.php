<?php
/*
 * 
 * @author
 */
namespace Gate\Package\User;
use Gate\Package\User\Userinfo;
use Gate\Package\User\Follow;
use Gate\Package\User\Login;
use Gate\Package\Sphinx\User AS sphinxUser;

class Search{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	/*
	 * 关键字搜索用户
	 */
	public function getUserByWord($loginUserId, $word, $offset, $length, $page){
		$data = new \stdClass;
		$data->list = array();
		$data->page = new \stdClass;
		$data->page->totalNum = 0;
		$spData = sphinxUser::getInstance()->searchWords($word, $offset, $length, $page);
		if( empty($spData) ){
			return $data;
		}

		$userIds = array();
		foreach($spData->data as $v){
			$userIds[] = $v['user_id'];
		}

		$data->list =  Userinfo::getInstance()->getUserByIds($userIds, '', 0, $length );
		// 是否关注
		$followUserIds = Follow::getInstance()->getFollowViewUserIds($loginUserId, $userIds);
		foreach($data->list as &$v){
			$v->is_follow =  ($loginUserId && in_array($v->user_id, $followUserIds)) ? true : false;
		}

		$data->page->totalNum = $spData->totalNum;
		return $data;
	}

}
