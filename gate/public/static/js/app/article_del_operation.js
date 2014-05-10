hitao.define('app/article_del_operation' , ['jquery'] , function(require , exports){
	var $ = require('jquery');
	var article_operation = function(){
		$('.article_info').bind('mouseenter',function(){
			var operation_log = $(this).find('.operation_log');
			operation_log.addClass('clickup');
			operation_log.siblings('.article_operation').show();
		});
		$('.article_info').bind('mouseleave',function(){
			var operation_log = $(this).find('.operation_log');
			operation_log.removeClass('clickup');
			operation_log.siblings('.article_operation').hide();
		});
	}
	var del_article = function(){
		$('.del_article').bind('click',function(){
			if(!confirm('是否要删除此文章')){return false;}
			var dom = $(this);
			var aid = dom.attr('article_id');
			var draft_id = dom.attr('draft_id');
			var data = {
				'aid' : aid,
				'draft_id' : draft_id
			}
			var url = '/article/del_article';
			var callback = function(response){
				if(response == 1){
					dom.parents('.article_item').remove();
				}else{
					alert('删除失败');
				}
			}
			$.post(url , data , callback , 'json');
		});
	}
	var drop_dom = function(){
		$('.error_unset').bind('click',function(){
			$(this).parents('.article_item').remove();
		});
	}
	var mouseEnter = function(){
		$('.mouseOver').bind('mouseenter',function(){
			$(this).attr('style','background:#c0c0c0;color:#ffffff;');
		});
		$('.mouseOver').bind('mouseout',function(){
			$(this).attr('style','');
		});
	}
	exports.article_operation = article_operation;
	exports.del_article = del_article;
	exports.drop_dom = drop_dom;
	exports.mouseEnter = mouseEnter;
});
