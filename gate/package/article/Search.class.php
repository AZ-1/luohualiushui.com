<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Article\Article;
use Gate\Package\Article\Topic;
use Gate\Package\Article\Category;
use Gate\Package\User\Userinfo;
use Gate\Package\Sphinx\Article AS sphinxArticle;
use Gate\Package\Sphinx\Topic AS sphinxTopic;
use Gate\Package\Sphinx\User AS sphinxUser;

class Search{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	 *  文章
	 */
	public function getArticleListByWord($word, $offset, $length, $page){
		$data = new \stdClass;
		$data->list = array();
		$data->page = new \stdClass;
		$data->page->totalNum = 0;
		$spData = sphinxArticle::getInstance()->searchWords($word, $offset, $length, $page);
		if( empty($spData) ){
			return $data;
		}

		$articleIds = array();
		foreach($spData->data as $v){
			$articleIds[] = $v['article_id'];
		}

		$articleList =  Article::getInstance()->getArticleByIds($articleIds, '', 0, $length);
		if(empty($articleList)){
			return array();
		}
		$userIds		= array();
		$categoryIds	= array();
		foreach($articleList as $v){
			$userIds[]	= $v->user_id;
			$categoryIds[]	= $v->category_id;
		}
		$userList		= Userinfo::getInstance()->getUserByIds($userIds, 'user_id, nickname, realname, grade, avatar_c', 0, $length);
		$categoryList	= Category::getInstance()->getCategoryByCids($categoryIds, 'id,name');
		foreach($articleList as $v){
			$v->user = $userList[$v->user_id];
			$v->category= $categoryList[$v->category_id];
			$v->category->id= $v->category_id;
			$data->list[] = $v;
		}

		$data->page->totalNum = $spData->totalNum;
		return $data;
	}

	/*
	 * 话题
	 */
	public function getTopicByWord($loginUserId, $word, $offset, $length, $page){
		$data = new \stdClass;
		$data->list = array();
		$data->page = new \stdClass;
		$data->page->totalNum = 0;
		$spData = sphinxTopic::getInstance()->searchWords($word, $offset, $length, $page);
		if( empty($spData) ){
			return $data;
		}

		$topicIds = array();
		foreach($spData->data as $v){
			$topicIds[] = $v['topic_id'];
		}

		$data->list =  Topic::getInstance()->getTopicByIds($topicIds );
		$data->page->totalNum = $spData->totalNum;

		if($loginUserId){ //登录用户
			$collectTopicIds = Topic::getInstance()->getCollectTopic($topicIds, $loginUserId);
			foreach($data->list as &$v){
				$v->is_collect = in_array($v->topic_id, $collectTopicIds) ? true : false;
			}
		}

		return $data;
	}

}
