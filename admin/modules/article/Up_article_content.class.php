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

class Up_article_content extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = FALSE;
	private	$id;
	private $title;
	private $content;

	public function run() {
        if (!$this->_init()) $this->redirect('/bad/badrequest');
		// 新增
		echo "dsaddaaaaaaaaaaaaaa";
			echo $this->content;exit;
		if( $this->title){
			$newId = $this->upArticle();
			$this->redirect('/article/index?aid=' . $newId);
		}

		// 显示
		$this->view->article = $this->getArticleById();
	}

	private function _init() {
		$this->id	        = $this->getRequest('article_id',1);
		$this->title		= $this->getRequest('title' ,0);
		$this->content		= $this->getRequest('editorValue' ,0);

		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}
	private function getArticleById(){
		return Article::getInstance()->getArticleById($this->id,'article_id,title,content');
	}
	private function _check(){
		return TRUE;
	}


	private function addArticle(){

		$data = array(
			'title'				=> $this->title,
			'description'		=> $this->getDescription($this->content),
		);

		$detailData = array(
			//'content'			=> $arrContent['content']
			'content'			=> ''
		);

		$newId =  Article::getInstance()->addArticle($data, $detailData, $this->tag);

		// edit
		$arrContent = $this->getContent($this->content, $newId);
		$description_pic = isset($arrContent['picList']) ? $this->getDescriptionPic($arrContent['picList']) : '';
		$this->editContent($newId, $description_pic, $arrContent['content']);

		return $newId;
	}

	private function editContent($articleId, $description_pic, $content){
		$data = array(
			'description_pic'=> $description_pic
		);

		$detailData = array(
			'content'			=> $content
		);
		return Article::getInstance()->editArticleById($articleId, array(), $detailData);
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
			$newContent['content']	= $content;

			foreach($imgUrlList as $oldUrl){
				// download
				$newUrl = $this->download($oldUrl, $newId);
				if($newUrl){
					$search[]	= $oldUrl;
					$replace[]	= $newUrl;
				}
			}
			
			if(!empty($replace)){
				$newContent['picList'] = $replace;
				$newContent['content'] = str_replace($search, $replace, $content);
			}else{
				$newContent['picList'] = $search;
			}

			return $newContent;
	}

	private function download($url, $articleId){
		// 本域名的不用下载
		$localImage = array('hitaopic.oss.aliyuncs.com', 'hitao.com');
		foreach($localImage as $v){
			if(strpos($v, $url)){
				return FALSE;
			}
		}

		$dir		= ARTICLE_PIC_DIR;
		// file path
		$saveName	= $dir . '/' . $articleId .'_' . Utilities::getUniqueId();
		$saveName	= Download::image($url, $dir, $saveName);
		if($saveName){
			//  上传到阿里云
			$newUrl = Upload::ossServer($saveName);
			if(!$newUrl){
				// url
				$newUrl = str_replace(ARTICLE_PIC_DIR, ARTICLE_PIC_URL, $saveName);
			}

			return $newUrl;
		}
		return FALSE;
	}

}
