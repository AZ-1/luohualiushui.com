<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBCosmeticGoodsDetailInfoHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'cosmetic_goods_investigated_info';
	const _FIELDS_	= 'id,img_urls,mult_assess,create_time';
	/*
		+-------------+------------------+------+-----+-------------------+-----------------------------+
		| Field       | Type             | Null | Key | Default           | Extra                       |
		+-------------+------------------+------+-----+-------------------+-----------------------------+
		| id          | int(10) unsigned | NO   | PRI | NULL              | auto_increment              |
		| img_urls    | varchar(3000)    | YES  |     | NULL              |                             |
		| mult_assess | varchar(500)     | YES  |     | NULL              |                             |
		| create_time | timestamp        | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
		+-------------+------------------+------+-----+-------------------+-----------------------------+*
	 *
	 *
	 * */	

}
