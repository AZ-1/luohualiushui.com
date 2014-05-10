hitao.define('app/tipsFollow' , ['jquery' , 'app/checkLogin'] , function(require , exports){
	var $  = require('jquery');
	var checkLogin = require('app/checkLogin');
	return function(){
		$("body").on('click' ,".tipsUserFollowBtn" ,  function(){
			if(!checkLogin()){
				return false;
			}
			if($(this).attr('is_follow') == 0){
				$(this).html('<span class="is_collected"></span>已关注');
				$(this).attr('is_follow' , 1);
				$(this).addClass('is_followed');
				var url = '/follow/add_follow';
			}else{
				$(this).html('<span></span>关注');
				$(this).attr('is_follow' , 0);
				$(this).removeClass('is_followed');
				var url = '/follow/del_follow';
			}
			var data = {
				view_uid : $(this).attr('uid')
			}
			var callback = function(){}
			$.post(url  , data , callback , 'json');
		})
	}
});
