<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBAdBannerHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_ad_banner';
	const _FIELDS_	= 'id,title,link_url,pic_url,file_path';

	// id					-- int	 
	// title					-- varchar	 
	// link_url					-- varchar	 
	// pic_url					-- varchar	 
	// file_path					-- varchar	 
}