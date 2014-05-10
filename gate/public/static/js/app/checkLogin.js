hitao.define('app/checkLogin' , ['jquery','app/login'] , function(require , exports){
	var $ = require('jquery');
	var login = require('app/login');   
	return function (){
		if(hitao.vars.user_id == 0){ 
			login.showLoginWin();   
//			location.href = '/user/login'
			return false;
		}
		return true; 
	}
});
