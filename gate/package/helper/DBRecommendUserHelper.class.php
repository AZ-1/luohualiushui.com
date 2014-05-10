<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class RecommendUser extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_recommend_user';
	const _FIELDS_	= '
		recommend_id,
		user_id,
		category_id';

	// recommend_id		-- int	 
	// user_id		-- int	 
	// category_id		-- int	 
}