<?php
namespace Gate\Modules\Daren;

class Index extends \Gate\Libs\Controller {

	public function run() {
		if (!$this->_init()) {
			return FALSE;
		}

	}

	private function _init() {
			return TRUE;
	}

}
