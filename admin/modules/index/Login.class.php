<?php
namespace Gate\Modules\Index;
use \Gate\Package\User\Login AS LoginP;

class Login extends \Gate\Libs\Controller {

	protected $nickname = NULL;
	protected $password = NULL;
	protected $checkLogin = false;

	public function run() {
        if (!$this->init()) {
            return FALSE;
		}
        $this->load();
	}

	public function init() {
		$this->nickname = isset($this->request->POST['nickname']) ? $this->request->POST['nickname'] : NULL;
		$this->password = isset($this->request->POST['password']) ? $this->request->POST['password'] : NULL;
		if(!$this->check()){
			return FALSE;
		}
        return TRUE;
	}

	private function check(){
		if (empty($this->nickname) || empty($this->password)) {
            $this->setError(400, 40001);
            return FALSE;
        }
        return TRUE;
	}

    private function load() {
        $checkCode =  LoginP::getInstance()->checkLogin($this->nickname, $this->password);
		
		if($checkCode!==true){
            $this->setError(400, $checkCode);
		}else{
            //login
			$this->redirect('/dwz/index.html');
		}
    }

}
