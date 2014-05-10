<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTopicUserHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_topic_user';
	const _FIELDS_	= 'topic_id,user_id,user_grade';

	// topic_id					-- int	 
	// user_id					-- int	 
	// user_grade					-- int	 
}