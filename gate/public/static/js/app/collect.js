hitao.define('app/collect',['jquery' , 'app/checkLogin'],function(require,exports){
	var $ = require('jquery');
	var checkLogin = require('app/checkLogin');
	var changeCollect = function(obj){
		$(obj).bind('click',function(){
			if(!checkLogin()){
				return false;
			}

			if($(this).attr('is_collect')==1){
				$(this).html('<span></span>关注');
				$(this).attr('is_collect',0);
				$(this).attr('style','');
				var url = "/topic/changeCollect/is_collect/0";
			}else{
				$(this).html('<span class="is_collected"></span>已关注');
				$(this).attr('is_collect',1);
				$(this).attr('style','background:#c0c0c0');
				var url = "/topic/changeCollect/is_collect/1";
			}		
		var data = {
			'user_id' : $(this).attr('user_id'),
			'user_grade' : $(this).attr('user_grade'),
			'topic_id' : $(this).attr('topic_id')
		};
		var callback = function(){}
		$.post(url,data,callback,'json');

		});
	}
	exports.changeCollect=changeCollect;
});
