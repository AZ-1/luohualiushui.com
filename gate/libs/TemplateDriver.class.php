<?php
namespace Gate\Libs;
use \Gate\Libs\HttpRequest;
use \Gate\Libs\Utilities;
use \Gate\Libs\Base\Curl;

class TemplateDriver {

    private static	 $templateDriver = NULL; 
	protected static $domain_url = '';
    protected	$formatData;
    protected	$module;
    protected	$action;
    protected	$user;
    private		$templatePath = TEMPLATE_PATH;

	public		$page_title;
	public		$css_arr;
	public		$uri;
    
	protected function init(){
		$this -> page_title = PAGE_TITLE;
		$this -> css_arr    = '';
		self::$domain_url	= DOMAIN_NAME;
		$request			= HttpRequest::getRequest();
		$this->uri			= $request->uri;
	}

	public function __construct() {
		$this->init();
	}

    public static function getInstance() {
        is_null(self::$templateDriver) && self::$templateDriver= new self();
        return self::$templateDriver;
    }

    public function loadParam($formatData, $module, $action, $user) {
        $this->formatData = $formatData;
        $this->module  = $module;
        $this->action = $action;
        $this->user = $user;
    }

    public function loadTemplate() {
		if(!empty($this->formatData)) {
			$data = $this->formatData;
			if(is_object($data)){
				$data = (array)$data;
			}
			extract($data);
		}
        if ('index' == $this->module && 'login' == $this->action) {
            require($this->templatePath . $this->module .  "/" . ucwords(strtolower($this->action)) . ".view.php");
        }
        else {
            //$this->loadHeader();
            $templateFile = $this->templatePath . $this->module .  "/" . ucwords(strtolower($this->action)) . ".view.php";
            if (file_exists($templateFile)) {
                require($templateFile);
            }else{
				if(defined('DEBUG') && DEBUG==1){
					echo '<div class="alert alert-danger">Template File : <b>', $templateFile, '</b> is not found!</div>';
				}else{
					header("Location:/bad/badrequest");
				}
            }
            //$this->loadFooter();
        }
    }

	/*
	 * view - 
	 * template - 
	 * data:传入参数 ,例如: data['item']
	 */
	public function includeTemplate($view, $template, $data=array()){
		$formatData = (array)$this->formatData;
		extract($formatData);
		foreach($data as $var=>$v){
			if(isset($$var)){
				die('已存在公用变量名: '. $var);
			}
		}
		extract($data);
		$templateFile = $this->templatePath . $view .  '/' . ucwords($template) . '.view.php';
		include($templateFile);
	}

    public function includeHeader() {
        require($this->templatePath . "header.view.php");
    }

	/*
	 *
	 */
	public function htmlsp($str){
		$str = htmlspecialchars($str);
		$str = str_replace(array('<div>','</div>'), '', $str);
		return $str;
	}
	public function mSubstr($str , $len , $pad){
		//return mb_substr($str,0,$len/3,"utf-8").$pad;
		return mb_substr($str,0,$len/3,"utf-8");
	}
	public function templateTitle(){
		$this -> $page_title = "嗨淘-逛穿搭";
	}
    private function loadHeader() {
        extract(array('nickname' => $this->user->user_name));
        require($this->templatePath . "header.view.php");
    }

    private function includeFooter() {
        require($this->templatePath . "footer.view.php");
    }

	/*
	 * view 调用其他module借口
	 * 此loadModule 必须在服务器上配置hosts内本网站域名
	 *
	 * type : html,json
	 */
	public function loadModule($module, $type='html'){

		$curl = new Curl();
		$url = self::$domain_url . $module;
		if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!=''){
		   	$url .= '?' . $_SERVER['QUERY_STRING'];
		}
		if(isset($_GET)){
			$url .= strpos('/', $url) ? '&' : '?';
			$url .= http_build_query($_GET);
			$url = urldecode($url);
		}
		//开启路由
		$url = str_replace(array('?','&', '='), '/', $url);
		$cookStr = "";
		foreach($_COOKIE as $k => $c){
			$cookStr .= $k."=".$c.";";
		}
		$curl->setCookie($cookStr );

		$result = $curl->get($url);
		if($type=='json'){
			$result = json_decode($result);
		}
		return $result;
	}

	public function loadCss($css){
		if(empty($css)){
			return '';	
		}
		$cssArr = explode("," , $css);
		foreach($cssArr as $item){
			echo '<link rel="stylesheet" href="/static/css/'.$item.'" />'."\n";
		}	
	}
	public function isLogin(){
		if( is_null($this->user->user_id) ){
			return false;
		}
		return true;
	}

	public function cutStr($string, $sublen, $start = 0, $code = 'UTF-8'){
		return Utilities::cutStr($string, $sublen, $start, $code);
	}


}
