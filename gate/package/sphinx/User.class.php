<?php
/*
 * 
 * @wanghaihong
 */
namespace Gate\Package\Sphinx;
Use Gate\Libs\Sphinx;

class User{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }


	/*
	 * 删除数据
	 * (更新sphx索引中的数据字段值)
	 */
	public function deleteArticle($articleId){
		$sphinx = new Sphinx();
		$res = $sphinx->UpdateAttributes('article_index, article_index_delta', array('is_delete'), array($articleId=>array(1)));
		return $res;
	}

	/*
	 * 搜关键词
	 */
	public function searchWords($searchWords, $offset, $length, $page, $filterFloadRange=array()){
		$sphinx = new Sphinx();
		//$sphinx->setFilter('weights', array(5,10));
		// 并集
		$searchWords = str_replace(array(',', '  ', ' '), '|', $searchWords);
		foreach($filterFloadRange as $field=>$v){
			$sphinx->setFilterFloatRange($field, $v['min'], $v['max'], $v['exclude']);
		}
		//$sphinx->setSort($sort, SPH_SORT_EXPR);
		$sphinx->setLimits($offset, $length, $page);
		$spData = $sphinx->query($searchWords, 'user_id', 'user, user_delta');

		return $spData;
	}

	/*
	 * 更改索引名称
	 */
	public function editRealname($userId, $realname){
		$sphinx = new Sphinx();
		$res = $sphinx->UpdateAttributes('user', array('realname'), array(14=>array($realname)));
		return $res;
	}

}
