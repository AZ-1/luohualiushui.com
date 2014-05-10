<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBBrandsHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_brands';
	const _FIELDS_	= 'brands_id,name,logo,update_time,create_time,link_url';

	// brands_id					-- int	 
	// name					-- varchar	 
	// logo					-- varchar	 
	// update_time					-- int	 
	// create_time					-- timestamp	 
}
