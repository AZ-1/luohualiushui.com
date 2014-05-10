<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBFansHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_fans';
	const _FIELDS_	= 'fans_id,user_id,fans_user_id';

	// fans_id					-- int	 
	// user_id					-- int	 
	// fans_user_id					-- int	 粉丝用户
}