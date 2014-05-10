<?php
namespace Gate\Config\Scripts;

class MySQL extends \Phplib\Config {

	protected function __construct() {
		$this->hitao_beauty = $this->hitao_beauty();
	}

	private function hitao_beauty() {
		$config = array();
		//$config['MASTER']    = array('HOST' => 'rdsaqu2yaaqu2ya.mysql.rds.aliyuncs.com', 'PORT' => '3306', 'USER' => 'mei', 'PASS' => 'htdb4mei', 'DB' => 'mei');
		//$config['SLAVES'][] = array('HOST' => 'rdsaqu2yaaqu2ya.mysql.rds.aliyuncs.com',	'PORT' => '3306', 'USER' => 'mei', 'PASS' => 'htdb4mei', 'DB' => 'mei');
		$config['MASTER']    = array('HOST' => 'localhost', 'PORT' => '3306', 'USER' => 'hitao_beauty', 'PASS' => '123456', 'DB' => 'hitao_beauty');
		$config['SLAVES'][] = array('HOST' => 'localhost',	'PORT' => '3306', 'USER' => 'hitao_beauty', 'PASS' => '123456', 'DB' => 'hitao_beauty');
		return $config;
	}

}
