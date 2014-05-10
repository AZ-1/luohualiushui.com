<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBArticleHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_article';
	const _FIELDS_	= 'article_id,title,category_id,user_id,description,description_pic,is_delete,update_time,create_time,is_check,quality,no_pass_reason,by_forward_user_id,forward_article_id,forward_description,title_pic';

	// article_id					-- int	 
	// title					-- char	 
	// category_id					-- int	 
	// user_id					-- int	 
	// description					-- char	 
	// description_pic					-- text	 描述图片
	// is_delete					-- tinyint	 
	// update_time					-- int	 
	// create_time					-- timestamp	 
	// is_check					-- tinyint	 0-未审核 1-未通过 2通过
	// quality					-- tinyint	 1 质量上 2质量中 3质量下
	// no_pass_reason					-- varchar	 
	// by_forward_user_id					-- int	 转发的原文章id
	// forward_article_id					-- int	 转发原文章id
	// forward_description					-- varchar	 
	// title_pic					-- varchar	 文章封面图
}