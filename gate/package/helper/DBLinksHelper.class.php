<?php
namespace Gate\Package\Helper;

class DBLinksHelper extends \Phplib\DB\DBModel{
     const _DATABASE_  = 'hitao_beauty';
	 const  _TABLE_    = 'beauty_links';
	 const  _FIELDS_   = 'links_id,name,url,logo,status,sort';

}

//links_id           int
//name          友链的名字 varchar
//url           友链地址  varchar
//logo          友链图片路径 varchar
//status        友链状态 int  1不推荐，2推荐
//sort          排序  int

