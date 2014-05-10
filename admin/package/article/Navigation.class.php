<?php
/*
 * 
 * @author
 */
namespace Gate\Package\Article;
use Gate\Package\Helper\DBNavigationHelper;

class Navigation{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }


	function getNavList(){
		$list = DBNavigationHelper::getConn() -> where('pid!=2 and id!=2', array()) ->order('level DESC, sort') -> fetchAssocAll();
		$data = array();
		$sortIds = array();
		foreach($list as $vid=>$v){
			$v->is_hot = 0;
			if(!empty($v->url)){
				parse_str($v->url, $arrUrlParams);
				isset($arrUrlParams['is_hot']) && $arrUrlParams['is_hot'] && $v->is_hot = 1;
			}
			if(isset($data[$vid])){
				$v->child = $data[$vid]->child;
				unset($data[$vid]);
			}
			$v->id = $vid;
			$data[$v->pid]->child[] = $v;
		}
		$newD = array();
		$this->sortNav($data, $newD);
		/*
		foreach($newD as $v){
			echo '<span style="padding-left:'.$v->level.'0px">'.$v->name. $v->sort.'<br/>';
		}
		 */
		return $newD;
	}

	private function sortNav($data, &$newD){
		foreach($data as $v){
			$child = array();
			if(isset($v->child)){
				$child = $v->child;
				unset($v->child);
			}
			if(isset($v->id)){
				$newD[] = $v;
			}
			if( !empty($child)){
				$this->sortNav($child, $newD);
			}
		}
	}

	/*
	 * 添加
	 */
	public function addNavigation($pid, $navigationName, $ename, $url){
		// 参数顺序不能变
		$data = array('parent_cate_id'=>$pid, 'new_cate_name'=>$navigationName, 'e_name'=>$ename, 'url'=>$url);
		return DBNavigationHelper::getConn()->call('hitao_hot_add_navigation_child', $data); 
	}

	/*
	 * 编辑
	 */
	public function editNavigation($id, $data){
		return DBNavigationHelper::getConn()->update($data, 'id=:id', array('id'=>$id)); 
	}

	/*
	 * 获取一条
	 */
	public function getNavById($id, $field='*'){
		return DBNavigationHelper::getConn()->field($field)->where('id=:id', array('id'=>$id))->limit(1)->fetch();
	}


	/*
	 *
	 */
	public function getUrlByEname($e_name){
		// cid
		// filter_key_word
		// akid
		$arrUrlParams = array();
		$row = DBNavigationHelper::getConn()->field('url')->where('e_name=:e_name', array('e_name'=>$e_name))->limit(1)->fetch();
		if(!empty($row->url)){
			parse_str($row->url, $arrUrlParams);
		}
		return $arrUrlParams;
	}

	public function getUrlById($id){
		// cid
		// filter_key_word
		// akid
		$arrUrlParams = array();
		$row = DBNavigationHelper::getConn()->field('url')->where('id=:id', array('id'=>$id))->limit(1)->fetch();
		if(!empty($row->url)){
			parse_str($row->url, $arrUrlParams);
		}
		return $arrUrlParams;
	}
}
