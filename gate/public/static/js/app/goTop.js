hitao.define('app/goTop' , ['jquery' , 'component/windowScroll'] , function(require , exports){
	var $ = require('jquery');
	var scroll = require('component/windowScroll');
	return function(){
		var is6 = $.browser.msie && $.browser.version=='6.0';
		//gotop
		var goTop = $("#goTop");
		var go_top = $('#go_top');
		goTop.show();
		var win  =$(window);
	   $('#go_top').bind("click" , function(){
			$('body,html').stop(true , true).animate({ scrollTop: 0 }, 300);
			return false;
		});
		scroll.yIn(50 , function(){
			$('.header').addClass('header_min');
			$('.header').css('position','fixed');
			go_top.stop(true,true).fadeIn("fast");
		} , function(){
			$('.header').removeClass('header_min');
			$('.header').css('position' , 'absolute');
			go_top.stop(true,true).fadeOut("fast");
		});
		if (is6){
			goTop.css("position","absolute");
			scroll.bind(function(pos){
				goTop.css({"top" : (pos + win.height()-230) + 'px'})
			});
		}
	}
});
