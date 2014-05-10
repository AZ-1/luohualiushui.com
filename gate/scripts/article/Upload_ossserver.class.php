<?php
/**
 * 调用 阿里云存储
 * todo:直接在一个脚本中执行裁图并上传阿里云，裁图以后上传阿里云报错，换成再调用这个脚本的方法实现裁图后上传阿里云
 * */
namespace Gate\Scripts\Article;
use \Gate\Libs\Base\Upload;

class Upload_ossserver extends \Gate\Libs\Scripts{
	public function run(){
		$tmpFile  = $this->args[0];
		echo Upload::ossServer($tmpFile, 'mei/art/');
	}
}
