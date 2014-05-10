<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBUserConnectBindHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_user_connect_bind';
	const _FIELDS_	= 'auth,user_id, user_type';

	// auth					-- char	 
	// user_id					-- int	 
	// type					-- tinyint	 
}
