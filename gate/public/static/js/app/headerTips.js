hitao.define('app/headerTips' , ['jquery' ] , function(require , exports){
	var $ = require('jquery');
	var UserTime;
	var MesTime;
	var bindTips = function(){
		$('.login_user_name').bind('mouseover' ,function(){
			$('.hitaoUser').show();
			$('.headerTipsTriangleUser').show();
		});
		$('.login_user_name').bind('mouseleave' ,function(){
			UserTime = setTimeout('hideUserTime()',100);
		});
		$('.hitaoUser').bind('mouseenter' ,function(){
			clearTimeout(UserTime);
		});
		$('.hitaoUser').bind('mouseleave' ,function(){
			UserTime = setTimeout('hideUserTime()',100);
		});

		$('.login_user_message').bind('mouseenter',function(){
			$('.hitaoMsg').show();
			$('.headerTipsTriangleMsg').show();
		});
		$('.login_user_message').bind('mouseleave',function(){
			MesTime = setTimeout('hideMesTime()',100);
		});
		$('.hitaoMsg').bind('mouseover' ,function(){
			clearTimeout(MesTime);
		});
		$('.hitaoMsg').bind('mouseleave',function(){
			MesTime = setTimeout('hideMesTime()',100);
		});
	}
	exports.bindTips = bindTips;
});
