<?php
namespace Gate\Config\Dev;

class MySQL extends \Phplib\Config {

	protected function __construct() {
		$this->hitao_beauty = $this->_hitao_beauty();
	}

	private function _hitao_beauty() {
		$config = array();
        $config['MASTER']    = array('HOST' => 'localhost', 'PORT' => '3306', 'USER' => 'hitao_beauty', 'PASS' => '123456', 'DB' => 'hitao_beauty');
        $config['SLAVES'][] = array('HOST' => 'localhost',  'PORT' => '3306', 'USER' => 'hitao_beauty', 'PASS' => '123456', 'DB' => 'hitao_beauty');
        return $config;
	}

}
