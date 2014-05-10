<?php
namespace Gate\Libs;
#use Gate\Libs\Router;
use \Gate\Package\Connect\TaobaoAuth;

class Dispatcher {

	private $request = NULL;
	private $module = NULL;
	private $action = NULL;
	private $xhprof = FALSE;
    //private $userid = NULL;

	public static function get() {
		static $singleton = NULL;
		is_null($singleton) && $singleton = new Dispatcher();
		return $singleton;
	}

	private function __construct() {
		try {
			$this->router();
			$this->request = new \Gate\Libs\HttpRequest();
			$this->checkLogin();
            //$this->userid = $this->request->session->id;
		}
		catch (\Exception $e) {
			$log = new \Phplib\Tools\Liblog('gate_error_log', 'normal');
			$log->w_log($e->getMessage());
			echo json_encode(array('error_code' => 10002, 'message' => $e->getMessage()));
			die();
		}
	}

	public function dispatch() {
        if (OA_VIEW_SWITCH == 'ON') {
            $path_args = $this->request->path_args;
            // first arg is the module's name
            $module = array_shift($path_args);
            $action = array_shift($path_args);

			if(empty($module)){
				$module = 'index';
			}
			if(empty($action)){
				$action = 'index';
			}

			$this->module = $module;
			$this->action = $action;

            $class = '\\Gate\\Modules\\' . ucwords($module) . '\\' . ucwords($action);
            $this->request->path_args = $path_args;
            if (!class_exists($class)) {
                $class = "\\Gate\\Modules\\Bad\\Badrequest";
            }
            $controller = new $class($this->request, $this->module, $this->action);
            $controller->control();
            $controller->echoTemplate();
        }
        //output json data
        else  {
            $path_args = $this->request->path_args;
            // first arg is the module's name
            $module = array_shift($path_args);
            empty($module) && $module = 'bad';
            $this->module = $module;

            $action = array_shift($path_args);
            empty($action) && $action = 'badrequest';
            $this->action = $action;
            // pass the control to module's Router class
            
            $class = '\\Gate\\Modules\\' . ucwords($module) . '\\' . ucwords($action);
            $this->request->path_args = $path_args;
            if (!class_exists($class)) {
                $class = "\\Gate\\Modules\\Bad\\Badrequest";
            }
            $controller = new $class($this->request);
            $controller->control();
            $controller->echoView();

        }
    }

	private function startAnylize() {
		if ($this->xhprof === TRUE) {
			xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);	
		}
	}

	private function finishAnylize() {
		if ($this->xhprof === TRUE) {
			$xhprof_data = xhprof_disable();	
			$xhprof_obj = new \Gate\Package\User\Xhprof();
			$uniqid = uniqid();
			$author = $this->module . ':' . $this->action;
			$xhprof_obj->addData($uniqid, $xhprof_data, $author);
		}
	}

	public function get_request() {
		return $this->request;
	}

	public function get_module() {
		return $this->module;
	}

	public function get_action() {
		return $this->action;
	}

	protected function router(){
		//$uri = explode('/', $_SERVER['REQUEST_URI']);
		//$uri = include_once(ROOT_PATH . '/constant/router.php');
		//if($uri==''){
		//	return false;
		//}
		//if(!isset($uriMapping[$uri[1]])){
		//	return FALSE;
		//}
		//foreach($uri as $k=>$v){
		//	if($k>1 && $v!=''){
		//		$p			= $k-1;
		//		$strParams	= str_replace(array(':'.$p, '?'), array($v, '&'), $uriMapping[$uri[1]][1]);
		//		$newUri		= $uriMapping[$uri[1]][0] .'?' . $strParams;
		//		$_SERVER['QUERY_STRING'] = $strParams;
		//		parse_str($strParams, $arrParams);
		//		$_GET		= array_merge($arrParams, $_GET);
		//		$_REQUEST	= array_merge($arrParams, $_REQUEST);
		//	}
		//}
		//unset($_SERVER['REQUEST_URI']);
		//pr($_SERVER['REQUEST_URI']);
		//$_SERVER['REQUEST_URI'] = $uri;
		//var_dump($_SERVER['REQUEST_URI']);
	}
 
	protected function checkLogin(){
		// 嗨淘主站打通登录
		if( isset($_COOKIE[HITAO_LOGIN_COOKIE])){
			if( !isset($_COOKIE[HITAO_LOGIN_COOKIE_IS])){
				$result = TaobaoAuth::getInstance()->taobaoLogin();
				if($result['status']==1){
					setcookie(HITAO_LOGIN_COOKIE_IS, true, time() + DEFAULT_COOKIE_EXPIRE, DEFAULT_COOKIE_PATH, SERVER_NAME );
					$this->request = new \Gate\Libs\HttpRequest();
				}
			}
		}
	}
}
