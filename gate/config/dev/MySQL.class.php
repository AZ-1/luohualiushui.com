<?php
namespace Gate\Config\Dev;

class MySQL extends \Phplib\Config {

	protected function __construct() {
		$this->hitao_beauty = $this->hitao_beauty();
	}

	private function hitao_beauty() {
		$config = array();
		$config['MASTER']    = array('HOST' => 'localhost', 'PORT' => '3306', 'USER' => 'root', 'PASS' => 'ai889575', 'DB' => 'hitao_beauty');
		$config['SLAVES'][] = array('HOST' => 'localhost',	'PORT' => '3306', 'USER' => 'root', 'PASS' => 'ai889575', 'DB' => 'hitao_beauty');
		return $config;
	}

}
