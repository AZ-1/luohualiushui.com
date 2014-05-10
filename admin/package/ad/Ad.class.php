<?php
/*
 * 广告管理
 * @author wanghaihong
 */
namespace Gate\Package\Ad;
use Gate\Package\Helper\DBAdBannerHelper;

class Ad{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	/*
	 * type : 手机端 / web网站
	 */
	public function getBannerList($type, $offset, $length){
		$list = DBAdBannerHelper::getConn()->where('type=:type', array('type'=>$type))->order('is_online DESC ')->limit($offset, $length)->fetchAll();
		return $list;
	}

	/*
	 * 总数
	 */
	public function getBannerTotalNum($type){
		return DBAdBannerHelper::getConn()->where('type=:type' , array('type'=>$type))->fetchCount();
	}

	public function addBanner($data){
		$isIn = DBAdBannerHelper::getConn()-> insert($data);
		return $isIn;
	}

	public function editBanner($id,$title,$link_url,$pic_url,$file_path){
		$data	= array("title"=>$title,"link_url"=>$link_url,"file_path"=>$file_path);
		if($pic_url != ""){
			$data['pic_url'] = $pic_url;
		}
		$where	= 'id=:gid';
		$params = array('gid'=>$id);
		return DBAdBannerHelper::getConn()->update($data, $where, $params); 
	}

	public function delBanner($id){
		$isDel = DBAdBannerHelper :: getConn() -> delete('id=:id' , array('id' => $id));
		return $isDel;
	}

	/*
	 *banner上线下线
	 */
	public function onLineBanner($id){
		return DBAdBannerHelper::getConn()->update(array('is_online'=>1),'id=:id',array('id'=>$id));
	}
	public function offLineBanner($id){
		return DBAdBannerHelper::getConn()->update(array('is_online'=>0 , 'sort'=>0),'id=:id',array('id'=>$id));
	}

	public function editBannerSort($id,$sort){
		return DBAdBannerHelper::getConn()->update(array('sort'=>$sort),'id=:id',array('id'=>$id));
	}

	public function getBanner($id){
		return DBAdBannerHelper::getConn()->where('id=:id' , array('id' => $id))->fetch();
	}


	public function upBanner($id,$title,$link_url,$pic_url,$file_path){
		$data = array("title"=>$title,"link_url"=>$link_url,"file_path"=>$file_path);
		if($pic_url){
			$data['pic_url'] = $pic_url;
		}
		return DBAdBannerHelper::getConn()->update($data,'id=:id',array("id"=>$id));
	}

	/*
	 * 
	 */
	public function bannerType(){
		$type = new \stdClass;
		$type->web = array(
					'guang' => 0,
					'daren' => 3,
				);
		$type->mobile = array(
					'guang' => 1,
					'daren' => 2,
				);
		return $type;
	}

}
