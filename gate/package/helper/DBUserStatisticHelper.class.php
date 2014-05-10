<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBUserStatisticHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_user_statistic';
	const _FIELDS_	= 'user_id,follow_num,fans_num,like_article_num,article_num,article_num_check,article_num_in,collect_topic_num';

	// user_id					-- int	 
	// follow_num					-- int	 
	// fans_num					-- int	 
	// like_article_num					-- int	 
	// article_num					-- int	 发布的文章数
	// article_num_check					-- int	 未审核和已通过的文章总数
	// article_num_in					-- int	 审核通过的文章总数
	// collect_topic_num					-- int	 关注的话题数量
}