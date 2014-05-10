hitao.define('app/shareToSinaWeibo' , ['jquery'] , function(require , exports){
	var $ = require('jquery');
	var checkLogin	=	require('app/checkLogin');
	var shareToSinaWeibo =  function(pwd){
		$('.shareToWeibo').bind('click',function(){
			if(hitao.vars.user_name == 0){
				window.location.href="http://www.hitao.com/passport-login.html";
				return false;
			}
			var content = '#嗨淘女神节#小伙伴快来吧,助我"聚神品",抽中1000元红包!凭此密码:A1234，即可领取我给你发的50元红包！点击参与http://t.cn/8F0fRlt 你也可以做女神、抽大奖哦！@嗨淘网';
			shareToWeibo('',content,'http://mei.hitao.com/static/images/activityLogo.jpg');
			var url = '/activity/prize';
			var data = {
				'user_name' : hitao.vars.user_name,
				'activity_id' : 1,
				'type' : 0
			}
			var callback = function(){}
			$.post(url , data , callback , 'json');
		});
	}

	var getPrize = function(){
		$('.enter').bind('click' ,function(){
			if(hitao.vars.user_name == 0){
				window.location.href="http://www.hitao.com/passport-login.html";
				return false;
			}
			var url = '/activity/prize';
			var data = {
				'user_name' : hitao.vars.user_name,
				'type' : 1,
				'activity_id' : 1
			};
			var callback = function(response){
				$('.prize_result').attr('id','');
				if(response.msg.error == 0){
					$('.prize_result').attr('id','prize_'+response.msg.prize);
				}else{
					$('.prize_result').attr('id' ,'error_'+response.msg.error);
				}	
				$('.prize_result').show();
			}
			$.post(url , data , callback , 'json');
		});
	}

	var close_prize = function(){
		$('.close_prize').bind('click',function(){
			$('.prize_result').hide();
		});
	}

	exports.shareToSinaWeibo = shareToSinaWeibo;
	exports.getPrize = getPrize;
	exports.close_prize = close_prize;
});
