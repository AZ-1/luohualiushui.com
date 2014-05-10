hitao.define('app/cleanMsg' , ['jquery' , 'component/shareTmp'] , function(require , exports){
	var $ = require('jquery');
	var shareTmp = require('shareTmp');

	var msgFunc = function(){
		if(hitao.vars.user_id == 0) return;
		var url = '/message/index';
		var data = {};
		var callback = function(res){
			if(!res.message.like_num  && !res.message.fans_num  && !res.message.comment_num) return false;
			var hitaoMsg = shareTmp('hitaoMsg' , res);
			$('.hitaoMsg').show();
			$('.hitaoMsg').html(hitaoMsg);
		}
		$.post(url , data , callback , 'json');	
		setTimeout(function(){
			msgFunc();
		} , 10000);

	}
	var msgTips = function(){
		msgFunc();	
			} 
	exports.msgTips = msgTips;
});
