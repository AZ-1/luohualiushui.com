<?php
namespace Gate\Config\Production;

class MySQL extends \Phplib\Config {

	protected function __construct() {
		$this->hitao_beauty = $this->_hitao_beauty();
	}

	private function _hitao_beauty() {
		$config = array();
		$config['MASTER']    = array('HOST' => 'rdsaqu2yaaqu2ya.mysql.rds.aliyuncs.com', 'PORT' => '3306', 'USER' => 'mei', 'PASS' => 'htdb4mei', 'DB' => 'mei');
		$config['SLAVES'][] = array('HOST' => 'rdsaqu2yaaqu2ya.mysql.rds.aliyuncs.com',	'PORT' => '3306', 'USER' => 'mei', 'PASS' => 'htdb4mei', 'DB' => 'mei');
		return $config;
	}

}
