hitao.define('app/login' , ['jquery' , 'component/dialog' , 'component/shareTmp'] , function(require , exports){
	var $ = require('jquery');	
	var shareTmp = require('component/shareTmp');
	var dialog = require('component/dialog');
	var showLoginWin = function(){
		dialog.meiliDialog({
			dialogWidth : 280,
			dialogTitle : '登陆'
		});	
		var login = shareTmp('loginWin');
		$('#dialogContent').html(login);
	}	
	exports.showLoginWin = showLoginWin;
});
