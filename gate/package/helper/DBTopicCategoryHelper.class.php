<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTopicCategoryHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_topic_category';
	const _FIELDS_	= 'id,topic_id,category_id';

	// id					-- int	 
	// topic_id					-- int	 
	// category_id					-- int	 
}