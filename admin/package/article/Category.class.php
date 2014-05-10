<?php
/*
 * 
 * @wanghaihong
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBCategoryHelper;
use Gate\Package\Helper\DBHotCategoryHelper;
use Gate\Package\Article\Tag;

class Category{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	
	/*
	 * 新增节点
	 * new_cate_name 中文名称
	 * new_cate_ename 别名(ex: english)
	 * parent_cate_id 父id
	 */
	function addChild($new_cate_name ,  $parent_cate_id){
		$isIn = DBCategoryHelper::getConn()->call('hitao_beauty_add_category_child', array(
				'parent_cate_id'	=> $parent_cate_id, 
				'new_cate_name'		=> $new_cate_name
			));
		
		return $isIn;
	}

	/*
	* 所有子节点
	*/
	public function getChildren($categoryId){
		$sql = "
			select node.id, node.name
			from :category as node, :category as parent
			where parent.id IN(:cata_id)
			and status=0
			and node.left_num between parent.left_num and parent.right_num
			and node.left_num = node.right_num-1	";

		return DBCategoryHelper::getConn()->fetchAll($sql, array('cata_id'=>$categoryId, 'category'=>DBCategoryHelper::_TABLE_));
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



	/*
	 * 移动节点
	 */
	public function movecategory($id, $toParentId){
		return DBCategoryHelper::getConn()->call('hitao_beauty_move_category_child', array('cate_id'=>$id, 'to_parent_id'=>$toParentId));
	}

	/*
	 * 排序
	 */
	public function sortcategory($id , $sort){
		return DBCategoryHelper::getConn() -> update(array('sort' => $sort) , 'id=:id' , array('id' => $id));
	
	}

	/*
	 * 默认的删除，移动到 default下 
	 */
	public function delToDefault($id){
		return DBCategoryHelper::getConn()->call('hitao_beauty_move_category_child', array('cate_id'=>$id, 'to_parent_id'=>2));
	}

	/*
	 * 真实的删除，会把所有子节点一起删除，慎用
	 */
	public function delCategory($id){
		return DBCategoryHelper::getConn()->call('del_category', array('cataId'=>$id));
	}

	/*
	 * 所有分类
	 */
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
		return DBCategoryHelper::getConn()->field('id AS pk, id,name,pid') ->where('status=0 AND id>2 AND pid=1', array()) ->order('level DESC, sort, id')-> fetchAssocAll();
	}

		
	public function getCategorybyIds($ids){
		$list	= DBCategoryHelper::getConn()->field('id AS pk, id, name,pid') ->where('id IN(:id)', array("id"=>$ids))->fetchAssocAll();
		return $list;
	}

	/*
	 * 广场热门文章的分类, 不同
	 */
	public function getHotCategory(){
		return DBHotCategoryHelper::getConn()->fetchAll();
	}

	/*
	 * 更改分类名称
	 */
	public function editCategoryName($categoryId, $name){
		return DBCategoryHelper::getConn()->update(array('name'=>$name), 'id=:id', array('id'=>$categoryId));
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
}
