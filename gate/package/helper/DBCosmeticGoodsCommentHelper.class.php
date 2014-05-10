<?php
namespace Gate\Package\Helper;
class DBCosmeticGoodsCommentHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'cosmetic_goods_comment';
	const _FIELDS_	= 'id,publish_time,goods_id,user_id,content,create_time';
}
