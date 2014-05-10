<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBTagHelper;
use Gate\Package\Helper\DBTagUserHelper;
use Gate\Package\User\Userinfo;

class Tag{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getTagList($categoryId){
		return DBTagHelper::getConn()->field('tag_id, name, category_id')->where('category_id IN(:category_id)', array('category_id'=>$categoryId))->fetchAll();
	}

	public function getAllTagList(){
		return DBTagHelper::getConn()->field('tag_id, name, category_id')->where('is_delete=0', array())->fetchAll();
	}

	/*
	 *
	 */
	public function getTagUser($tagId=0, $categoryId=0, $offset, $length){
		if($tagId){
			$where		= 'tag_id=:tag_id';
			$whereParams= array('tag_id'=>$tagId);
		}elseif($categoryId){
			$where		= 'category_id=:category_id';
			$whereParams= array('category_id'=>$categoryId);
		}else{
			return array();
		}

		$userIds =  DBTagUserHelper::getConn()->field('user_id')->where($where, $whereParams)->order('id DESC')->limit($offset, $length)->fetchCol();
		if(empty($userIds)){
			return array();
		}
		return Userinfo::getInstance()->getUserByIds($userIds);	

	}


}
