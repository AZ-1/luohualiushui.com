<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBHotUserHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_hot_user';
	const _FIELDS_	= 'hot_id,user_id,category_id';

	// id					-- int	 
	// article_id					-- varchar	 
	// tag					-- varchar	 
}
