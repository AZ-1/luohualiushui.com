<?php
namespace Gate\Config\Dev;

class Memcache extends \Phplib\Config {

	protected function __construct() {
		$this->pools = array(
				array('host' => '127.0.0.1', 'port' => 11211)
		);
	}   
}
