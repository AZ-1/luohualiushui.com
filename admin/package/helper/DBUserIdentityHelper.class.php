<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBUserIdentityHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_user_identity';
	const _FIELDS_	= 'id,identity';

	// id					-- tinyint	 
	// grade					-- smallint	 
	// text					-- varchar	 
	// icon					-- varchar	 
}
