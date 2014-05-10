<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTopicArticleHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_topic_article';
	const _FIELDS_	= 'id,topic_id,article_id';

	// id					-- int	 
	// topic_id					-- int	 
	// article_id					-- int	 
}