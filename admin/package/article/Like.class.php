<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBLikeHelper;
use Gate\Package\Helper\DBArticleStatisticHelper;

class Like{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	* 
	*/
	public function addLike($articleId, $userId){
		$data	= array('article_id'=>$articleId, 'user_id'=>$userId);
		$newId	= DBLikeHelper::getConn()->insert( $data );
		if($newId){
			DBArticleStatisticHelper::getConn()->increament('like_num', $newId);
		}
		return $newId;
	}


	/*
	 * 
	 */
	public function delLike($id, $userId){
		$sqlData = array();
        $sqlData['user_id']		= $userId;
        $sqlData['like_id']		= $id;
		$isD = DBLikeHelper::getConn()->delete('like_id=:like_id AND user_id=:user_id', $sqlData);

		if($isD){
			DBArticleStatisticHelper::getConn()->decrement($id);
		}
		return $isD;
	}


	/*
	 * 用户喜欢的文章
	 */
	public function getUserLikeArticle($userId, $offset, $length){
		$sqlData['user_id'] = $userId;
		$likeList	= DBLikeHelper::getConn()->where('user_id=:user_id', $sqlData)->order('like_id DESC')->limit($offset, $length)->fetchAll();
		if(empty($likeList)){
			return array();
		}
		$articleIds = array();
		foreach($likeList as $v){
			$articleIds[] = $v->article_id;
		}
		return $articleIds;
	}
}
