<?php
/*
 * 添加文章 - 用户上传图片
 * @author wanghaihong
 */
namespace Gate\Modules\Article;
use Gate\Libs\base\Upload;
use Gate\Libs\Utilities;

class Upload_pic extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected	$view_switch = false;
	private		$file;

	public function run() {
        if (!$this->_init()) $this->redirect('/bad/badrequest');
		$up = $this->uploadImg();
		if($up['error']==''){
			$this->view->url	= $up['url'];
			$this->view->title	= '';
			$this->view->state	= 'SUCCESS';
		}else{
			$this->view->state	= $up['error'];
		}

	}

	private function _init() {
		$this->file = 'article_pic';
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}
	
	private function uploadImg(){
		$dir		= ARTICLE_PIC_DIR;
		$saveName	= Utilities::getUniqueId();
		$up = Upload:: uploadImage($this->file, $dir, $saveName);
		if($up['error']==''){
			$newUrl = Upload::ossServer($up['path'], 'mei/art/');
			if($newUrl){
				$up['url'] = $newUrl;
				unlink($up['path']);
			}else{
				$up['error'] = '云存储失败，请稍后重试';
				//$up['url'] = ARTICLE_PIC_URL  . '/' . $up['name'];
			}
		}
		return $up;
	}
}
