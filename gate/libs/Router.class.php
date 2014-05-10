<?php
namespace Gate\Libs;

class Router extends \Phplib\Router {

	/**
	 * Constructor.
	 */
    protected function __construct() {
		//$this->config = \Phplib\Config::load('Memcache');
        parent::__construct();
    }


	protected function getAction($uri){


		$arr =  array(
			'guang'=>'index/guang',
		
		);
		return $arr[$path];
	}

}
