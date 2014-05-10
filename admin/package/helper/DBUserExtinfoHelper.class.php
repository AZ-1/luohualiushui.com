<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBUserExtinfoHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_user_extinfo';
	const _FIELDS_	= '
		user_id,
		gender,
		birthday,
		province_id,
		city_id,
		msn,
		qq,
		blog,
		about_me,
		interests,
		hobby,
		school,
		workplace,
		city,
		occupation';

	// user_id		-- int	 
	// gender		-- tinyint	 
	// birthday		-- date	 
	// province_id		-- int	 
	// city_id		-- int	 
	// msn		-- char	 
	// qq		-- char	 
	// blog		-- varchar	 
	// about_me		-- varchar	 
	// interests		-- varchar	 
	// hobby		-- varchar	 
	// school		-- varchar	 
	// workplace		-- varchar	 
	// city		-- text	 
	// occupation		-- text	 
}
