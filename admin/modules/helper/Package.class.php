<?php
/*
 * 开发助手
 * 生成Package
 */
namespace Gate\Modules\Helper;
use Gate\Package\Demo\Demo;

class Package extends \Gate\Libs\Controller {
	private $name;
	private $packageName;

	public function run() {
		if (!$this->_init()) {
			return FALSE;
		}
		if( !empty($this->name) && !empty($this->packageName)){
			$this->createPackage();
		}
	}

	private function _init() {
		if($_SERVER['SERVER_ADDR']!='192.168.11.10'){
			return FALSE;
		}

		if(!empty($_REQUEST['name'])){
			$this->name = trim($_REQUEST['name']);
			$this->packageName = trim($_REQUEST['package']);
		}

		return TRUE;
			
	}

	/*
	 * create Package
	 */
	private function createPackage(){
		$packageName= ucfirst($this->packageName); // 首字母大写
		$dir	= ROOT_PATH . '/package/' . $this->packageName;
		$name	= ucfirst($this->name); //首字母大写
		$file	= $dir . '/' . $name . '.class.php';

		$code	= <<<CODE
<?php
/*
 * 
 * @author
 */
namespace Gate\Package\\{$packageName};
#use Gate\Package\Helper\

class {$name}{
	private static \$instance;
    public static function getInstance(){
        is_null(self::\$instance) && self::\$instance = new self(); 
        return self::\$instance;
    }

}

CODE;


		@mkdir($dir);
		if(file_exists($file)){
			echo '<br/>Package: ' . $name . '已存在';
			return FALSE;
		}
		//$isC = file_put_contents($file, $code, FILE_APPEND);
		$isC = file_put_contents($file, $code);
		@chmod($file, 0777);

		if($isC){
			echo $name . '<br/> 添加成功';
		}
	}
}
