hitao.define('app/index' , ['jquery' , 'app/test' , 'component/windowResize'] , function(require , exports){
	var t = require('app/test');
	var $ = require('jquery');
	var windowResize = require('component/windowResize');
	exports.a = function(){
		$(document).ready(function(){
		var mWidth = 1180;
		var windowWidth = $(window).width();
		if(windowWidth > mWidth){
			var mLeft = Math.ceil((windowWidth-mWidth)/2);
			$(".tern").css({"margin-left":mLeft});
		}else{
			$(".tern").css({"margin-left":0});
		}

		});
		windowResize.bind(function(){
		var mWidth = 1180;
		var windowWidth = $(window).width();
		if(windowWidth > mWidth){
			var mLeft = Math.ceil((windowWidth-mWidth)/2);
			$(".tern").css({"margin-left":mLeft});
		}else{
			$(".tern").css({"margin-left":0});
		}
		})
	}
	exports.alert_hover = function(){
		$(".user_info").mouseover(function(){
			var obj = $(this).find(".alertUser");
			obj.css({top:$(this).offset().top+83,left:$(this).offset().left,display:"inline"});
		});
		$(window).click(function(){
			var obj = $(".alertUser");
			obj.css({display:"none"});
		});
	windowResize.bind(function(){
		var cha = $(".alertUser");
		cha.hide();
	});
	}
	exports.positionAbsout = function(){
		var par = $(".arvticleUser");
		var cha = par.find(".alertUser");
		if(cha == "undefind"){
			return ;
		}
		if(par.offset().left != cha.offset().left || par.offset().top - cha.offset().top != 83){
			cha.css({left:par.offset().left,top:par.offset().top+83});
		}
	}
});
