<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTopicHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_topic';
	const _FIELDS_	= 'topic_id,title,pic,description,sort,status,article_num,user_num,update_time,create_time';

	// topic_id					-- int	 
	// title					-- varchar	 
	// pic					-- varchar	 
	// description					-- text	 
	// sort					-- int	 
	// status					-- tinyint	 
	// article_num					-- int	 
	// user_num					-- int	 关注话题的用户数
	// update_time					-- int	 
	// create_time					-- timestamp	 
}