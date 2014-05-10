<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBMessageHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_message';
	const _FIELDS_	= 'id,user_id,type,num';

	// id					-- int	 
	// user_id					-- int	 
	// type					-- tinyint	 
	// num					-- smallint	 
}