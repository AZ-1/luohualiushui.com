<?php
namespace Gate\Config\Production;
class Sphinx extends \Phplib\Config {
	protected function __construct() {
		$this->pools = new \stdClass();
		$this->pools->master = new \stdClass();
		$this->pools->slave[0] = new \stdClass();
		$this->pools->master->host = '127.0.0.1';
		$this->pools->master->port = 9314;
		$this->pools->slave[0]->host = '127.0.0.1';
		$this->pools->slave[0]->port = 9314;
	}
}
