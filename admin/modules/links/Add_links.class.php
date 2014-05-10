<?php
namespace  Gate\Modules\Links;
use \Gate\Package\Links\Links;


class Add_links extends \Gate\Libs\Controller{
     private $Lname;
	 private $Lurl;

	 public function run(){
		 if(!$this->_init()){return false;}


			 $data = array(
				 'name'=> $this->Lname,
		         'url' => $this->Lurl,
			 );

		 $insertLinks = $this->insertLinks($data);
		 if($insertLinks){
			 $this->forward('/links/listlinks',200,'操作成功');
		 }else{
			 $this->forward('/links/addLinks',300,'操作失败');
		 }
	 }

	 private function _init(){
		 $this->Lname     = $this->getRequest('Lname',0);
		 $this->Lurl      = $this->getRequest('Lurl',0);
		 return $this->_check();
	 
	 }
	 private  function getRequest($param ,$isInt=null){
		 return isset ($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : (
		 $isInt === null ?null : ($isInt ? 0 : ''));
	 }
	 private function  _check(){
	   return TRUE;
	 }

	 private function insertLinks($data){
	 
	  return Links::getInstance()->addLinks($data);
	 }

	 private function forward($forwardUrl,$statusCode,$message){
			$array = array(
						'statusCode'	=> $statusCode,
						'message'		=> $message,
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}

}


?>
