<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Topic;
use Gate\Package\Maidan\Maidan;
use Gate\Package\Article\Topic;
use Gate\Package\Article\Article;
use Gate\Libs\Utilities;
use Gate\Libs\Base\Upload;

class Edit_topic_article extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
//    protected $view_switch = FALSE;
	private	$length = 20;
	private	$pageNum;
	private $article_id;	
	private $topic_id;
	private $isUp;
	private $pic='pic';

	public function run() {
		if (!$this->_init()) {return FALSE;}
		if($this->isUp){
			$up = $this->uploadImg($this->pic);
			if($up['error']==''){
				$isAdd = $this->update_title_pic($this->article_id,$up['url']);
				if($isAdd){
					$this->forward('/topic/topic_article?id='.$this->id);
				}
			}	
		}
		$this->view->title_pic = $this->getTitlePic();
		$this->view->isUp = $this->isUp;
		$this->view->id = $this->id;
		$this->view->article_id = $this->article_id;
	}

	private function getTitlePic(){
		return Article::getInstance()->getArticleById($this->article_id,'title_pic',0);	
	}

	private function page(){
		$page = new \stdClass;
		$page->length	= $this->length;
		$page->pageNum	= $this->pageNum;
		$page->totalNum = Topic::getInstance()->getTopicArticleNum($this->id);
		$this->view->page = $page;
	}
	private function forward($forwardUrl){
			$array = array(
						'statusCode'	=> '200',
						'message'		=> '操作成功',
						'navTabId'		=> '',
						'rel'			=> '',
						'callbackType'	=> 'forward',
						'forwardUrl'	=> $forwardUrl);
			die( json_encode($array));
	}

	private function _init() {
		 $this->pageNum    = $this->getRequest("pageNum",1);
		 $this->article_id = $this->getRequest("aid",1);
		 $this->id         = $this->getRequest("id",1);
		 $this->isUp	   = $this->getRequest('isUp',1);
		return $this->_check();
	}

	private function getRequest($param, $isInt=null){
		return isset($this->request->REQUEST[$param]) ? $this->request->REQUEST[$param] : ($isInt===null ? null : ($isInt ? 0 : ''));
	}

	private function _check(){
		return TRUE;
	}

	private function uploadImg($pic){
		$dir		= TOPIC_PATH;
		$saveName	= Utilities::getUniqueId();
		$up = Upload::uploadImage($pic, $dir, $saveName);
		if($up['error']==''){
			$newUrl = Upload::ossServer($up['path'], 'mei/art/');
			if($newUrl){
				$up['url'] = $newUrl;
				// 删除本地图片
				unlink($up['path']);
			}else{
				$up['error'] = '云存储失败，请稍后重试';
				//$up['url'] = str_replace(TOPIC_PATH, TOPIC_URL, $up['path']);
			}
		}
		return $up;
	}


	private function update_title_pic($article_id,$title_pic){
		$data = array('title_pic'=>$title_pic);
		$update = Article::getInstance()->editArticleById($article_id,$data);		
		return $update;
	}
}
