<?php
namespace Gate\Package\Links;

use \Gate\Package\Helper\DBLinksHelper;

class Links{
  private static $instance;
  public static function getInstance(){
	  is_null(self::$instance) && self::$instance = new self ();
	  return self::$instance;
  }  
  public function addLinks($data){
      return DBLinksHelper::getConn()->insert($data);
  }
  public function getLinks(){
	  return DBLinksHelper::getConn()->fetchAll();

  }

  public function upLinks($data,$id){
	  $where  = 'links_id=:links_id';
	  $params = array ('links_id'=> $id);
    return DBLinksHelper::getConn()->update($data,$where,$params);
  }

  public function delLinks($id){
     $where  = 'links_id =:links_id';
	 $params = array ('links_id'=>$Lid);
	 return DBLinksHelper::getConn()->delete($where,$params);
  }

  public  function getLinksOne($id){
    return DBLinksHelper::getConn()->where('links_id=:links_id',array('links_id'=>$id))->fetch();
  }

  public function getLinksTotalNum(){
    return DBLinksHelper::getConn()->fetchCount();
  
  }

  public function getLinksList($offset=0,$length,$fields='*'){
	  $res =DBLinksHelper::getConn()->field('links_id,name,url,logo,status')->limit($offset,$length)->fetchAll();
	  return $res;
   }
}



?>
