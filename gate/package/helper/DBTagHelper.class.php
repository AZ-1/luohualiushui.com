<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTagHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_tag';
	const _FIELDS_	= 'tag_id,name,category_id';

	// tag_id					-- int	 
	// name					-- varchar	 
	// category_id					-- int	 默认所属类目
}