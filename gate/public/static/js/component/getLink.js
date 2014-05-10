hitao.define('component/getLink' , ['component/urlHandle'] , function(require , exports){
	var urlHandle = require('component/urlHandle');
	
	return function(obj , href){
		obj = obj || {};
		href = href || "";
		var query = urlHandle.getRouterParams(href);
		var hostDomain = urlHandle.getUrl(href).hostDomain;
		query = query || {};
		var url = [];
	
		for (var k in query){
			if(k in obj) continue;
			url.push( k + '/' + encodeURIComponent(query[k]));
		}

		for(var x in obj){
			if(obj[x] === null) continue;
			//href = href + '' + x + '/' + encodeURIComponent(obj[x])+'/';
			url.push( x + '/' + encodeURIComponent(obj[x]) );
		}
		url = url.join('/');
		return 'http://' + hostDomain +'/' + decodeURIComponent( url);
	}
});
