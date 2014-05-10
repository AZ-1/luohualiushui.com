<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTagArticleHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_tag_article';
	const _FIELDS_	= 'id,tag_id,article_id';

	// id					-- int	 
	// tag_id					-- int	 
	// article_id					-- int	 
}