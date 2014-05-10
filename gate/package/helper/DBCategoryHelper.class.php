<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBCategoryHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_category';
	const _FIELDS_	= 'id,name,pid,level,left_num,right_num,status,sort,description,article_num,top_id,navigation_id,source_cats_id,is_end,update_time,create_time';

	// id					-- int	 
	// name					-- varchar	 
	// pid					-- int	 
	// level					-- tinyint	 
	// left_num					-- int	 
	// right_num					-- int	 
	// status					-- tinyint	 
	// sort					-- tinyint	 
	// description					-- varchar	 
	// article_num					-- int	 
	// top_id					-- int	 
	// navigation_id					-- int	 
	// source_cats_id					-- int	 
	// is_end					-- tinyint	 
	// update_time					-- int	 
	// create_time					-- timestamp	 
}