hitao.define('app/page' , ['jquery' , 'component/shareTmp' , 'component/getLink'] , function(require , exports){
	var $ = require('jquery');
	var shareTmp = require('component/shareTmp');
	var getComment = require('app/getComment');
	var getLink = require('component/getLink');
	function formartPage(data){
		var totalNum = data.totalNum || 0;	
		var pageSize = data.pageSize || 20;
		var pageLen = Math.ceil(totalNum / pageSize);
		data['pageLen'] = pageLen;
		data.getLink = getLink;
		data.endIds = data.endIds || [];
		if(pageLen>1){
			var pageNav = shareTmp('pagingNav' , data);
			$('#showPagingNav').html(pageNav);
		}
	}

	return function(data){
		formartPage(data);	
	}
});
