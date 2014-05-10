<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBUserGradeHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_user_grade';
	const _FIELDS_	= 'id,value,name,icon,user_num';

	// id					-- tinyint	 
	// value					-- smallint	 这个字段未使用
	// name					-- varchar	 
	// icon					-- varchar	 
	// user_num					-- int	 达人数量统计
}
