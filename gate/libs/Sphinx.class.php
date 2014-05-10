<?php
namespace Gate\Libs;
use  Phplib\Sphinx\SphinxClient; 

class Sphinx{
	private	$sph;
	public function __construct() {
		$this->connect();
	}
	/*
	private static $instance = NULL;
	public static function instance() {
		empty(self::$instance) && self::$instance = new self(); 	
		return self::$instance;
	}
	 */
	protected function connect(){
		$this->sph = new SphinxClient();
		$config = \Phplib\Config::load('Sphinx');
		$this->sph->SetServer( $config->pools->master->host, $config->pools->master->port);
	}

	public function query($searchWords, $aliasId = 'id', $index='*'){
		//$this->sph->SetArrayResult(true);
		$this->sph->setMatchMode(SPH_MATCH_BOOLEAN);
		//$searchWords = '('.str_replace(array(',', ' ', ' '), '|', $searchWords) . ')';
		$spData = $this->sph->Query($searchWords, $index);
		//pr($this->sph);
		if( !isset($spData['matches'])){
			return array();
		}
		$res = new \stdClass();
		foreach($spData['matches'] as $id=>$attrs){
			$attrs['attrs'][$aliasId] = $id;
			$res->data[] = $attrs['attrs'];
		}
		$res->totalNum = $spData['total_found'];
		return $res;
	}

	public function setFilter($field, $list, $exclude=false){
		if( !is_array($list)) die('filter values mast be array');
		$this->sph->SetFilter($field, $list, $exclude);
	}

	public function setLimits($offset, $length ,$page){
		$this->sph->SetLimits($offset, $length, max(70000,($page*50)+100));
	}
	public function setSort($attr, $mode=null){
		if( $mode==null) $mode = (strpos($attr, ',') || strpos($attr, 'asc') || strpos($attr, 'desc') ) ? SPH_SORT_EXTENDED : SPH_SORT_ATTR_DESC;
		$this->sph->SetSortMode($mode,$attr);
	}

	public function setFilterFloatRange($field, $min, $max, $exclude=false){
		$this->sph->SetFilterFloatRange($field, $min, $max, $exclude);
	}

	public function setFilterRange($field, $min, $max, $exclude=false){
		$this->sph->SetFilterRange($field, $min, $max, $exclude);
	}

	public function setMatchMode($mode){
		$this->sph->SetMatchMode($mode);
	}

	/*
	 * $index 为一个或多个索引，逗号分隔
	 * $arrAttrs 是要修改的属性 eg: array('is_delete', 'is_old')
	 * $arrValues 是唯一$id对应的属性值 eg: array($goodsId=>array(1, 1))
	 * 返回实际被更新的文档数目（0或更多），失败则返回-1。
	 */
	public function UpdateAttributes ( $index, $arrAttrs, $arrValues ){
		return $this->sph->UpdateAttributes ( $index, $arrAttrs, $arrValues );
	}
}
