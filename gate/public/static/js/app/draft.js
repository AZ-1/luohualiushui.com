hitao.define('app/draft' , ['jquery' ] , function(require , exports){
	var $ = require('jquery');
	var draft_id=0;
	var draftBox = function(editor , login_user_id ,old_draft_id){
		var firstSave = false;
		if(old_draft_id != 0){
			firstSave = true;
			draft_id = old_draft_id;
		}
		var firstTime = 0;
		editor.addListener('keyup',function(){
			if(draft_id != 0){
				firstSave = true;
			}
			var content_title = $('#title').val();
			var category_id = $('#categoryList').val();
			var content = editor.getContent();
			var draftTime = new Date();
			if(content.length>100 && firstSave==false){
				firstTime = Date.parse(new Date());
				firstSave = true;
				var data = {
					'title' : content_title,
					'content' : content,
					'user_id' : login_user_id,
					'category_id' : category_id
				}
				var url = '/article/add_article_draft';
				var callback = function(response){
					draft_id = response;
					$('#add_article_form').attr('action','/article/add_article/draft_id/'+draft_id);
					var draft_time = new Date(nowTime).toLocaleString().replace(/:\d{1,2}$/,' ');
					$('.message_report_time').text('已在 '+draft_time+'保存草稿');
					$('.message_report').text('保存成功');
				}
				$.post(url , data , callback , 'json');
			}
			if(firstSave){
				var nowTime = Date.parse(draftTime);
				if(nowTime - firstTime > 60000){
					firstTime = Date.parse(draftTime);
					var data = {
						'title' : content_title,
						'content' : content,
						'draft_id' : draft_id,
						'user_id' : login_user_id,
						'category_id' : category_id
					}
					var url = '/article/add_article_draft';
					var callback = function(response){
						var draft_time = new Date(nowTime).toLocaleString().replace(/:\d{1,2}$/,' ');
						$('.message_report_time').text('已在 '+draft_time+'保存草稿');
						$('.message_report').text('保存成功');
					}
					$.post(url , data , callback , 'json');
				}
			}
		});
	}

	var manualDraft = function(editor,login_user_id ,old_draft_id){

		var firstTime = 0;
		if(draft_id == 0){
			draft_id = old_draft_id;
		}
		$('.draft_btn').bind('click' , function(){
			if($('.article_in_title #title').val()==''){
				alert('请填写标题!');
				return false;
			}
			var content_title = $('#title').val();
			var category_id = $('#categoryList').val();
			if(!editor.hasContents()){
				return false;
			}
			var content = editor.getContent();
			var draftTime = new Date();
			if(draft_id == 0){
				firstTime = Date.parse(new Date());
				var data = {
					'title' : content_title,
					'content' : content,
					'user_id' : login_user_id,
					'category_id' : category_id
				}
				var url = '/article/add_article_draft';
				var callback = function(response){
					draft_id = response;
					$('#add_article_form').attr('action','/article/add_article/draft_id/'+draft_id);
					var draft_time = new Date(nowTime).toLocaleString().replace(/:\d{1,2}$/,' ');
					$('.message_report_time').text('已在 '+draft_time+'保存草稿');
					$('.message_report').text('保存成功');
				}
				$.post(url , data , callback , 'json');
			}else{
				var nowTime = Date.parse(draftTime);
				if(nowTime - firstTime > 60000){
					firstTime = Date.parse(draftTime);
					var data = {
						'title' : content_title,
						'content' : content,
						'draft_id' : draft_id,
						'user_id' : login_user_id,
						'category_id' : category_id
					}
					var url = '/article/add_article_draft';
					var callback = function(response){
						var draft_time = new Date(nowTime).toLocaleString().replace(/:\d{1,2}$/,' ');
						$('.message_report_time').text('已在 '+draft_time+'保存草稿');
						$('.message_report').text('保存成功');
					}
					$.post(url , data , callback , 'json');
				}else{
					$('.message_report').text('请勿频繁保存,最小时间间隔为1分钟');
				}
			}
		
		});
	}
	exports.draftBox = draftBox;
	exports.manualDraft = manualDraft;
});
