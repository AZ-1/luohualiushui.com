<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBBrandHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'cosmetic_brand';
	const _FIELDS_	= 'id,chi_name,eng_name,img_add,brand_classify,initator,create_time,birth_place,official_web,story,is_del';

}
