<?php
namespace Gate\Libs;
use \Gate\Libs\HttpRequest;
use \Gate\Libs\Utilities;
use \Gate\Libs\Base\Curl;

class TemplateDriver {

    protected $formatData;
    protected $module;
    protected $action;
    protected $user;
	public $page_title;
    private $templatePath = TEMPLATE_PATH;
    private static $templateDriver = NULL; 
	protected static $domain_url = '';
    

	public function __construct() {
		$this -> page_title = PAGE_TITLE;
		$this -> templateGobal();
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
			if(is_object($this->formatData)){
				$this->formatData = (array)$this->formatData;
			}
			extract($this->formatData);
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
					header("Location:/index/error");
				}
            }
            //$this->loadFooter();
        }
    }

	public function includeTemplate($view, $template){
		extract($this->formatData);
		$templateFile = $this->templatePath . $view .  "/" . ucwords($template) . ".view.php";
		include($templateFile);
	}
    public function includeHeader() {
        require($this->templatePath . "header.view.php");
    }

	/*
	 *
	 */
	public function htmlsp($str){
		return htmlspecialchars($str);
	}

	private function templateGobal(){
		self::$domain_url = DOMAIN_NAME;
	}
	public function templateTitle(){
		$this -> $page_title = "嗨淘-逛穿搭";
	}
    private function loadHeader() {
        extract(array('nickname' => $this->user->user_name));
        require($this->templatePath . "header.view.php");
    }

    private function loadFooter() {
        require($this->templatePath . "footer.view.php");
    }

	public function loadModule($module){
		$curl = new Curl();
		$url = self::$domain_url . $module;
		if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!=''){
		   	$url .= '?' . $_SERVER['QUERY_STRING'];
		}

		if( isset($result['POST'])){
			$request = HttpRequest::getRequest();
			$result = $curl->post($url,http_build_query($request['POST']));
		}else{
			$result = $curl->get($url);
		}
		$result = json_decode($result);
		//$result = file_get_contents($url);
		return $result;
	}

}
