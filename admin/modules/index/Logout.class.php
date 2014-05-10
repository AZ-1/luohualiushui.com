<?php
namespace Gate\Modules\Index;

class Logout extends \Gate\Libs\Controller {

	public function run() {
        \Gate\Libs\Session::Singleton()->destory();
        header('Location: /');
        exit();
	}

}
