<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBArticleStatisticHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_article_statistic';
	const _FIELDS_	= 'article_id,like_num,forward_num,comment_num,is_delete';

	// article_id					-- int	 
	// like_num					-- int	 
	// forward_num					-- int	 
	// comment_num					-- int	 
	// is_delete					-- tinyint	 和article主表一致
}
