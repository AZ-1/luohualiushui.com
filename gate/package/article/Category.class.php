<?php
/*
 * 
 * @wanghaihong
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBCategoryHelper;
use Gate\Package\Article\Tag;

class Category{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	

	/*
	* 所有子节点
	*/
	public function getChildren($categoryId){
		$sql = "
			select node.id, node.name
			from beauty_category as node, beauty_category as parent
			where parent.id IN(:cata_id)
			and node.status=0
			and node.left_num between parent.left_num and parent.right_num
			and node.left_num = node.right_num-1	";
		return DBCategoryHelper::getConn()->fetchAll($sql, array('cata_id'=>$categoryId));
	}

	/*
	 *
	 */
	public function getChildrenArticleNum($categoryId){
		$sql = "
			select SUM(node.article_num_in) as article_num
			from beauty_category as node, beauty_category as parent
			where parent.id IN(:cata_id)
			and node.status=0
			and node.left_num between parent.left_num and parent.right_num
			and node.left_num = node.right_num-1	";
		 $row = DBCategoryHelper::getConn()->fetch($sql, array('cata_id'=>$categoryId));
		return $row->article_num;
	}

	/*
	 * 分类的文章统计(审核通过的)
	 */
	public function getArticleCategoryTotalNum($categoryId){
		//return DBArticleHelper::getConn()->where('is_delete=0 AND is_check=2', array())->fetchCount();
		return $this->getChildrenArticleNum($categoryId);
	}

	/*
	 * 所有文章的总数(审核通过的)
	*/
	public function getArticleTotalNum(){
		// 根分类的id 是 1
		return $this->getChildrenArticleNum(1);
	}

	/*
	* 所有上级父节点 直系祖宗十八代
	*/
	public function getAllParent($categoryId, $field){
		$sql = "
			SELECT	:field
			FROM	category AS parent, category AS node
			where	node.id =:cataId 
				AND	  node.left_num BETWEEN parent.left_num AND parent.right_num
				AND	  parent.id !=1
				AND	  node.status = 0
			ORDER BY  parent.level
		";


		$field = str_replace(',', ',parent.', $field);
		$field = 'parent.'. $field;
		$sql = str_replace(':field', $field, $sql);
		$param = array(
			'cataId'=> $categoryId
		);

		return DBCategoryHelper::getConn()->fetchAll($sql, $param);
	}


	public function getCategoryList(){
		$list	= DBCategoryHelper::getConn()->field('id,name,pid') ->where('status=0 AND id>2', array()) ->order('level DESC, sort, id')-> fetchAll();
		if(empty($list)){return array();}
		$data = array();
		foreach($list as $v){
			// url
			if(!empty($v->url)){
				parse_str($v->url, $arrUrlParams);
				$v->is_hot = isset($arrUrlParams['is_hot']) && $arrUrlParams['is_hot'] ? 1 : 0;
			}
			//返回多维数组
			if(isset($data[$v->id])){
				$v->child = $data[$v->id]->child;
				unset($data[$v->id]);
			}

			$data[$v->pid]->child[] = $v;
		}

		return current($data)->child;
	}

	public function getTopCategoryList(){
		return DBCategoryHelper::getConn()->field('id,name')->where('status=0 AND id>2 AND pid=1', array()) ->order('level DESC, sort, id')->fetchAll();
	}

	/*
	 * 获取全部分类和分类的标签
	 */
	public function getCategoryTagList(){
		$categoryList = $this->getCategoryList();
		$tagList = Tag::getInstance()->getAllTagList();
		$cid = array();
		foreach($categoryList as $vc){
			$cid[$vc->id][] = $vc->id;
			foreach($vc->child as $vcc){
				$cid[$vc->id][] = $vcc->id;
			}
		}

		foreach($tagList as $vt){
			foreach($categoryList as &$vc){
				if(in_array($vt->category_id, $cid[$vc->id])){
					$vc->tag[] = $vt;
				}
			}
		}
		return $categoryList;
	}

	/*
	 * 
	 */
	public function getHotCategoryList(){
		$list	= DBCategoryHelper::getConn()->field('id,name,pid') ->where('pid=1', array()) ->order('level DESC, sort, id')->limit(0, 5)-> fetchAll();

		return $list;
	}

	/*
	 * 新增节点
	 * new_cate_name 中文名称
	 * new_cate_ename 别名(ex: english)
	 * parent_cate_id 父id
	 */
	function addChild($new_cate_name , $parent_cate_id){
		$isIn = DBCategoryHelper::getConn()->call('hitao_beauty_add_category_child', array(
				'parent_cate_id'	=> $parent_cate_id, 
				'new_cate_name'		=> $new_cate_name
			));
		
		return $isIn;
	}

	/*
	 *
	 */
	public function getCategoryByCids($categoryIds, $field='*'){
		$categoryList	= DBCategoryHelper::getConn()->field($field)->where('id IN(:cid)', array('cid'=>$categoryIds))->fetchAssocAll();
		return $categoryList;
	}
}
