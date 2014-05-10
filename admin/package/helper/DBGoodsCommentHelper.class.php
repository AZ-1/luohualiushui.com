<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBGoodsCommentHelper  extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'cosmetic_goods_comment';
	const _FIELDS_	= 'id,goods_id,user_id,content,is_del';

}
