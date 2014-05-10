hitao.define('app/comment' , ['jquery' ] , function(require , exports){
	var $ = require('jquery');
	var replayComment = function(){
		$('.send_mes').bind('click',function(){
			var article_id = $(this).attr('h_aid');
			var comment_id = $(this).attr('h_pid');
			var article_user_id = $(this).attr('h_article_uid');
			var reply_user_id = $(this).attr('h_replayid');
			var user_id = $(this).attr('h_uid');
			var content = $(this).prev('.comment_mes').val();
			$(this).prev('.comment_mes').val('');
			var reg_res = content.match('回复@(.*)+[:\s]');	//被回复的人名
			if(!reg_res){
				reply_user_id = '';
				pid = '';
			}
			var data = {
				'a_uid' : article_user_id,	//文章所属用户id
				'reply_user_id' : reply_user_id,	//被回复的用户id
				'pid' : comment_id ,		//回复的评论id
				'aid' : article_id,		//文章id, article_id
				'uid' : user_id,		//发表评论用户的id  user_id
				'data' : content		//评论内容
			};
			var url = '/comment/Add_comment';
			var callback = function(){}
			$.get(url,data,callback,'json');
			$(this).siblings('.result').attr('style','');
		});
	}
	var replayBtn = function(){
		$('.myNewsReply').bind('click',function(){
			var status = $(this).next().next().next();
			if(status.attr('style') ==''){
				status.attr('style','display:none;');
			}else{
				status.attr('style','');
				var replayname = $(this).attr('replayname');
				var mes_box = status.children('.comment_mes').val('回复@'+replayname+':');
			}
		});
	}

	var commentWordCount = function(){
		$('.comment_mes').bind('keyup' , function(){
			var content = $(this).val();
			var length = content.length;
			if(length>500){
				length = 500;
				var str = content.substring(0,500);
				$(this).val(str);
				alert('评论内容过长哦!请不要超过500字');
			}
			$(this).siblings('.comment_count').children('.comment_count_left').text(length);

		});
	}
	exports.replayComment = replayComment();
	exports.replayBtn = replayBtn();
	exports.commentWordCount = commentWordCount();
});
