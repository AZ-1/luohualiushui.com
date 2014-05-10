<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBFollowHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_follow';
	const _FIELDS_	= 'follow_id,user_id,follow_user_id';

	// follow_id					-- int	 
	// user_id					-- int	 
	// follow_user_id					-- int	 被关注用户
}