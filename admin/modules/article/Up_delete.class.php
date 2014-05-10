<?php
/*
 * 进行文章回收
 * @author huizai
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Article;

class Up_delete extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private $aid;

	public function run() {

		if (!$this->_init()) {return FALSE;}
			$this->recycle();
	}


	/*
	 *回收站
	 */
	private function recycle(){
		return Article::getInstance()->recycle($this->aid);
	}

	private function _init() {
		  $this->aid		= $this->getRequest('aid',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

}
