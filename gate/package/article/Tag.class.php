<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBTagHelper;
use Gate\Package\Helper\DBTagUserHelper;
use Gate\Package\User\Userinfo;
use Gate\Package\article\Category;

class Tag{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getTagList($categoryId){
		$categoryList = Category::getInstance()->getChildren($categoryId);
		$cids = array();
		foreach($categoryList as $v){
			$cids[] = $v->id;
		}
		$cids[] = $categoryId;
		return DBTagHelper::getConn()->field('tag_id, name, category_id')->where('category_id IN(:category_id) AND is_delete=0', array('category_id'=>$cids))->fetchAll();
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
	
	/*
	 * 标签的文章统计(审核通过的)
	 */
	public function getArticleTagTotalNum($tagId){
		$row =  DBTagHelper::getConn()->field('article_num_in')->where('tag_id=:tag_id', array('tag_id'=>$tagId))->fetch();
		return $row->article_num_in;
	}

}
