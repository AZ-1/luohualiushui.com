<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTagUserHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_tag_user';
	const _FIELDS_	= 'id,tag_id,category_id,user_id';

	// id					-- int	 
	// tag_id					-- int	 
	// category_id					-- int	 
	// user_id					-- int	 
}