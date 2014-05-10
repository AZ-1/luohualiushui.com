<?php
namespace Gate\Libs;

class HttpRequest {

	private $request_data = NULL;

	public static function getRequest() {
		static $singleton = NULL;
		is_null($singleton) && $singleton = new HttpRequest();
		return $singleton;
	}

	public function __construct() {
		$this->parseUrl();

		// initialize HTTP data
		$this->request_data['protocol']  = $_SERVER['SERVER_PROTOCOL'];
		$this->request_data['domain']    = $_SERVER['SERVER_NAME'];
		$this->request_data['uri']       = $_SERVER['REQUEST_URI'];
		$this->request_data['path']      = $this->getRequestPath();
		$this->request_data['path_args'] = explode('/', $this->path);
		$this->request_data['method']    = $this->getRequestMethod();
		$this->request_data['GET']       = $_GET;
		$this->request_data['POST']      = $_POST;
		$this->request_data['COOKIE']    = Utilities::zaddslashes($_COOKIE);
		$this->request_data['REQUEST']   = Utilities::zaddslashes($_REQUEST);
		$this->request_data['headers']   = Utilities::parseRequestHeaders();
		$this->request_data['referer']	 = isset($this->request_data['headers']['Referer']) ? $this->request_data['headers']['Referer'] : 0;
		$this->request_data['base_url']  = $this->detectBaseUrl();
		$this->request_data['ip']        = $_SERVER['REMOTE_ADDR'];
		$this->request_data['time']      = $_SERVER['REQUEST_TIME'];
		$this->request_data['session']   = \Gate\Libs\Session::singleton()->load($_COOKIE);
	}

	public function __get($name) {
		if (!isset($this->request_data[$name])) {
			return NULL;
		}
		return $this->request_data[$name];
	}

	public function writeLog($content='', $timeShow=true)
	{
		$file = fopen('/tmp/log/mei.hitao.com/robot.log', 'a');
		$time = $timeShow == true ? date('Y-m-d H:i:s', time()).':' : '';
		if($file)
		{
			fwrite($file, $time.$content."\r\n");
			fclose($file);
		}                                                                                                                                
	}

	/*
	 * url 路由
	 */
	private function parseUrl()
	{
		$defaultParse = array(
			'control'=>'index',
			'action'=>'index'
		);
		
		extract($defaultParse);

		$pathinfo = $_SERVER['REQUEST_URI'];
		$pathinfo = explode('?', $pathinfo);
		$_SERVER['PATH_INFO'] = $pathinfo[0];

		if(isset($_SERVER['PATH_INFO']) && $pathinfo[0] != 'index.php' && $pathinfo[0] != '/')
		{
			//$currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$pathinfo  = explode('/',  $_SERVER['PATH_INFO']);
			$url  =  array_values(array_diff($pathinfo, array(null,'null','',' ')));

			// 获取 control
			$control = (!empty($url[0]) ? $url[0] : $control);
			array_shift($url);

			// 获取 action
			$action = (!empty($url[0]) ? $url[0] : $action);
			array_shift($url);

			$spm = '';
			if(!empty($_GET['spm']))
			{
				$spm = '?spm='.$_GET['spm'];
			}

			$tmpGET = $_GET;
			unset($tmpGET['spm']);

			$query = http_build_query($tmpGET);
			$newUrl = '/'.$control.'/'.$action.'/'.str_replace(array('&','='), '/', $query.'/').$spm;

			for($i = 0, $cnt = count($url); $i <$cnt; $i++)
			{
				$_GET[$url[$i]] = (string)$url[++$i];
			}

			$spmCondition = strstr($_SERVER['QUERY_STRING'], 'spm');
			$spmArray = explode('&', $spmCondition);

			$mobileReg = '#\/\w+_m\?|\/\w+_m\/#';
			preg_match_all($mobileReg, $newUrl, $match);

			if(!empty($match[0]))
			{
				return false;		
			}

			if(count($spmArray)>1 || (empty($_GET['spm']) && !empty($_SERVER['QUERY_STRING'])))
			{
				//header('Location:'.$newUrl);
			}
		}
	
		$_REQUEST += $_GET;
	}

	/**
	 * Returns the requested URL path.
	 * E.g., for http://io.xxx.com/a/b it returns "a/b".
	 */
	private function getRequestPath() {
		// only parse $path once in a request lifetime
		static $path;

		if (isset($path)) {
			return $path;
		}

		if (isset($_SERVER['REQUEST_URI'])) {
			// extract the path from REQUEST_URI
			$request_path = strtok($_SERVER['REQUEST_URI'], '?');
			$base_path_len = strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/'));

			// unescape and strip $base_path prefix, leaving $path without a leading slash
			$path = substr(urldecode($request_path), $base_path_len + 1);

			// $request_path is "/" on root page and $path is FALSE in this case
			if ($path === FALSE) {
				$path = '';
			}

			// if the path equals the script filename, either because 'index.php' was
			// explicitly provided in the URL, or because the server added it to
			// $_SERVER['REQUEST_URI'] even when it wasn't provided in the URL (some
			// versions of Microsoft IIS do this), the front page should be served
			if ($path == basename($_SERVER['PHP_SELF'])) {
				$path = '';
			}
		}

		return $path;
	}

	private function getRequestMethod() {
		static $method;

		if (isset($method)) {
			return $method;
		}

		$method = strtolower($_SERVER['REQUEST_METHOD']); 
		// make sure $method is valid and supported
		in_array($method, array('get', 'post', 'delete')) || $method = 'get';

		return $method;
	}

	private function detectBaseUrl() {
		$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$host = $_SERVER['SERVER_NAME'];
		$port = ($_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT']);
		$uri = preg_replace("/\?.*/", '', $_SERVER['REQUEST_URI']);

		return "$protocol$host$port";
	}

    public function __toString() {
        return serialize($this->request_data);
        //return json_encode($this->request_data);
    }

}
