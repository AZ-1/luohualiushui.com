<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBCommentHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_comment';
	const _FIELDS_	= 'comment_id , article_id , user_id , reply_user_id , article_user_id , type , pid , content , create_time , is_forward ,is_taobao , is_delete';

	// comment_id					-- int	 
	// article_id					-- int	 
	// user_id					-- int	 发表评论的用户
	// article_user_id					-- int	 文章所属用户
	// type					-- tinyint	 
	// pid					-- int	 
	// content					-- text	 
	// create_time					-- timestamp	 
	// is_taobao					-- tinyint	 是淘宝评论
	// is_delete					-- tinyint	 
}
