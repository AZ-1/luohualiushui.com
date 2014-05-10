$('.allCheckBox').die('click').live('click',function(){
		if($(this).attr('checked') == 'checked'){
			$('.required').attr('checked',true);
		}else{
			$('.required').attr('checked',false);
		}
	});

	$('.deleteArticleasd').die('click').live('click' , function(){
		if(confirm('是否删除?')){
			var __this = this;
			$.post('/article/del_article' , {'aid' : $(this).attr('aid')} , function(){
				$(__this).parent().parent().parent().remove();
			});
		}
	});
	/*
	*批量删除
	*/ 
	$('.moreDel').bind('click',function(){
		var check		=	$('input:checked');
		if(!check.length){
				alert('您没有选择内容');
				return false;
		}
		if(confirm('是否删除?')){
			var check		=	$('input:checked');
			if(!check.length){
				alert('您没有选择内容');
				return false;
			}
			var articleId	=	'';
			check.each(function(i){
				articleId	+=	$(this).val() + ',';
			});
			var url		= '/article/del_more_article';
			var data	= {
				'aid':articleId
			}
			
			var callback	=	function(response){
			}
			$.post(url,data,callback,'json');
			check.each(function(i){
				$('.article_'+$(this).val()).remove();
			});
		}
	});

	/*
	 *	批量审核
	 *
	 */
	$('.saveMoreCheck').die('click').live('click',function(){
		var articleIds	= $('.articleIds').val();
		var reason		= $('#reason').val();
		var quality		= $('.qualitySelect option:selected').val();
		if(!articleIds){
			return false;
		}
		var check		=	$('input:checked');
		var checkStatus = check.parent().parent().parent().find('.checkStatus');
		if(reason){
			checkStatus.html('未通过|'+reason);
			checkStatus.css('color','red');
		}
		if(quality == 1){
			checkStatus.html('质量上乘|');
			checkStatus.css('color','green');
		}else if(quality == 2){
			checkStatus.html('质量中等|');
			checkStatus.css('color','green');
		}else if(quality == 3){
			checkStatus.html('质量下等|');
			checkStatus.css('color','green');
		}
		var url			= '/article/batch_check_article';
		var data		= {
			'article_id':articleIds,
			'reason':reason,
			'quality':quality
		}
		var callback = function(response){
			$('input:checked').attr('checked',false);;
			$('.confirmCheck').hide(300);
		}
		
		$.post(url,data,callback,'json');
	});

	$('.cancelMoreCheck').die('click').live('click',function(){
			$('input:checked').attr('checked',false);;
			$('.confirmCheck').hide(300);
	});
	
	$('.addTopic').bind('click',function(){
		var topicId		=	$('.topic_list option:selected').val();
		var topicName	=	$('.topic_list option:selected').text();
		var articleId	=	'';
		var check		=	$('input:checked');
		check.each(function(i){
			articleId	+=	$(this).val() + ',';
		});
		var url		= '/topic/add_topic_article';
		var data	= {
			'id':topicId,
			'article':articleId
		}
		var callback	=	function(response){
			check.each(function(i){
				$('.topic_'+$(this).val()).find('div').html(topicName);
			});
		}
		$.post(url,data,callback,'json');
	});
	
	$('.moreCheck').bind('click',function(){
		var articleId	=	'';
		var check		=	$('input:checked');
		check.each(function(i){
			articleId	+=	$(this).val() + ',';
		});
		$('.articleIds').val(articleId);
	});


