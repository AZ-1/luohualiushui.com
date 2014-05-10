hitao.define('app/page_ajax' , ['jquery' ,'app/comment' , 'component/shareTmp' , 'component/getLink'] , function(require , exports){
	var $ = require('jquery');
	var shareTmp = require('component/shareTmp');
	var comment = require('app/comment');
	var getLink = require('component/getLink');
	var pageNum = 0;
	var getComment = comment.getComment;
	function formartPage(data){
		data = data || {};
		var totalNum = data.totalNum || 0;	
		var pageSize = data.pageSize || 20;
		var pageLen = Math.ceil(totalNum / pageSize);
		if(pageLen <= 1){ 
			getComment(data.aid, data.page ,data.seminar_id ,data.pageSize)
			return;
		};
		data['pageLen'] = pageLen;
		pageNum = data.page;
		getComment(data.aid , data.page ,data.seminar_id , data.pageSize)
		var pageNav = shareTmp('pagingNavAjax' , data);
		$('#showPagingNav').html(pageNav);
		if(data.callback && data.click){
			data.callback();
		}
	}

	return function(data){
		data = data || {};
		data.page = pageNum;
		formartPage(data);	
		$('#showPagingNav').on('click' , '.pageNav1 .pageitem' , function(){
			if(pageNum == $(this).attr('index')) return;
			data.page = $(this).attr('index');
			data.click = 1;
			formartPage(data);	
		});
		$('#showPagingNav').on('click' , '.pageNav1 .pageNext , .pageNav1 .pagePrev' , function(){
			data.page = +pageNum + (+$(this).attr('index'));
			data.click = 1;
			formartPage(data);		
		});

	}
});
