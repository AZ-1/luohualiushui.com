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

class Todo extends \Gate\Libs\Scripts{

	public function run(){
		$pic_url = '/home/work/mei.hitao.com/data/ad/banner/item1.png';
		$pic_url2 = '/home/work/mei.hitao.com/data/topic/images/a1-1.jpg';
		        $ximage = new Imagick();
		        $ximage->load($pic_url);
			$width = $ximage->getWidth();
			$height = $ximage->getHeight();
			if($width > $height){
				$ximage->resizeTo(0, 210);
		        $ximage->crop(0, 0, 210,210);
			}else{
				$ximage->resizeTo(210,0);
		        $ximage->crop(0, 0, 210,210);
			}
		        //$ximage->crop(0, 0, 210,210);
		        $isSave = $ximage->saveTo($pic_url2);
				var_dump($isSave);
	}
}
