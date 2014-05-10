<?php
/*
 * 
 * @author wanghaihong
 */
namespace Gate\Modules\Article;
use Gate\Package\Article\Category;
use Gate\Package\Article\Article;
use Gate\Package\User\Userinfo;
use Gate\Libs\Base\Download;
use Gate\Libs\Base\Upload;
use Gate\Libs\Utilities;
use Gate\Libs\Image\Imagick;

class Edit_article_content extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    //protected $view_switch = FALSE;
	private	$id;
	private $title;
	private $content;
	private $category_id;
	private $tag;

	public function run() {
        if (!$this->_init()) $this->redirect('/bad/badrequest');
		// 新增
		if( $this->title){
			if($newId = $this->upArticle()){

				if($this->title=='' || $this->content ==''){
					return false;
				}
				if($this->upTagArticle())
					echo "<script> alert('修改成功');</script>";
			}
		}

		// 显示
		$this->view->article         = $this->getArticleById();
		$this->view->articleTagList  = $this->getArticleTagById();
		$this->view->categoryTagList = $this->getCategoryTagList();
	}

	private function upArticle(){
		$arrContent = $this->getContent($this->content, $this->id);
		$description_pic = isset($arrContent['picList']) ? $this->getDescriptionPic($arrContent['picList']) : '';
		return Article::getInstance()->upArticle($this->id,$this->title,$arrContent['content'],$this->category_id, $description_pic);
	}

	private function upTagArticle(){
		return Article::getInstance()->upTagArticle($this->id,$this->tag);
	}

	private function _init() {
		$this->id				= $this->getRequest('article_id',1);
		$this->title			= $this->getRequest('title' ,0);
		$this->category_id		= $this->getRequest('category_id' ,0);
		$this->content			= $this->getRequest('editorValue' ,0);
		$this->tag				= $this->getRequest('tag' ,0);

		$this->content		= trim($this->content);
		$this->title		= trim($this->title);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function getArticleById(){
		return Article::getInstance()->getArticleById($this->id,'article_id,title,content,category_id',0);
	}
	
	private function getArticleTagById(){
		return  Article::getInstance()->getArticleTagById($this->id);
	}

	private function _check(){
		return TRUE;
	}

	private function getCategoryTagList(){
		$categoryTagList = Category::getInstance()->getCategoryTagList();
		$articleTagList  = $this->getArticleTagById();
		foreach($categoryTagList as $ctl){
			if(isset($ctl->tag)){
				foreach($ctl->tag as $ctt){
					$ctt->is_checked = false;
					foreach($articleTagList as $alt){
						if($ctt->tag_id == $alt->tag_id){
							$ctt->is_checked = true;
						}
					}
				}
			}
		}
		return $categoryTagList;
	}


	private function editContent($articleId, $description_pic, $content){
		$data = array(
			'description_pic'=> $description_pic
		);

		$detailData = array(
			'content'			=> $content
		);
		return Article::getInstance()->editArticleById($articleId, $data, $detailData);
	}

	/*
	 * 文章描述信息
	 */
	private function getDescription($content){
		$description = Utilities::htmlStripTags($content);
		$description = mb_substr($description, 0,140);
		return $description;
	}

	/*
	 * 文章描述信息
	 */
	private function getDescriptionPic($picList){
		$picList = array_slice($picList, 0, 3);
		return implode(',', $picList);

		//preg_match_all('/src=&quot;(.*)&quot;/U', $content, $arrDescription_pic);
		//$arrDescription_pic = array_slice($arrDescription_pic[1], 0, 3);
		//return implode(',', $arrDescription_pic);
	}

	/*
	 * 文章内容过滤
	 */
	private function getContent($content, $newId){
			preg_match_all('/src=&quot;(.*)&quot;/U', $content, $arrDescription_pic);
			$imgUrlList = $arrDescription_pic[1];
			$imgUrlList = array_unique($imgUrlList);

			$search		= array();
			$replace	= array();
			$newContent = array();
			$newContent['content']	= $content;

			$isDescPic = true; //是否需要裁缩略图
			foreach($imgUrlList as $oldUrl){
				// 已经存在的图片不用再处理
				//if(strpos($oldUrl, 'hitaopic.oss.aliyuncs.com') && strpos($oldUrl, '_800x_')){
				//	$newContent['picList'][] = str_replace('_800x_', '_210x_', $oldUrl);
				//}else{
					// download
					$download = $this->download($oldUrl, $newId, $isDescPic);

					if($download['status']){
						$search[]	= $oldUrl;
						$replace[]	= $download['url'];
						if(isset($download['descPicUrl'])){ //获取缩略图
							$newContent['picList'][] = $download['descPicUrl'];
						}
						if(count($newContent)==3){ //获得三个缩略图以后就不再需要缩略图
							$isDescPic = FALSE;
						}
					}
				//}
			}
			
			if(!empty($replace)){
				$newContent['content'] = str_replace($search, $replace, $content);
			}else{
				$newContent['picList'] = $search;
			}

			return $newContent;
	}

	private function download($url, $articleId, $isDescPic=FALSE){
		$rs['status'] = FALSE;
		/*
		// 本域名的不用下载
		$localImage = array('hitaopic.oss.aliyuncs.com', 'hitao.com');
		foreach($localImage as $v){
			if(strpos($url, $v)){
				$rs['status'] = true;
				$rs['is_big'] = 1;
				$rs['url'] = $url;
				return $rs;
			}
		}*/

		$dir		= ARTICLE_PIC_DIR;
		// file path
		$saveName	= $dir . '/' . $articleId .'_' . Utilities::getUniqueId();
		$saveName	= Download::image($url, $saveName);
		$newUrl210	= '';
		$newUrl		= '';
		if($saveName){
			// 裁图
			$newUrl = $this->resizeContentPic($saveName, 800);
			if(!$newUrl){
				return false;
			}else{
				$rs['url'] = $newUrl;
				$newUrl300 = $this->resizeContentPic($saveName, 300);
				if($isDescPic){
					// 需要描述缩略图
					$newUrl210 = $this->resizeDescPic($saveName, 210);
				}

				// 删除本地图片
				unlink($saveName);
			}

			$rs['status'] = TRUE;
		}

		if($isDescPic){
			if($newUrl210){
				$rs['descPicUrl'] = $newUrl210;
			}elseif($newUrl && $this->checkUrlPic($newUrl)){
				$rs['descPicUrl'] = $newUrl;
			}elseif($url && $this->checkUrlPic($url)){
				$rs['descPicUrl'] = $url;
			}
		}
		return $rs;
	}

	public function checkUrlPic($url){
		$info = getimagesize($url);
		if( $info[0]<200 &&  $info[1]<200){
			return false;
		}
		return true;
	}

	public function resizeContentPic($saveName, $toWidth){
			if(!file_exists($saveName)){
				return false;
			}
			$ximage = new Imagick();
			$ximage->load($saveName);
			$width = $ximage->getWidth();
			$height = $ximage->getHeight();

			$extension = pathinfo($saveName, PATHINFO_EXTENSION);
			$saveNameNew = str_replace('.'.$extension, '_'.$toWidth.'x_'.'.jpg', $saveName);
			if($width>$toWidth){
				$ximage->resizeTo($toWidth,0);
				$isSave = $ximage->saveTo($saveNameNew);
			}else{
				$isSave = copy($saveName, $saveNameNew);
			}

			if($isSave){
				// 保存到阿里云
				$newUrl = $this->uploadOssserver($saveNameNew);
				if(strpos($newUrl, 'http')===false){ // 返回的不是url
					$rootPath = ROOT_PATH.'/public/';
					$newUrl = str_replace($rootPath, MEI_BASE_URL , $saveNameNew);
				}else{
					unlink($saveNameNew);
				}
				return $newUrl;
			}

			return FALSE;
	}

	/*
	 *
	 */
	public function resizeDescPic($saveName, $toWidth){
			if(!file_exists($saveName)){
				return false;
			}
			$ximage = new Imagick();
			$ximage->load($saveName);
			$width = $ximage->getWidth();
			$height = $ximage->getHeight();

			// 过滤小兔
			if($width <$toWidth && $height <$toWidth){
				return FALSE;
			}

			$extension = pathinfo($saveName, PATHINFO_EXTENSION);
			$saveNameNew = str_replace('.'.$extension, '_'.$toWidth.'x_'.'.jpg', $saveName);
			// 缩放,裁图
			if($width > $height){
				if($height > $toWidth){ // 小于210 不缩放
					$ximage->resizeTo(0, $toWidth);
				}
		        $ximage->crop(0, 0, $toWidth, $toWidth);
			}else{
				if($width > $toWidth){ // 小于210 不缩放
					$ximage->resizeTo($toWidth,0);
				}
		        $ximage->crop(0, 0, $toWidth,$toWidth);
			}

			$isSave = $ximage->saveTo($saveNameNew);
			if($isSave){
				// 保存到阿里云
				$newUrl = $this->uploadOssserver($saveNameNew);
				if(strpos($newUrl, 'http')===false){ // 返回的不是url
					$rootPath = ROOT_PATH.'/public/';
					$newUrl = str_replace($rootPath, MEI_BASE_URL, $saveNameNew);
				}else{
					unlink($saveNameNew);
				}
				return $newUrl;
			}
			return FALSE;
	}

	private function uploadOssserver($saveName){
		// return Upload::ossServer($saveName, 'mei/art/');
		$exe = 'php '. MEI_ROOT_PATH . '/public/run.php article\\\Upload_ossserver ' . $saveName;
		return exec($exe);
	}
}
