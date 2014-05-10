<?php
/*
 * Modules 业务注释
 * @作者
 */
// 命名空间，每个Modules文件都要写,首字母大写
namespace Gate\Modules\Demo;

// 引用其他的package，libs ，插件等
use \Gate\Package\Demo\Demo AS DemoModel;

/*
 * Modules名称:和文件名相同, 首字母大写
 */
class Index extends \Gate\Libs\Controller {

	// 全局参数
	// TRUE: 输出view 页面; FALSE: 输出json格式数据
	//protected $view_switch = FALSE; 

	// 本接口需要的属性变量，自定义
	private $test;

	/*
	 * 单一出口
	 */
	public function run() { 
		//初始化
		if (!$this->_init()) { 
			return FALSE;
		}

		// 一般以单例模式访问package ，libs 等接口
		$data = DemoModel::getInstance()->getDemoInfo();

		$this->view->data = $data;
	}

	/*
	 * 本接口所有提交的参数(post,get,request) 都在这里交接给本接口的属性变量
	 */
	private function _init() {
		// 调用验证
		if( !$this->check()){
			return FALSE;
		}
		return TRUE;
	}

	/*
	 * 获取 $_GET, $_POST 传参
	 *
	 */
	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param])
				? $this->request->REQUEST[$param]
				: ($isInt===null ? null : ($isInt ? 0 : ''));
	}


	 /*
	  * 进行验证
	  */
	private function check(){
		return TRUE;
	}


}
