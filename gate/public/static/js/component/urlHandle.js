hitao.define('component/urlHandle' , [] , function(require , exports){
	var getRouterParams = function(url){
		if(url == '') return '';
		var url_arr = {};
		//var hostDomain = getUrl(url).hostDomain;
		var hostDomain = window.location.host;
		url = url.replace('http://' + hostDomain , '');
		url = url.split('/');
		if(url[0] == ''){
			url = url.slice(1 , url.length);
		}
		var len = url.length -1;
		if(url[len] == ''){
			url = url.slice(0, len);
		}
		for(var i=0;i<url.length;i+=2){
			if(!url[i+1]) continue;
			url_arr[url[i]] = url[i+1];
		}
		return url_arr;
	}
	var getUrl = function(url){
		if(url == '') return '';
		var options = {
		};
		options = getParams(url);
		var tag = document.createElement('A');
		tag.href = url;
		options.hostDomain = tag.host;
		var rstr = options.hostDomain.replace(/\.(com|cn|net|org)/g,'');
		rstr = rstr.substr(rstr.lastIndexOf('.')+1);
		options.rootDomain = options.hostDomain.substr(options.hostDomain.indexOf(rstr));
		//options.rootDomain = options.hostDomain.substring(options.hostDomain.length , options.hostDomain.indexOf('.') + 1);
		return options;
	}
	function getParams(url){
		if(url == '') return '';
		var options = {};
		var name,value,i;
		var params = url.indexOf('?');
		var str = url.substr(params + 1);
		var arrtmp = str.split('&');
		for(i=0 , len = arrtmp.length;i < len;i++){
			var paramCount = arrtmp[i].indexOf('=');
			if(paramCount > 0){
				name = arrtmp[i].substring(0 , paramCount);
				value = arrtmp[i].substr(paramCount + 1);
				try{
				if (value.indexOf('+') > -1) value= value.replace(/\+/g,' ')
				options[name] = decodeURIComponent(value);
				}catch(exp){}
			}
		}
		delete options['frm'];
		return options;
	}
	exports.redirect = function(url){
		var isIe = /MSIE (\d+\.\d+);/.test(navigator.userAgent);
		if(!url) return;
		if(isIe){
			var referLink = document.createElement('a');
			referLink.href = url;
			document.body.appendChild(referLink);
			referLink.click();
		}else{
			location.href = url;
		}   
	} 
	exports.getUrl = getUrl;
	exports.getParams = getParams;
	exports.getRouterParams = getRouterParams;
});
