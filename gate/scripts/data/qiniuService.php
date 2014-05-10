<?php
define('ROOT_PATH' , '/home/work/d9app_admin/gate');
require_once(ROOT_PATH.'/libs/qiniu/qbox/rs.php');
require_once(ROOT_PATH.'/libs/qiniu/qbox/client/rs.php');
require_once(ROOT_PATH.'/libs/qiniu/qbox/fileop.php');
require_once(ROOT_PATH.'/libs/qiniu/qbox/authtoken.php');

class QiniuService{
    public function uploadFile($bucket , $key , $localFile){
        $params = array(
            'scope'            => $bucket,
            'expiresIn'        => 86400
        );
        $uploadToken = MakeAuthToken($params);
        list($result, $code, $error) = UploadFile(
            $uploadToken,
            $bucket,
            $key,
            '',
            $localFile,
            '',
            '',
            '');
        if ($code == 200) {
            return TRUE;
        } else {
            $msg = ErrorMessage($code, $error);
            echo "PutFile failed: $code - $msg\n";
            return FALSE;
        }
    }
	public function randomDomain(){
		$domain = Array();
		$domain[] = "http://duobaohui.qiniudn.com/";
		$domain[] = "http://dbh-img-1.qiniudn.com/";
		$domain[] = "http://dbh-img-2.qiniudn.com/";
		$domain[] = "http://dbh-img-3.qiniudn.com/";
		$domain[] = "http://dbh-img-4.qiniudn.com/";
		$domain[] = "http://dbh-img-5.qiniudn.com/";
		$domain[] = "http://dbh-img-6.qiniudn.com/";
		$domain[] = "http://dbh-img-7.qiniudn.com/";
		$domain[] = "http://dbh-img-8.qiniudn.com/";
		$num = mt_rand(0 , count($domain) - 1);
		return $domain[$num];
	}
}
