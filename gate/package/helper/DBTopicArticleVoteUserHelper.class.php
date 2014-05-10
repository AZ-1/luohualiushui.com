<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTopicArticleVoteUserHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_topic_article_vote_user';
	const _FIELDS_	= 'id,activity_id,user_id,vote_times,article_ids';
}
