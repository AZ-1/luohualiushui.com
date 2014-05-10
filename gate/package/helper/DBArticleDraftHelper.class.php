<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBArticleDraftHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_article_draft';
	const _FIELDS_	= 'id , user_id , category_id , title , content , create_time';
}
