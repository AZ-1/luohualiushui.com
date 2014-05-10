<?php
namespace Gate\Modules\Links;
use \Gate\Package\Links\Links;

class Listlinks extends \Gate\Libs\Controller{
	protected $view_switch = true;
	private $pageNum;
	private $length = 20;

	public function run(){
		if (!$this->_init()){return false;}
		$this->view->message  =	$this->getMessage();
		$this->page();
	}
   
	private function	_init(){
		$this->pageNum = $this->getRequest('pageNum',1);
	   return $this->_check();	
		}

	private function getRequest($param,$isInt=null){
		return isset ($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : (
		$isInt === null ? null : ($isInt?0 : ''));
	}
	private function getMessage(){
		$this->pageNum = $this->pageNum==0 ? 1 :$this->pageNum;
		$offset               = ($this->pageNum-1)*$this->length;
		return Links::getInstance()->getLinksList($offset,$this->length);
	
	}

	private function  _check(){
	return true;
	}
	private function page(){
	   $page = new \stdClass;
       $page->length   = $this->length;
	   $page->pageNum  = $this->pageNum;
	   $page->totalNum = Links::getInstance()->getLinksTotalNum();
	   $this->view->page =$page;
	
	}


}

?>
