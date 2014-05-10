<?php
namespace Gate\Package\Article;
use Gate\Package\Helper\DBHotTagHelper;
use Gate\Package\Helper\DBHotArticleHelper;
use Gate\Package\Helper\DBCategoryHelper;
use Gate\Package\User\Userinfo;

use Gate\Package\Article\Article;
class HotTag {
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	 * todo:需求改变，$categoryId 不是id 了，变成字符串
	 */
	public function addHotTag($articleId, $categoryId){
		$data = array('article_id'=>$articleId, 'name'=>$categoryId);
		$res = Article::getInstance()->getArticleById($articleId,'article_id, category_id',0);
		if(empty($res->article_id)){
			return FALSE;
		}
		$haData = array('article_id'=>$articleId, 'category_id'=>$res->category_id, 'hot_category_id'=>$res->category_id);
		DBHotArticleHelper::getConn()->insert($haData);

		return DBHotTagHelper::getConn()->insert($data);
	}

	/*
	 * 
	 */
	public function getHotTagList($offset, $length){
		$list =  DBHotTagHelper::getConn()->field('article_id AS pk, article_id, name')->limit($offset, $length)->fetchAssocAll();
		$articleIds		= array();
		$userIds		= array();
		$categoryIds	= array();
		foreach($list as $v){
			$articleIds[] = $v->article_id;
		}
		$articleList = Article::getInstance()->getArticleByIds($articleIds, '', 0, $length);
		if( empty($articleList)){
			return array();
		}
		foreach($articleList as $v){
			$userIds[]		=	$v->user_id;
			$categoryIds[]	=	$v->category_id;
			//$list[$v->article_id]->article_title = $v->title;
		}
		$userList		=	Userinfo::getInstance()->getUserByIds($userIds,'',0,$length);
		$categoryList	=	DBCategoryHelper::getConn()->field('id,name')->where('id IN(:ids)',array('ids'=>$categoryIds))->fetchAssocAll();
		foreach($articleList as $v){
			foreach($userList as $u){
				if($v->user_id == $u->user_id){
					$v->userInfo = $u;
				}
			}
			foreach($categoryList as $c=>$cv){
				if($v->category_id == $c){
					$v->categoryName = $cv;
				}
			}
			foreach($list as $l){
				if($v->article_id == $l->article_id){
					$v->tagName	= $l;
				}
			}
		}
		return $articleList;
	}

	/*
	 *
	 */
	public function getHotTagTotalNum(){
		return DBHotTagHelper::getConn()->fetchCount();
	}

	public function delHotTag($article_id){
		return DBHotTagHelper::getConn()->delete('article_id=:article_id',array('article_id'=>$article_id));
	}

}
