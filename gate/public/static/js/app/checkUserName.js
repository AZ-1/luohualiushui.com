hitao.define('app/checkUserName' , ['jquery' , 'component/shareTmp'] , function(require , exports){
	var $ = require('jquery');
	var checkUserName = function(){
		$('.tdInput').bind('blur',function(){
			var user_input_name = $('.tdInput').val();
			var url = '/user/edit_user';
			var data = {
				'is_check' : 1,
				'username' : user_input_name
			};
			var callback = function(res){
				$('.error_report').html(res);
			}
			$.post(url , data , callback , 'json');
		});
	}
	exports.checkUserName = checkUserName;
});
