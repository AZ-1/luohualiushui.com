<?php
/*
 * 
 */
namespace Gate\Package\Helper;

class TableRowsNum extends \Phplib\DB\DBModel {
	const _DATABASE_= 'hitao_beauty';
	const _TABLE_	= 'beauty_table_rows_num';
	const _FIELDS_	= '
		table_name,
		num';

	// table_name		-- varchar	 
	// num		-- bigint	 
}