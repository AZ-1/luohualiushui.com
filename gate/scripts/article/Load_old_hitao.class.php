<?php
/**
 *
 * */
namespace Gate\Scripts\Article;
use \Gate\Package\Helper\DBArticleDetailHelper;
use \Gate\Package\Helper\DBArticleHelper;
use \Gate\Package\Article\Article;
use \Gate\Package\User\Register;
use \Gate\Package\Article\Category;
use \Gate\Libs\Utilities;
use \Gate\Libs\Image\Imagick;
use \Gate\Libs\Base\Upload;
use \Gate\Libs\Base\Download;

class Load_old_hitao extends \Gate\Libs\Scripts{

	public function run(){
		DBArticleDetailHelper::getConn()->dump(0);
		$lastId = 0;
		$break = 0;
		while( !$break){
			$list = $this->getOldItemList($lastId);

			if(!empty($list)){
				//$lastId = $this->load($list);
				//$lastId = $this->loadAttr($list);
				//$lastId = $this->resetDescription_pic($list);
				$lastId = $this->resetContentPic($list);
			}else{
				$break = 1;
			}
		}
		
		// user
		//$this->addUser();

		// category  reset 
		//$this->resetCategory();
	}

	/*
	 * 重置内容图片
	 */
	private function resetContentPic($list){
		foreach($list as $v){
			$id = $v->article_id;
			$newImglist = $this->getContentPic($v->content, $id);
			if(!$newImglist){
				continue;
			}
			// 替换图片
			$oldImglist =array_keys($newImglist);
			$content = str_replace($oldImglist, $newImglist, $v->content);

			$isUp = DBArticleDetailHelper::getConn()->update(array('content'=>$content), 'article_id=:aid', array('aid'=>$v->article_id) , 'beauty_article_detail');
			if($isUp){
				echo '重置article的 content-' . $v->article_id ."\n";
			}
		}
		return $id;
	}


	/*
	 * 标签
	 */
	public function loadAttr($list){
		foreach($list as $vl){
			$lastId =  $vl->id;
			$categoryId = $this->getCategoryId($vl->cate_id);
			if( !$categoryId){
				//continue;
			}
			$vl->attr = trim($vl->attr);
			if(empty($vl->attr)){
				continue;
			}
			$vl->attr = unserialize($vl->attr);
			if(empty($vl->attr)){
				continue;
			}
			foreach($vl->attr as $va){
				if(trim($va)==''){
					continue;
				}

				$arrTag = explode('/', $va);
				foreach($arrTag as $vt){
					// add tag
					$tagData  = array('name'=>$vt , 'category_id'=>$categoryId);
					$tagId = $this->addTag($tagData);

					// add tag article
					$tagArtData = array('tag_id'=>$tagId, 'article_id'=>$vl->id);
					print_r($tagArtData);
					$this->addTagArticle($tagArtData);
				}
			}

		}
		return $lastId;
	}

	private function addTag($data){
		$row = DBArticleHelper::getConn()->from('beauty_tag')->field('tag_id')->where('name=:name', array('name'=>$data['name']))->fetch();
		if(!$row){
			$newId = DBArticleHelper::getConn()->insert($data, 'beauty_tag');
		}else{
			$newId = $row->tag_id;
		}
		return $newId;
	}

	private function addTagArticle($data){
		//$row = DBArticleHelper::getConn()->from('beauty_tag_article')->field('id')->where('tag_id=:tag_id AND article_id=:article_id', array('tag_id'=>$data['tag_id'], 'article_id'=>$data['article_id']))->fetch();
		return DBArticleHelper::getConn()->insert($data, 'beauty_tag_article');
	}

	/*
	 * 文章
	 */
	public function load($list){
		$userList = array();
		foreach($list as $v){
			$data = array(
				'article_id'		=> $v->id,
				'title'				=> $v->title,
				'category_id'		=> $v->cate_id,
				'user_id'			=> $v->uid,
				'description'		=> $this->getDescription($v->intro),
				'description_pic'	=> $this->getDescriptionPic($v->intro),
				'is_check'			=> $this->getStatus($v->status),
				'quality'			=> $this->getQuality($v->quality),
				'no_pass_reason'	=> strval($v->refuseReason),
				'create_time'		=> date("Y-m-d H:i:s", $v->add_time),
				'update_time'		=> $v->edit_time,
			);

			$detailData = array(
				'content'			=> $v->intro
			);
			return $this->addArticle($data,$detailData);
			
			// user
			//$userList[$v->uid] = new \stdClass;
			//$userList[$v->uid]->user_id = $v->uid;
			//$userList[$v->uid]->realname = $v->uname;
		}
	}

	/*
	 * 更新一下description
	 */
	private function resetDescription($list){
		foreach($list as $v){
			$description = $this->getDescription($v->intro);
			$isUp = DBArticleDetailHelper::getConn()->update(array('description'=>$description), 'article_id=:aid', array('aid'=>$v->id) , 'beauty_article');
			if($isUp){
				echo '重置article的  description-' . $v->id ."\n";
			}
			$id = $v->id;
		}
		return $id;
	}


	/*
	 * 本系统 表
	 */
	private function resetDescription_pic($list){
		foreach($list as $v){
			$id = $v->article_id;
			$description_pic = $this->getDescriptionPic($v->content, $id);
			if(!$description_pic){
				continue;
			}
			$isUp = DBArticleDetailHelper::getConn()->update(array('description_pic'=>$description_pic), 'article_id=:aid', array('aid'=>$v->article_id) , 'beauty_article');
			if($isUp){
				echo '重置article的  description_pic-' . $v->article_id ."\n";
			}
		}
		return $id;
	}

/*
 * 从旧的表数据取图
	private function resetDescription_pic($list){
		foreach($list as $v){
			$description_pic = $this->getDescriptionPic($v->intro);
			$isUp = DBArticleDetailHelper::getConn()->update(array('description_pic'=>$description_pic), 'article_id=:aid', array('aid'=>$v->id) , 'beauty_article');
			if($isUp){
				echo '重置article的  description_pic-' . $v->id ."\n";
			}
			$id = $v->id;
		}
		return $id;
	}
 */	
	private function init(){
		
	}

	private function addArticle($data, $detailData){
		$row = DBArticleDetailHelper::getConn()->from('beauty_article')->field('article_id')->where('article_id=:id', array('id'=>$data['article_id']))->fetch();
		if(!$row){ // 新数据
			$newId =  Article::getInstance()->addArticle($data, $detailData);
			echo '文章添加-'. $newId."\n";
		}else{ // 已存在
		
			DBArticleHelper::getConn()->update($data, 'article_id=:article_id', array('article_id'=>$data['article_id']), 'beauty_article');
			DBArticleDetailHelper::getConn()->update($detailData, 'article_id=:article_id', array('article_id'=>$data['article_id']), 'beauty_article_detail');
			echo '更改-'. $data['article_id']."\n";
			
			$newId = $data['article_id'];	
		}

		return $newId;
	}

	private function addUser(){
		$sql = "SELECT uid,uname FROM pin_item GROUP BY uid;";
		$userList = DBArticleDetailHelper::getConn()->fetchAll($sql);

		foreach($userList as $v){
			$row = DBArticleDetailHelper::getConn()->from('beauty_user_profile')->field('user_id')->where('user_id=:uid', array('uid'=>$v->uid))->fetch();
			if($row){
				echo '已存在:' . $row->user_id . "\n";
				continue;
			}

			$proData = array();
			$proData['user_id']			= $v->uid;
			$proData['realname']		= $v->uname;
			$proData['password']		= '';
			$proData['cookie']			= Utilities::getUniqueId();
			$proData['avatar_c']		= 'http://mei.hitao.com/static/images/default.gif';
			$proData['invite_code']		= Utilities::getUniqueId(); // 邀请码
			$proData['active_code']		= Utilities::getUniqueId(); // 激活码
			$proData['last_login_time'] = time();
			$proData['description']		= '嗨淘网';

			$exData['gender']			= 1; 

			$newId = Register::getInstance()->addUser($proData, $exData);

			echo '用户添加-'. $newId."\n";
		}
	}

	/*
	 * 文章
	 */
	private function getOldItemList($lastId){
		//$row = DBArticleDetailHelper::getConn()->from('beauty_article')->field('article_id')->order('article_id DESC')->limit(1)->fetch();
		//$lastId = $row->article_id;
		//return DBArticleDetailHelper::getConn()->from('pin_item')->where('id > :id', array('id'=>$lastId))->order('id')->limit(10)->fetchAll();
		$ids =  DBArticleDetailHelper::getConn()->from('beauty_article')->field('article_id')->where('article_id > :id ', array('id'=>$lastId))->order('article_id')->limit(10)->fetchCol();
		return DBArticleHelper::getConn()->from('beauty_article_detail')->where('article_id IN(:ids)', array('ids'=>$ids))->fetchAll();
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
	 *
	 */
	private function getContentPic($content, $id){
		preg_match_all('/src=&quot;(.*)&quot;/Uis', $content, $arrDescription_pic);
		if(empty($arrDescription_pic[1])){
			preg_match_all('/src\s*=\s*[\"|\']?\s*([^>\"\'\s]*)/i', $content, $arrDescription_pic);
		}
		$arr = array();
		foreach($arrDescription_pic[1] as $v){
			if(strrpos($v, '.gif') || strpos($v, 'http')===false){
				continue;
			}
			$arr[] = $v;
		}
		if(empty($arr)){
			return false;
		}

		$imglist = array();
		$public = __DIR__ . '/../../public';
		$article_dir = __DIR__ . '/../../public/data/article/old_pic';
		if( !file_exists($article_dir)){
			mkdir($article_dir, 0755);
		}
		foreach($arr as $k=>$vurl){
			// 保存图片
			$tmpFile = $article_dir . '/' . $id .'_' .Utilities::getUniqueId();
			$tmpFile = Download::image($vurl, $tmpFile);
			// 裁图
			$newUrl = $this->resizeContentPic($tmpFile, 800);
			if($newUrl){
				$newUrl300 = $this->resizeContentPic($tmpFile, 300);
				$imglist[$vurl] = $newUrl;
				unlink($tmpFile);
			}
		}
		return $imglist;
	}

	/*
	 * 文章描述图片
	 */
	private function getDescriptionPic($content, $id){
		preg_match_all('/src=&quot;(.*)&quot;/Uis', $content, $arrDescription_pic);
		if(empty($arrDescription_pic[1])){
			preg_match_all('/src\s*=\s*[\"|\']?\s*([^>\"\'\s]*)/i', $content, $arrDescription_pic);
		}
		$arr = array();
		foreach($arrDescription_pic[1] as $v){
			if(strrpos($v, '.gif') || strpos($v, 'http')===false){
				continue;
			}
			$arr[] = $v;
		}


		$imglist = array();
		$public = __DIR__ . '/../../public';
		$article_dir = __DIR__ . '/../../public/data/article/old_pic';
		if( !file_exists($article_dir)){
			mkdir($article_dir, 0755);
		}
		foreach($arr as $k=>$vurl){
			// 保存图片
			$tmpFile = $article_dir . '/' .Utilities::getUniqueId();
			$tmpFile = Download::image($vurl, $tmpFile);
			// 擦图
			$tmpFile = $this->resize($tmpFile);

			if($tmpFile){
				// 保存到阿里云
				$newUrl = $this->uploadOssserver($tmpFile);
				if(strpos($newUrl, 'http')===false){ // 返回的不是url
					$newUrl = str_replace($public, 'http://mei.hitao.com' , $tmpFile);
				}else{
					unlink($tmpFile);
				}
				$imglist[] = $newUrl;
			}

			if(count($imglist)==3){
				break;
			}
		}

		//print_r($content);
		//var_dump($arrDescription_pic);die;
		//$arrDescription_pic = array_slice($arr, 0, 3);
		return implode(',', $imglist);
	}


	/*
	 * 分类
	 */
	private function getOldCate(){
		$sql = "SELECT id, name, pid
			FROM  `pin_item_cate` 
			WHERE id
			IN (
				
				SELECT  `cate_id` 
				FROM pin_item
				WHERE cate_id >400
				GROUP BY cate_id
			) AND pid=0
			";

		return DBArticleDetailHelper::getConn()->fetchAll($sql, array());
	}

	public function getCategoryId($oldCid){
			$row = DBArticleDetailHelper::getConn()->from('beauty_category')->field('id, name')->where('source_cats_id=:id', array('id'=>$oldCid))->fetch();
			if(!isset($row->id)){
				return false;
			}
			return $row->id;
	}

	private function addCategory($list){
		foreach($list as $v){
			$row = DBArticleDetailHelper::getConn()->from('beauty_category')->field('id, name')->where('name=:name', array('name'=>$v->name))->fetch();
			DBArticleDetailHelper::getConn()->update(array('source_cats_id'=>$v->id), 'id=:id', array('id'=>$row->id) , 'beauty_category');
			$arr = array('资讯', '评测', '教程');
			$arr_e = array('zixun', 'pingce', 'jiaocheng');
			foreach($arr as $k=>$vname){
				$new_cate_name	= $vname;
				$new_cate_ename = $row->id . '_' . $arr_e[$k];
				$parent_cate_id = $row->id;
				Category::getInstance()->addChild($new_cate_name , $parent_cate_id);
			}
		}
	}

	private function resetCategory(){
		$cidList = DBArticleDetailHelper::getConn()->from('beauty_category')->field('id, source_cats_id')->fetchAll();
		foreach($cidList as $v){
			$isUp = DBArticleDetailHelper::getConn()->update(array('category_id'=>$v->id), 'category_id=:scid', array('scid'=>$v->source_cats_id) , 'beauty_article');
			if($isUp){
				echo '重置article的  category id-' . $v->id ."\n";
			}
		}
	}


	private function getStatus($status){
		$rs = 0;
		switch($status){
			case '通过': 
				$rs = 2;
				break;
			case '未通过': 
				$rs = 1;
				break;
			case '未审核': 
				$rs = 0;
				break;
		}
		return $rs;
	}


	private function getQuality($quality){
		$rs = 0;
		switch($quality){
			case '上': 
				$rs = 1;
				break;
			case '中': 
				$rs = 2;
				break;
			case '下': 
				$rs = 3;
				break;

		}
		return $rs;
	}

	private function cropImage($pic){
	}


	function upload_by_file($obj, $file_path){
		$bucket		= 'hitaopic';
		$object		= 'mei/art/' . basename($file_path);
		
		$response = $obj->upload_file_by_file($bucket,$object,$file_path);
		if(isset($response->header['x-oss-request-url'])){
			return $response->header['x-oss-request-url'];
		}else{
			return false;
		}

		//print_r($response);
		//_format($response);
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
				$public = __DIR__ . '/../../public';
				$newUrl = $this->uploadOssserver($saveNameNew);
				if(strpos($newUrl, 'http')===false){ // 返回的不是url
					$newUrl = str_replace($public, 'http://mei.hitao.com' , $saveNameNew);
				}else{
					unlink($saveNameNew);
				}
				return $newUrl;
			}

			return FALSE;
	}

	public function resize($saveName){
			$ximage = new Imagick();
			$ximage->load($saveName);
			$width = $ximage->getWidth();
			$height = $ximage->getHeight();
			// 过滤小兔
			
			if($width <210 && $height <210){
				return $saveName;
			}

			// 缩放并裁图
			if($width > $height){
				$ximage->resizeTo(0, 210);
		        $ximage->crop(0, 0, 210,210);
			}else{
				$ximage->resizeTo(210,0);
		        $ximage->crop(0, 0, 210,210);
			}

			$isSave = $ximage->saveTo($saveName);
			if($isSave){
				return $saveName;
			}
			return FALSE;
	}

	private function uploadOssserver($saveName){
		// return Upload::ossServer($saveName, 'mei/art/');
		$exe = 'php '. ROOT_PATH . '/public/run.php article\\\Upload_ossserver ' . $saveName;
		return exec($exe);
	}


}
