hitao.define('app/activity' , ['component/dialog' , 'app/shareTo' , 'app/checkLogin', 'jquery' , 'component/shareTmp'] , function(require , exports){
	var $ = require('jquery');
	var shareTmp = require('component/shareTmp');
	var shareTo  = require('app/shareTo');
	var checkLogin = require('app/checkLogin');
	var dialog = require('component/dialog');

	exports.works = function(){
		$('.workLeft').bind('click',function(){
			var url = '/activity/ch_article/';
			var data = {};
			var callback = function(response){
				var works_item = shareTmp('sc_works' ,response);
				$('.worksInfo').remove();
				$('.works').append(works_item);
			}
			$.post(url,data,callback,'json');
		});
		$('.workRight').bind('click',function(){
			var url = '/activity/ch_article/';
			var data = {};
			var callback = function(response){
				var works_item = shareTmp('sc_works' ,response);
				$('.worksInfo').remove();
				$('.works').append(works_item);
			}
			$.post(url,data,callback,'json');
		});

		$(document).ready(function(){
			var url = '/activity/ch_article/';
			var data = {};
			var callback = function(response){
				var works_item = shareTmp('sc_works' ,response);
				$('.worksInfo').remove();
				$('.works').append(works_item);
			}
			$.post(url,data,callback,'json');	
		});
	}
	exports.like = function(){
		$('.works').delegate('.like','click',function(){
			if(!checkLogin()){
				return false;
			}

			var _this = $(this);
			var is_like = _this.attr('is_like');
			var aid = _this.attr('aid');
			if(is_like == 1){
				var url			=	'/like/del_like';
				var num			=	Number(_this.find('span').html())-1; 
				var like_statue		=	0;
			}else{
				var url			=	'/like/add_like';
				var num			=	Number(_this.find('span').html())+1;
				var like_statue		=	1;
			}
			var callback	=	function(response){
			}
			_this.attr('is_like',like_statue);
			_this.find('span').html(num);

			var data		=	{
				'article_id' : aid
			}
			$.post(url,data,callback,'json');	
		});
	}
	
	exports.vote = function(){
		$('.works').delegate('.like','click',function(){
			
			if(!checkLogin()){
				return false;
			}
			var _this	=	$(this);
			var aid		=	_this.attr('aid');
			var tid		=	_this.attr('tid');
			var url		=	'/activity/add_vote';
			var data	=	{
				'article_id'	:	aid,
				'topic_id'		:	tid
			}
			var callback	=	function(response){
				var close = dialog.meiliDialog({
					dialogWidth : 280,
					dialogTitle : '温馨提示',
				});
				if(response.vote_resule.num_left == 9){
					response.vote_resule.msg = '投票成功!您还剩下9票可以投给自己喜欢的作品,快去支持她吧!';
				}else if(response.vote_resule.num_left > 1){
					response.vote_resule.msg = '投票成功!';
				}else if(response.vote_resule.num_left == 1){
					response.vote_resule.msg = '投票成功!您的票数已用完,召集小伙伴一起参与中大奖!';
				}else{
					response.vote_resule.msg = '您的票数已用完,召集小伙伴一起参与中大奖!';
				}
				var dialog_10 = shareTmp('dialog_10',response);
				$('#dialogContent').html(dialog_10);
				$('.button').bind('click',function(){
					if(response.vote_resule.num_left == 0){
						$('.share').trigger('click');
					}else if(response.vote_resule.num_left == 9){
						$('.share').trigger('click');
					}
					close();
				});
				if(response.vote_resule.num_left > 0){
					var span	=	_this.find('span');
					span.html(Number(span.html())+1);
				}
			}
			$.post(url,data,callback,'json');
		});
	}

	exports.share = function(){
		$('.share').bind('click' , function(){
			var url = hitao.vars.domain_url+'activity/topic_vote';
			var reply = "小伙伴们~辞旧迎新年，约会、逛街、年会、party各种场合妆容怎么搞定？\
						#嗨淘星妆扮-美妆大赛# 高手来支招！@嗨淘网 @越淘越开心\
						PS:只要转发就可以参与妆扮精美大礼包抽奖活动哟";
			var imgUrl = hitao.vars.domain_url+'static/images/vote/reward_one.gif';
			shareTo.shareToWeibo(url,reply,imgUrl);
		});
	}

	exports.endActivity = function(){
		$('.works').delegate('.like','click',function(){
				var close = dialog.meiliDialog({
					dialogWidth : 280,
					dialogTitle : '温馨提示',
				});
				var endActivity = shareTmp('endActivity');
				$('#dialogContent').html(endActivity);
				$('.button').bind('click',function(){
					close();
					window.open('http://mei.hitao.com/article/index?aid=23254');
				});

		});
	}
});
