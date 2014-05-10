<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBCosmeticGoodsHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'cosmetic_goods_basic_info';
	const _FIELDS_	= 'id,pro_name,label,output_priority,succession,classify_id,create_time,img_add,comment_count,is_del,specify,des_info';
/*	 
	 | id                   | int(10) unsigned | NO   | PRI | NULL              | auto_increment              |
	 | pro_name             | varchar(50)      | NO   |     | NULL              |                             |
	 | label                | varchar(50)      | YES  |     | NULL              |                             |
	 | fun_ranking_index    | float(2,1)       | YES  |     | NULL              |                             |
	 | output_priority      | int(2)           | YES  |     | 0                 |                             |
	 | succession           | varchar(60)      | YES  |     | NULL              |                             |
	 | price                | float(5,1)       | YES  |     | NULL              |                             |
	 | classify_id          | int(10)          | YES  |     | NULL              |                             |
	 | brand_id             | int(10) unsigned | YES  |     | NULL              |                             |
	 | create_time          | timestamp        | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
	 | img_add              | varchar(100)     | YES  |     | NULL              |                             |
	 | comment_count        | int(7)           | YES  |     | 0                 |                             |
	 | is_del               | int(1)           | YES  |     | 0                 |                             |
	 | specify              | varchar(50)      | YES  |     | NULL              |                             |
	 | des_info 
*/
	// brands_id					-- int	 
	// name					-- varchar	 
	// logo					-- varchar	 
	// update_time					-- int	 
	// create_time					-- timestamp	 
}
