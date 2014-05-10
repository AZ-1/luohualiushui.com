<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBUserStatisticHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_user_statistic';
	const _FIELDS_	= 'user_id,follow_num,fans_num,like_article_num,article_num';

	// user_id					-- int	 
	// follow_num					-- int	 
	// fans_num					-- int	 
	// like_article_num					-- int	 
	// article_num					-- int	 发布的文章数
}
