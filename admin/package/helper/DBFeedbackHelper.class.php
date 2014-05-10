<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBFeedbackHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_feedback';
	const _FIELDS_	= 'id,user_id,content,create_time,client_type,version';
}
