hitao.define('app/userInfoTips' , ['jquery' , 'component/shareTmp' ,'component/tips'] , function(require , exports){
	var $ = require('jquery');
	var tips = require('component/tips');
	var shareTmp = require('component/shareTmp');
	var objId = {};
	return function(){
		tips('body' , '.userInfoTips' , '.pop_loading' , function(obj , t){
			var user_id = obj.attr('user_id');
			var url = '/index/getUser/';
			var data = {'user_id' : user_id , 'ajax' : 1};
			var callback = function(response){
				var tpl = shareTmp('facePop',response);
				$('.pop_loading').html(tpl);
				objId[user_id] = response;
			}
			if(objId[user_id]){
				callback(objId[user_id]);
			}else{
				$.post(url , data , callback , 'json');
			}
		});	
		$('body').on('.tipsUserFollowBtn' , 'click' , function(){
		});
	}
});
