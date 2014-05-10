<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBNavigationHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'navigation';
	const _FIELDS_	= 'id,name,e_name,pid,level,left_num,right_num,status,sort,description,article_num,top_id,is_end,update_time,create_time,url';

	// id					-- int	 
	// name					-- varchar	 
	// e_name					-- varchar	 
	// pid					-- int	 
	// level					-- tinyint	 
	// left_num					-- int	 
	// right_num					-- int	 
	// status					-- tinyint	 
	// sort					-- tinyint	 
	// description					-- varchar	 
	// article_num					-- int	 
	// top_id					-- int	 
	// is_end					-- tinyint	 
	// update_time					-- int	 
	// create_time					-- timestamp	 
	// url					-- varchar	 
}
