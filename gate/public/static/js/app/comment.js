hitao.define('app/comment' , ['jquery' , 'component/shareTmp' ,'app/checkLogin'] , function(require , exports){
	var $ = require('jquery');
	var checkLogin = require('app/checkLogin');
	var shareTmp = require('component/shareTmp');

	var getComment = function(aid , pageNum , seminar_id ,pageSize){
		pageSize = pageSize == undefined ? pageSize = 20 : pageSize;
		var url = "/comment/comment_list";	
		var data = {
			'aid' : aid,
			'pageNum' : pageNum,
			'seminar_id' : seminar_id,
			'pageSize' : pageSize
		};
		var callback = function(res){
			var comment_list = shareTmp('comment_list' , res);
			$('.comment_list').html(comment_list);
		}
		$.post(url , data , callback , 'json');
	}
	//发布评论
	var comment_time = 0;
	var addComment = function(){
		$('.send_mes').bind('click' , function(){
			if(!checkLogin()){
				return false;
			}
			var comment_mes = $('.comment_mes').val();
			if($.trim(comment_mes) == ''){alert('请您填写评论内容');return false;}
			var now_time = new Date().getTime();
			if(now_time - comment_time < 5000){
				alert('您的评论太过频繁,请休息休息');
				return false;
			}
			comment_time = now_time;
			var add_time = addTime();
			var aid = $(this).attr('h_aid');
			var is_seminar = $(this).attr('is_seminar');
			var article_user_id = $(this).attr('h_article_uid');
			var uid = $(this).attr('h_uid');
			var seminar_id = $(this).attr('seminar_id');
			var status = $('.comment_title').attr('status');//转发0|评论1
			if(status == 1){
				var is_forward = 0;
			}else{
				var is_forward = 1;
			}
			var pid = $(this).attr('h_pid');
			var replayid = $(this).attr('h_replayid');
			$(this).attr('h_pid','');
			$(this).attr('h_replayid','');
			//开始评论
			var url = "/comment/Add_comment";
			if(replayid != ''){
				var reg_res = comment_mes.match('回复@(.*)+[:\s]');	//被回复的人名
				if(reg_res){
					var reg_com = comment_mes.replace(reg_res[0],'');	//回复的内容
					var reg_name = reg_res[0];
				}else{
					replayid = '';
					pid = '';
					reg_com = comment_mes;
				}
			}else{
				var reg_com = comment_mes;
				var reg_name = '';
			}

			var data = {
				'a_uid' : article_user_id,	//文章所属用户id
				'reply_user_id' : replayid,	//被回复的用户id
				'pid' : pid ,		//回复的评论id
				'aid' : aid,		//文章id, article_id
				'uid' : uid,		//发表评论用户的id  user_id
				'data' : comment_mes,		//评论内容
				'is_forward' : is_forward,
				'seminar_id' : seminar_id
			};
			var callback = function(response){}
			$.post(url , data , callback , 'json');
			//转发
			if(status==0){
				var url = '/article/forward_article';
				var data = {
					'article_id' : aid,
					'forward_content' : comment_mes,
					'by_forward_user_id' : article_user_id
				};
				var callback = function(){}
				$.post(url,data,callback,'json');
			}
			var add_mes = {
				rep_id : replayid,
				reg_name : reg_name,
				reg_com : reg_com,
				time : add_time
			};
			var comment_item = shareTmp('sc_comment_item' ,add_mes);
			$('.comment_list').prepend(comment_item);
			$('.new').fadeIn();
			$('.comment_mes').val('');
		});//end bind
	}//end addComment->function

	var comment_article = function(){
		$('.comment_title').bind('click',function(){
			$(this).attr('status',1);
			$(this).addClass('active');
			$('.forward_title').removeClass('active');
		});
	}
	var forward_article = function(){
		$('.forward_title').bind('click',function(){
			$('.comment_title').attr('status',0);
			$('.comment_title').removeClass('active');
			$('.forward_title').addClass('active');
		});
	}
	
	//动态绑定回复
	var replay = function(){
		$('.comment_list').on('click' , '.comment_forward' , function(){
			$('.comment_mes')[0].focus();
			$('.comment_mes').val('回复@'+$(this).attr('user_name')+':');
			var replayid = $(this).attr('user_id');
			$('.comment_mes').next().attr('h_replayid',replayid);
			var p_id = $(this).attr('p_id');
			$('.comment_mes').next().attr('h_pid',p_id);
		});
	}

	var delComment = function(){
		$('.comment_list').on('click' ,'.comment_del' , function(){
			var comment_id = $(this).attr('p_id');
			var user_id = $(this).attr('user_id');
			var article_id = $(this).attr('article_id');
			if(confirm('是否要删除此条评论') && user_id == hitao.vars.user_id){
				var url = '/comment/del_comment';
				var data = {
					'comment_id' : comment_id ,
					'user_id' : user_id,
					'article_id' : article_id
				}
				var callback = function(response){
					if(response.status==1){
						alert(response.message);
					}else{
						alert(response.message);
					}
				}
				$.post(url , data , callback , 'json');
				$(this).parents('.comment_context').hide();
			}
		});
	}

	//获取时间
	var addTime = function(){
		var myTime = new Date();
		var addTime = myTime.toLocaleString().replace(/年|月/g,"-").replace(/日/g," ");
		return addTime;
	}

	var commentWordCount = function(){
		$('.comment_mes').bind('keyup' , function(){
			var content = $('.comment_mes').val();
			var length = content.length;
			if(length>500){
				length = 500;
				var str = content.substring(0,500);
				$('.comment_mes').val(str);
				alert('评论内容过长哦!请不要超过500字');
			}
			$('.comment_count_left').text(length);

		});
	}
	exports.replay = replay;
	exports.addComment = addComment;
	exports.comment_article = comment_article;
	exports.forward_article = forward_article;
	exports.getComment = getComment;
	exports.delComment = delComment;
	exports.commentWordCount = commentWordCount;
});
