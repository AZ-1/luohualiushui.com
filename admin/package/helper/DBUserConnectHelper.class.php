<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBUserConnectHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_user_connect';
	const _FIELDS_	= 'user_id,user_type,status,auth,old_user_id';

	// user_id					-- int	 
	// user_type					-- tinyint	 
	// status					-- tinyint	 
	// auth					-- char	 
}
