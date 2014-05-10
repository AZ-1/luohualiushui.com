<?php
/*
 * 
 * @wanghaihong
 */
namespace Gate\Package\Sphinx;
Use Gate\Libs\Sphinx;

class Article{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	 * 按时间衰减排序
	 */
	private function getSort(){
		$now = time();
		$areaTime = 3600*24*30; // x天之内
		$sort = " IF( weights = 10,
						IF(  ({$now} - create_time)<{$areaTime}, 
							like_num+24*300 , like_num), like_num)
					* pow( 1 + 0.014 * (( {$now} - create_time) / 3600), -1/2)";
		return $sort;
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
		$sort = $this->getSort();
		//$sphinx->setFilter('weights', array(5,10));
		// 并集
		$searchWords = str_replace(array(',', '  ', ' '), '|', $searchWords);
		foreach($filterFloadRange as $field=>$v){
			$sphinx->setFilterFloatRange($field, $v['min'], $v['max'], $v['exclude']);
		}
		//$sphinx->setSort($sort, SPH_SORT_EXPR);
		$sphinx->setLimits($offset, $length, $page);
		$spData = $sphinx->query($searchWords, 'article_id', 'article, article_delta');

		return $spData;
	}

	/*
	* 按分类
	*/
	public function shareCategory($searchWords, $categoryIdList, $offset, $length, $page){
		$sphinx = new Sphinx();
		if( !empty($categoryIdList) && $categoryIdList[0]){
			$sphinx->setFilter('goods_category_id', $categoryIdList);
		}
		//$sphinx->setSort('like_num');
		//$sphinx->setSort('goods_id');
		$sphinx->setSort('@random', SPH_SORT_EXTENDED);
		$sphinx->setLimits($offset, $length, $page);
		$spData = $sphinx->query($searchWords, 'goods_id', 'goods, goods_delta');

		return $spData;
	}

	/*
	* 可能喜欢的
	*/
	public function shareLike($searchWords, $categoryIdList, $offset, $length, $page){
		$sphinx = new Sphinx();
		if( !empty($categoryIdList) && $categoryIdList[0]){
			$sphinx->setFilter('goods_category_id', $categoryIdList);
		}
		//$sphinx->setSort('like_num');
		//$sphinx->setSort('goods_id');
		$sphinx->setSort('@random', SPH_SORT_EXTENDED);
		$sphinx->setLimits($offset, $length, $page);
		$spData = $sphinx->query($searchWords, 'goods_id', 'goods, goods_delta');

		return $spData;
	}

}
