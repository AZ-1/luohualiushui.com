hitao.define('app/firstTips' , ['jquery' , 'component/iStorage'] , function(require , exports){
	var $ = require('jquery');
	var iStorage = require('component/iStorage');
	return function(){
//		iStorage.remove('isTips');
		iStorage.get('isTips' , function(v){
			if(v == null){
				var maskLayer = '<div class="maskLayer"></div><img class="wenxinTips" src="/static/images/wenxintishi.gif" />';
				$('body').append(maskLayer);
				$('.maskLayer').width($(window).width());
				$('.maskLayer').height($(document).height());
				$('.wenxinTips').css({'left' : ($(window).width() - 740) / 2});
				$('.wenxinTips').bind('click' , function(){
					$('.maskLayer').remove();	
					$(this).remove();
				});
				iStorage.set('isTips' , 'true');
			}
		});
	}
});
