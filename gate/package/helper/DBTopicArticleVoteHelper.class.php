<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBTopicArticleVoteHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_topic_article_vote';
	const _FIELDS_	= 'id,activity_id,article_id,topic_id,num';
}
