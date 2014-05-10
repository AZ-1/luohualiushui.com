<?php
namespace Gate\Config\Production;

class MySQL extends \Phplib\Config {

	protected function __construct() {
		$this->hitao_beauty = $this->hitao_beauty();
	}

	private function hitao_beauty() {
		$config = array();
		$config['MASTER']    = array('HOST' => '127.0.0.1', 'PORT' => '3306', 'USER' => 'root', 'PASS' => '123456', 'DB' => 'hitao_beauty');
		$config['SLAVES'][] = array('HOST' => '127,0,0,1',	'PORT' => '3306', 'USER' => 'root', 'PASS' => '123456', 'DB' => 'hitao_beauty');
		return $config;
	}

}
