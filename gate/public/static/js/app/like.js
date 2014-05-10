hitao.define('app/like' , ['app/checkLogin' , 'jquery'] , function(require , exports){
	var $			=	require('jquery');
	var checkLogin	=	require('app/checkLogin');
	var time		=	0;
	return function(){
		$('.content').delegate('.ac_like','click',function(){
			if(!checkLogin()){
				return false;
			}
			var _islike		=	$(this);
			$('.xinBreak').attr('src','');
			var articleId	=	_islike.attr('aid');
			var div			=	_islike.find('div');
			var span		=	_islike.find('span');
			if(_islike.find('.num').attr('isLike') == 'false' || _islike.find('.num').attr('isLike') == '0'){
				var url			=	'/like/add_like';
				var bg			=	'url(/static/images/icon/icon.gif) -103px 0 no-repeat'
				_islike.find('.xinBreak').attr('src','');
				_islike.find('.xinBreak').hide();
				var like		=	1;
			}else{
				var url			=	'/like/del_like';
				var bg			=	'url(/static/images/icon/icon.gif) no-repeat'
				var like		=	0;
				_islike.find('.xinBreak').attr('src','/static/images/break.gif');
					_islike.find('.xinBreak').show();
				setTimeout(function(){
					_islike.find('.xinBreak').attr('src','');
					_islike.find('.xinBreak').hide();
				},800);
			}

			if(like){
				div.attr('isLike',1);
				span.html(parseInt(+span.text())+1);
			}
			else{
				div.attr('isLike',0);
				span.html(parseInt(+span.text())-1);
			}
			var callback	=	function(response){
				div.css('background',bg);	
			}
			var data		=	{
				'article_id':articleId
			}
			$.post(url,data,callback,'json');	
		});
	}
});
