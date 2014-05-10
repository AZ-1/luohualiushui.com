<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBLikeHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_like';
	const _FIELDS_	= 'like_id,user_id,article_id';

	// like_id					-- int	 
	// user_id					-- int	 
	// article_id					-- int	 
}