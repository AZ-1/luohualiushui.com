<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class DBUserProfileHelper extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_user_profile';
	const _FIELDS_	= 'user_id,nickname,realname,password,cookie,is_actived,invite_code,active_code,salt,email,mobile,status,is_delete,grade,description,identity,last_login_time,last_login_ip,create_time,avatar_c,avatar_width,avatar_height,is_recommend';

	// user_id					-- int	 用户ID
	// nickname					-- char	 用户名
	// realname					-- char	 
	// password					-- char	 用户密码
	// cookie					-- char	 
	// is_actived					-- tinyint	 
	// invite_code					-- char	 
	// active_code					-- char	 
	// salt					-- char	 密码加密串
	// email					-- char	 用户电子邮箱
	// mobile					-- bigint	 用户手机号码
	// status					-- tinyint	 状态: 0=默认，1=完成引导
	// is_delete					-- tinyint	 
	// grade					-- smallint	 用户等级
	// description					-- varchar	 
	// identity					-- tinyint	 
	// last_login_time					-- int	 最后登录时间
	// last_login_ip					-- int	 最后登录IP
	// create_time					-- timestamp	 
	// avatar_c					-- char	 
	// avatar_width					-- smallint	 
	// avatar_height					-- smallint	 
	// is_recommend					-- tinyint	 
}
