<?php
/*
 * 开发助手
 * 生成package/helper/DBHelper
 */
namespace Gate\Modules\Helper;
use Gate\Package\Demo\Demo;

class Dbhelper extends \Gate\Libs\Controller {
	private $table_name;

	public function run() {
		if (!$this->_init()) {
			return FALSE;
		}
		if( !empty($this->table_name)){
			$this->create();
		}
	}

	private function _init() {
		if($_SERVER['SERVER_ADDR']!='192.168.11.10'){
			return FALSE;
		}

		if(!empty($_REQUEST['table_name'])){
			$this->table_name = trim($_REQUEST['table_name']);
		}

		return TRUE;
			
	}

	private function create(){
		// 
		$tableName	= str_replace('beauty_','',$this->table_name);
		$tableName	= str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($tableName))));
		$dbhelper	= 'DB'. $tableName . 'Helper';
		$file		= ROOT_PATH . '/package/helper/' . $dbhelper . '.class.php';

		//
		$fields		= $this->getTableFields();
		if(!$fields){
			echo '表错误';
			return false;
		}
		$fieldsDesc = $this->getTableFieldsDesc();
		$code = <<<CODE
<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class {$dbhelper} extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= '{$this->table_name}';
	const _FIELDS_	= '{$fields}';

	{$fieldsDesc}
}
CODE;


		//$isC = file_put_contents($file, $code, FILE_APPEND);
		$isC = file_put_contents($file, $code);

		if($isC){
			echo $dbhelper . ' 添加成功';
		}
	}

	/*
	 *
	 */
	public function getTableFields(){
		$list = Demo::getInstance()->getTableFields($this->table_name);
		if(empty($list)){
			return FALSE;
		}
		foreach($list as $v){
			$fieldsList[] = $v->COLUMN_NAME ;
		}
		return implode(",", $fieldsList);
	}

	public function getTableFieldsDesc(){
		$list = Demo::getInstance()->getTableFields($this->table_name);
		if(empty($list)){
			return FALSE;
		}
		foreach($list as $v){
			$fieldsList[] = '// '. $v->COLUMN_NAME . '					-- ' . $v->DATA_TYPE . '	 ' . $v->COLUMN_COMMENT;
		}
		return implode("\n	", $fieldsList);
	}

}
