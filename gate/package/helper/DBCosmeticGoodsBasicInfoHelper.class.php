<?php
namespace Gate\Package\Helper;
class DBCosmeticGoodsBasicInfoHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'cosmetic_goods_basic_info';
	const _FIELDS_	= 'id,name,label,fun_ranking_index,output_priority,succession,price,funtionality_id,brand_id,investigated_info_id,create_time';
}
