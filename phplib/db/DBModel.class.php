<?php
namespace Phplib\DB;

abstract class DBModel {

    public static function getConn() {
        $class		= get_called_class();
		$database	= defined("$class::_DATABASE_") ? $class::_DATABASE_ : null;

        $instance =  Database::getConn($database);
		$instance->table($class::_TABLE_);
		return $instance;
    }

}
