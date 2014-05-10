<?php
namespace Gate;
define('CONFIG_PATH', 'production');
define('CONFIG_PATH_NAMESPACE', 'Production');


define('ROOT_PATH', __DIR__ . '/..');
define('LIB_PATH', ROOT_PATH . '/../phplib');
require_once(ROOT_PATH . '/libs/base/function.php');

// require zoo's autoloader
require_once(LIB_PATH . '/Autoloader.class.php');
require_once(ROOT_PATH . '/config/'.CONFIG_PATH.'/config.inc.php');
require_once(ROOT_PATH . '/config/config.inc.php');

$root_path_setting = array(
	'gate' => ROOT_PATH,
	'phplib' => LIB_PATH,
);
$autoloader = \Phplib\Autoloader::get($root_path_setting);
// configuration of MySQL, Redis, Memcache, etc.
\Phplib\Config::setConfigNamespace('\\Gate\\Config\\'.CONFIG_PATH_NAMESPACE);

$dispatcher = Libs\Dispatcher::get();
$dispatcher->dispatch();

//exec fastcgi_finish_request
if (function_exists("fastcgi_finish_request")) {
	fastcgi_finish_request();
}
