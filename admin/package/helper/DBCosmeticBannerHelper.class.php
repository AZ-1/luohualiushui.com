<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBCosmeticBannerHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'cosmetic_ad_banner';
	const _FIELDS_	= 'id,pid,forwardAddress,img_src,displayOrder';
	/*
	 * +----------------+--------------+------+-----+---------+----------------+
	 * | Field          | Type         | Null | Key | Default | Extra          |
	 * +----------------+--------------+------+-----+---------+----------------+
	 * | id             | int(5)       | NO   | PRI | NULL    | auto_increment |
	 * | forwardAddress | varchar(400) | NO   |     | NULL    |                |
	 * | img_src        | varchar(255) | NO   |     | NULL    |                |
	 * | is_del         | int(1)       | NO   |     | 0       |                |
	 * | pid            | int(5)       | NO   |     | 0       |                |
	 * | displayOrder   | int(1)       | YES  |     | 0       |                |
	 * +----------------+--------------+------+-----+---------+--	 
*/
}
