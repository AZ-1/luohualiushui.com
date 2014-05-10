<?php
/*
 * 广告管理
 * @author wanghaihong
 */
namespace Gate\Package\Ad;
use Gate\Package\Helper\DBAdBannerHelper;

class Banner{
	private static $instance;
    public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }

	public function getBannerList(){
		$list = DBAdBannerHelper::getConn()->where('type=0 AND is_online=1', array())->limit(4)->order('sort DESC')->fetchAll();
		return $list;
	}


	/*
	 * 手机端接口数据
	 */
	public function getMobileBannerList(){
		$list = DBAdBannerHelper::getConn()->where('type=1', array())->fetchAll();
		return $list;
	}

	public function getBanner($id){
		return DBAdBannerHelper::getConn()->where('id=:id' , array('id' => $id))->fetch();
	}

	/*
	 * 达人
	 */
	public function getDarenBannerList(){
		
		return DBAdBannerHelper::getConn()->where('type=3 AND is_online=1', array())->limit(4)->order('sort DESC')->fetchAll();
	}

	/*
	 *
	 * 手机达人接口数据
	 */
	public function getMobileDarenBannerList(){
		return DBAdBannerHelper::getConn()->where('type=2', array())->fetchAll();
	}

}
