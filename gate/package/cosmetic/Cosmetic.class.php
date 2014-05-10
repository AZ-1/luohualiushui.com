<?php
/*
 * @author xujiantao
 */
namespace Gate\Package\Cosmetic;
use Gate\Package\Helper\DBCosmeticBrandHelper AS Brand;
use Gate\Package\Helper\DBCosmeticFuntionalityHelper AS Category;
use Gate\Package\Helper\DBCosmeticGoodsBasicInfoHelper AS Goods;
use Gate\Package\Helper\DBCosmeticGoodsCommentHelper AS Comment;
use Gate\Package\Helper\DBCosmeticRankingListHelper AS Ranking;
use Gate\Package\Helper\DBCosmeticBenefitsHelper AS ActionEffect;
use Gate\Package\Helper\DBCosmeticGoodsInvestigatedInfoHelper AS GoodsDetail;
use Gate\Package\Helper\DBCosmeticUserHelper AS User;
use Gate\Package\Helper\DBCosmeticAdBannerHelper AS Banner;
use Gate\Package\Helper\DBUserProfileHelper AS MeiUser;
use Gate\Package\Helper\DBUserExtinfoHelper AS MeiUserExtinfo;
use Gate\Package\Helper\DBUserSkinHelper AS MeiUserSkin;
use Gate\Package\Helper\DBFavoritesHelper AS Favorites;
use Gate\Libs\Utilities;
header("Content-type:text/html;charset=utf-8");
class Cosmetic{
	private static $instance;
	private $searchPageNum=10;
	private $letter = array( 
		//gb2312 拼音排序
		array(45217,45252), //A
		array(45253,45760), //B
		array(45761,46317), //C
		array(46318,46825), //D
		array(46826,47009), //E
		array(47010,47296), //F
		array(47297,47613), //G
		array(47614,48118), //H
		array(0,0),         //I
		array(48119,49061), //J
		array(49062,49323), //K
		array(49324,49895), //L
		array(49896,50370), //M
		array(50371,50613), //N
		array(50614,50621), //O
		array(50622,50905), //P
		array(50906,51386), //Q
		array(51387,51445), //R
		array(51446,52217), //S
		array(52218,52697), //T
		array(0,0),         //U
		array(0,0),         //V
		array(52698,52979), //W
		array(52980,53688), //X
		array(53689,54480), //Y
		array(54481,55289), //Z
	);

	public static function getInstance(){
        is_null(self::$instance) && self::$instance = new self(); 
        return self::$instance;
    }
	
	//取中文首字母
	public function getLetter($num){
    	$char_index=65;
		foreach($this->letter as $k=>$v){
			if($num>=$v[0] && $num<=$v[1]){
				$char_index+=$k;
				return $char_index;
			}
		}
		return -1;
	}

	//品牌部分
	public function getNationList(){
		$sql = 'SELECT id,brand_classify 
			FROM cosmetic_brand 
			WHERE brand_classify!=""
			GROUP BY brand_classify 
			ORDER BY add_time';
		return Brand::getConn()->fetchArrAll($sql, array(''));
	}

	public function getBrandList($nationList, $page){
		foreach($nationList as $key=>$val){
			$condition = 'is_del=0 AND chi_name IS NOT NULL';
			if($val['id'] != 'all'){
				$condition .= ' AND brand_classify=\''.$val['brand_classify'].'\'';
			}else{
				$condition .= ' AND brand_classify!=\'\'';
			}
			$nationCount = Brand::getConn()->where($condition, array())->fetchCount();
			$twoBrandList =  Brand::getConn()
				->where($condition, array())
				->order('first_character ASC')
				->limit(intval($page-1)*$this->searchPageNum, $this->searchPageNum)
				->fetchArrAll();

			$result[$key]['area'] = $val['brand_classify'];
			$result[$key]['twoBrandList'] = empty($twoBrandList) ? null : $twoBrandList;
			$result[$key]['page']['totalNum'] = $nationCount;
			$result[$key]['page']['pageTotal'] = ceil($nationCount/$this->searchPageNum);
		}
		return $result;
	}

	public function getBrandConditionList($nation, $page){
		$condition = 'is_del=0 AND chi_name IS NOT NULL';
		if($nation == '全部'){
			$condition .= ' AND brand_classify!=\'\'';
		}else{
			$condition .= ' AND brand_classify=\''.$nation.'\'';
		}
		$brandList['area'] = $nation;
		$brandList['list'] =  Brand::getConn()
			->where($condition, array())
			->order('first_character ASC')
			->limit(intval($page-1)*$this->searchPageNum, $this->searchPageNum)
			->fetchArrAll();
        $nationCount = Brand::getConn()
			->where($condition, array())
		    ->fetchCount();
		$brandList['page']['totalNum'] = $nationCount;
		$brandList['page']['pageTotal'] = ceil($nationCount/$this->searchPageNum);
		return $brandList;
	}

	public function updateInitialData(){
		$allData = Brand::getConn()->fetchArrAll();
		foreach($allData as $k=>$v){
			if(preg_match('/^[\x7f-\xff]+$/', $v['chi_name'])){ 
				@$str= iconv('UTF-8', 'gb2312', $v['chi_name']);
				$i=0;
				while($i<1){
					$tmp=bin2hex(substr($str,$i,1));
					if($tmp>='B0'){ //汉字的开始
						$t=$this->getLetter(hexdec(bin2hex(substr($str,$i,2))));
						$initial = sprintf('%c',$t==-1 ? '*' : $t );
						$i+=2;
					}
					else{
						$initial = sprintf('%s',substr($str,$i,1));
						$i++;
					}
				}
			}else{
				$initial = substr($v['chi_name'], 0, 1);
			}
			$checkRes = preg_match('#\W#', $initial, $res);
			$initial = $checkRes==1 ? '' : $initial;
			$status = Brand::getConn()->update(array('first_character'=>$initial), 'id='.$v['id'], array());
			echo '更新ID为'.$v['id'].'首字母为'.$initial.'<br>';
			flush();
		}
	}

	//品牌中英文、数字、特殊字符、去空A-Z排序
	public function getMixSortLstSql($condition, $limit='0,10'){
		$initialFieldAsZh = 'ELT(
			INTERVAL(CONV(HEX(left(CONVERT(chi_name USING gbk),1)),16,10),
			0xB0A1,0xB0C5,0xB2C1,0xB4EE,0xB6EA,0xB7A2,0xB8C1,0xB9FE,0xBBF7,
			0xBFA6,0xC0AC,0xC2E8,0xC4C3,0xC5B6,0xC5BE,0xC6DA,0xC8BB,0xC8F6,
			0xCBFA,0xCDDA,0xCEF4,0xD1B9,0xD4D1),
			"A","B","C","D","E","F","G","H","J","K","L","M","N","O","P",
			"Q","R","S","T","W","X","Y","Z") as zh';
		$searchSql = '
			(SELECT id,chi_name,left(chi_name, 1) AS SZM,"#" AS zh FROM cosmetic_brand WHERE id NOT IN(
				SELECT id FROM (
					SELECT id,chi_name,zh AS SZM,zh FROM (
						SELECT id,chi_name,%s from cosmetic_brand
					) AS zh WHERE zh IS NOT NULL
					UNION
					SELECT id,chi_name,UPPER(left(chi_name, 1)) AS SZM,zh FROM (
						SELECT id,chi_name,zh FROM (
							SELECT id,chi_name,%s from cosmetic_brand
						) AS en_tab WHERE zh IS NULL
					) AS tab1
				) AS brand_b WHERE brand_b.SZM>="A" AND brand_b.SZM<="Z" ORDER BY brand_b.SZM ASC
			) AND chi_name IS NOT NULL)
			UNION
			(SELECT * FROM(
				SELECT id,chi_name,zh AS SZM,zh FROM (
					SELECT id,chi_name,%s from cosmetic_brand
			) AS zh WHERE zh IS NOT NULL
			UNION
			SELECT id,chi_name,UPPER(left(chi_name, 1)) AS SZM,zh FROM (
				SELECT id,chi_name,zh FROM (
					SELECT id,chi_name,%s from cosmetic_brand
				) AS en_tab WHERE zh IS NULL
			) AS tab1
			) AS brand_b WHERE brand_b.SZM>="A" AND brand_b.SZM<="Z" ORDER BY brand_b.SZM ASC)
			ORDER BY SZM ASC LIMIT %s';
			$searchSql = sprintf($searchSql, $initialFieldAsZh, $initialFieldAsZh, $initialFieldAsZh, $initialFieldAsZh, $limit);
			return $searchSql;
	}
	//分类部分
	public function getCategoryList(){
		$dataList = Category::getConn()
			->field('id,classify_name')
			->where('pid=0 AND is_del=0', array())
			->fetchArrAll();
		foreach($dataList as $key=>$val){
			$twoCategory = Category::getConn()
				->field('id,classify_name,goods_count')
				->where('pid=:pid AND is_del=0', array('pid'=>$val['id']))
				->fetchArrAll();
			$twoCategory = empty($twoCategory) ? 'null' : $twoCategory;
			$dataList[$key]['twoCategory'] = $twoCategory;
		}
		return $dataList;
	}

	//品牌产品列表
	public function getGoodsList($condition, $page=1){
		return Goods::getConn()
			->where($condition, array())
			->limit(intval($page-1)*$this->searchPageNum, $this->searchPageNum)
			->fetchArrAll();
	}

	//品牌产品列表总分页数
	public function getGoodsListPageTotal($condition){
		$page['totalNum'] = Goods::getConn()->where($condition, array())->fetchCount();
		$page['pageTotal'] = ceil($page['totalNum']/$this->searchPageNum);
		return $page;
	}
	
	//化妆品分类排行榜
	public function getRankingList(){
		$rankingList = Ranking::getConn()->where('pid=0', array())->fetchArrAll();
		foreach($rankingList as $key=>$val){
			$twoRankingList = Ranking::getConn()
				->where('pid=:pid', array('pid'=>$val['id']))
				->fetchArrAll();
			
			if(!empty($twoRankingList)){
				$rankingList[$key]['twoRankList'] = $twoRankingList;
			}	
		}
		return $rankingList;
	}

	//商品评价列表
	public function getGoodsCommentList($goodsId, $page=1){
		if(!empty($goodsId)){
			$result['commentList'] = Comment::getConn()
				->where('goods_id='.$goodsId, array())
				->limit(intval($page-1)*$this->searchPageNum, $this->searchPageNum)
				->order('id DESC')
				->fetchArrAll();

			$count = Comment::getConn()->where('goods_id='.$goodsId, array())->fetchCount();
			$result['commentPage']['totalNum'] = $count;
			$result['commentPage']['pageTotal'] = ceil($count/$this->searchPageNum);
		}

		//查找用户
		if(!empty($result['commentList'])){
		
			foreach($result['commentList'] as $key=>$val){
				$userId = empty($val['user_id']) ? null : $val['user_id'];
				if(!empty($userId) && $val['is_fake']==1){
					$result['commentList'][$key]['userInfo'] = User::getConn()
						->field('nickname,create_time,user_pic,skin')
						->where('id=:user_id AND is_del=0', array('user_id'=>$userId))
						->fetch();
				}else{
					$result['commentList'][$key]['userInfo'] = MeiUser::getConn()
						->field('realname AS nickname,create_time,avatar_c AS user_pic')
						->where('user_id=:user_id AND is_delete=0', array('user_id'=>$userId))
						->fetch();
					$skinConf = MeiUserSkin::getConn()->fetchArrAll();
					$skin = MeiUserExtinfo::getConn()
						->field('skin_type')
						->where('user_id=:user_id', array('user_id'=>$userId))
						->fetch();
					foreach($skinConf as $val){
						if($skin->skin_type == $val['id']){
							$skinVal = $val['name'];
						}
					}
					$result['commentList'][$key]['userInfo']->skin = $skinVal;
				}
			}
		}
		return $result;
	}
	
	//添加评论
	public function getAddCommentStatus($data, $deleteData, $status){
		$data['content'] = Utilities::htmlStripTags($data['content']);
		if($status == 'add'){
			$status = Comment::getConn()->insert($data);
		}else if($status == 'delete'){
			$status = Comment::getConn()
				->delete('user_id=:user_id AND goods_id=:goods_id', $deleteData);
		}
		return !empty($status);
	}

	//用户的评论列表
	public function getMyCommentList($userId, $page=1){
		$myCommentList['list'] = Comment::getConn()
			->where('user_id='.$userId, array())
			->limit(intval($page-1)*$this->searchPageNum, $this->searchPageNum)
			->fetchArrAll();
		//getSettleChaosGradeData
		$deleteNum = 0;
		foreach($myCommentList['list'] as $key=>$val){
			$goodsId = $val['goods_id'];
			$goodsInfo = Goods::getConn()
				->field('fun_ranking_index,img_add')
				->where('id='.$goodsId, array())
				->fetch();

			if(empty($goodsInfo)){
				$deleteNum++;
				unset($myCommentList['list'][$key]);
				continue;
			}
			$myCommentList['list'][$key]['img_add'] = empty($goodsInfo->img_add) ? null : $goodsInfo->img_add;
			$myCommentList['list'][$key]['fun_ranking_index'] = empty($goodsInfo->fun_ranking_index) ? null : $goodsInfo->fun_ranking_index;
		}
		$myCommentList['list'] = array_values($myCommentList['list']);
		$myCommentCount = Comment::getConn()->where('user_id='.$userId, array())->fetchCount();
		$myCommentList['page']['totalNum'] = $myCommentCount-$deleteNum;
		$myCommentList['page']['pageTotal'] = ceil(($myCommentCount-$deleteNum)/$this->searchPageNum);
		return $myCommentList;
	}

	//功效列表
	public function getActionEffectList(){
		$dataList = ActionEffect::getConn()
			->where('pid=0 AND is_del=0', array())
			->fetchArrAll();
		foreach($dataList as $key=>$val){
			$dataList[$key]['twoDataList'] = ActionEffect::getConn()
				->where('pid=:pid', array('pid'=>$val['id']))
				->fetchArrAll();
		}
		return $dataList;
	}

	//商品详情
	public function getGoodsDetail($goodsId, $userId=null){
		$detail = (array) @Goods::getConn()
			->field('id,pro_name,price,img_add,comment_count,specify,des_info,succession,classify_id,label')
			->where('id=:id', array('id'=>$goodsId))
			->order('id DESC')
			->fetch();
		$category =  @Category::getConn()
			->field('classify_name')
			->where('id=:id', array('id'=>$detail['classify_id']))
			->fetch();
		$detail['category'] = empty($category->classify_name) ? null : $category->classify_name;

		$detail['is_favorites'] = $this->getFavoritesStatus($userId, $goodsId);	
		$actionEffect = ActionEffect::getConn()
			->field('des_info')
			->where('id IN(:id)', array('id'=>$detail['label']))
			->fetchArrAll();
		$detail['actionEffect'] = empty($actionEffect) ? null : $actionEffect;
		$detail['gradeList'] = $this->getSettleChaosGradeData($goodsId);
		return $detail;
	}

	public function getSettleChaosGradeData($goodsId){
		$gradeList = GoodsDetail::getConn()->field('mult_assess')->where('id='.$goodsId, array())->fetch();
		if(empty($gradeList)){
			return null;
		}
		$gradeList->mult_assess = str_replace('{{:,:,:,}', '', $gradeList->mult_assess).'}';
		preg_match_all('#{(.*),}#iUs', $gradeList->mult_assess, $pregList);
		$find = array('{', '}');
		$replace = array('', '');
		foreach($pregList[1] as $k=>$v){
			$tmpVal = str_replace($find, $replace, $v);
			$tmpArr = explode(',', $tmpVal);
			for($i=0; $i<count($tmpArr); $i++){
				$result[]=@$tmpArr[$i];
			}
		}
		return $result;
	}

	//搜索
	public function getSearchList($keyword, $page=1){
		$condition = Utilities::htmlStripTags($keyword);
		$result['list'] = Goods::getConn()
			->where('pro_name LIKE "%'.$condition.'%" OR succession LIKE "%'.$condition.'%"', array())
			->limit(intval($page-1)*$this->searchPageNum, $this->searchPageNum)
			->fetchArrAll();
		$searchCount = Goods::getConn()
			->where('pro_name LIKE "%'.$condition.'%" OR succession LIKE "%'.$condition.'%"', array())
			->fetchCount();
		$result['page']['totalNum'] = $searchCount;
		$result['page']['pageTotal'] = ceil($searchCount/$this->searchPageNum);	
		return empty($result) ? null : $result;
	}

	//添加与取消收藏
	public function getUpdateFavorites($userId, $goodsId, $status){
		$upData = array(
			'user_id'=>$userId,
			'goods_id'=>$goodsId
		);
		if($status == 'add'){
			return Favorites::getConn()->insertIgnore($upData);
		}else if($status == 'cancel'){
			return Favorites::getConn()->delete('user_id=:user_id AND goods_id=:goods_id', $upData);
		}	
	}

	//收藏状态
	public function getFavoritesStatus($userId, $goodsId){
		$userInfo = array(
			'user_id'=>$userId,
			'goods_id'=>$goodsId
		);
		$result = Favorites::getConn()
			->field('user_id')
			->where('user_id=:user_id AND goods_id=:goods_id', $userInfo)
			->fetch();
		return !empty($result) ? true : false;
	}

	//我的商品收藏列表
	public function getFavoritesList($userId, $page=1){
		$myFavorites = Favorites::getConn()
			->field('goods_id')
			->where('user_id='.$userId, array())
			->fetchCol();
		
		$myFavoritesaId = implode(',', $myFavorites);
		$count = 0;
		
		if(!empty($myFavoritesaId)){
			$myFavGoodsList['list'] = Goods::getConn()
				->where('id IN('.$myFavoritesaId.')', array())
				->limit(intval($page-1)*$this->searchPageNum, $this->searchPageNum)
				->fetchArrAll();
			
			$count = Goods::getConn()
				->where('id IN('.$myFavoritesaId.')', array())
				->limit(intval($page-1)*$this->searchPageNum, $this->searchPageNum)
				->fetchCount();
		}

		$myFavGoodsList['page']['totalNum'] = $count;
		$myFavGoodsList['page']['pageTotal'] = ceil($count/$this->searchPageNum);
		return $myFavGoodsList;
	}

	//首页焦点图
	public function getIndexBannerData(){
		$result['bannerList'] = Banner::getConn()
			->field('id,forwardAddress AS address,img_src')
			->where('is_del=0 AND pid=0', array())
			->fetchArrAll();

		$result['picList'] = Banner::getConn()
			->field('id,forwardAddress AS address,img_src')
			->where('is_del=0 AND pid=1', array())
			->fetchArrAll();

		foreach($result['bannerList'] as $key=>$val){
			$result['bannerList'][$key]['address'] = json_decode($val['address']);
		}

		foreach($result['picList'] as $key=>$val){
			$result['picList'][$key]['address'] = json_decode($val['address']);
		}
		return $result;
	}
}
