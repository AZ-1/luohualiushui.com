<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBActivityLotteryHelper  extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_activity_lottery';
	const _FIELDS_	= 'id , user_id , lottery_times , prize ,create_time,source_user_name';

}
