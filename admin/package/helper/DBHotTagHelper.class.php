<?php
namespace Gate\Package\Helper;

class DBHotTagHelper extends \Phplib\DB\DBModel{
	const _DATABASE_ = 'hitao_beauty';
	const _TABLE_ = 'beauty_hot_tag';
	const _FIELDS_ = 'tag_id,name,article_id';
}
