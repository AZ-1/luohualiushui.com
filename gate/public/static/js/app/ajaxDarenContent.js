hitao.define('app/ajaxDarenContent' , ['jquery' , 'component/shareTmp'] , function(require , exports){
	var $ = require('jquery');
	var shareTmp = require('component/shareTmp');
	//加载内容
	var addContent = function(){
		$(document).ready(function(){
			var data = {
			}
			var url = '/daren/ajax_daren_all_top';
			var callback = function(response){
				var content_item_skin = shareTmp('sc_skinCare' ,response);
				$('.ajaxSkin').remove();
				$('.leftTop').append(content_item_skin);
			}
			$.post(url,data,callback,'json');

			url = '/daren/ajax_daren_all_center';
			callback = function(response){
				var content_item_makeUp = shareTmp('sc_makeUp' ,response);
				$('.ajaxMakeUp').remove();
				$('.leftCenter').append(content_item_makeUp);
			}
			$.post(url,data,callback,'json');

		});



		$('.makeUp_a').bind('click',function(){
			$('.makeUp_a').removeClass('active');
			$(this).addClass('active');
			var mname = $(this).attr('mname');
			if(mname == 0){
				var url = '/daren/ajax_daren_all_center';
				var data = {
				}
			}else{
				var url = '/daren/ajax_daren_tag';
				var data = {
					'mname':$(this).attr('mname')
				}
			}
			var callback = function(response){
				var content_item = shareTmp('sc_makeUp' ,response);
				$('.ajaxMakeUp').remove();
				$('.leftCenter').append(content_item);
			}
			$.post(url,data,callback,'json');
		});


		$('.skin_a').bind('click',function(){
			$('.skin_a').removeClass('active');
			$(this).addClass('active');
			var sname = $(this).attr('sname');
			if(sname == 0){
				var url = '/daren/ajax_daren_all_top';
				var data = {
				}
			}else{
				var url = '/daren/ajax_daren_tag';
				var data = {
					'sname':sname
				}
			}
			var callback = function(response){
				var content_item = shareTmp('sc_skinCare' ,response);
				$('.ajaxSkin').remove();
				$('.leftTop').append(content_item);
			}
			$.post(url,data,callback,'json');
		});

	}
	exports.addContent = addContent;
});



/*hitao.define('app/ajaxContent' , ['jquery' , 'component/shareTmp'] , function(require , exports){
	var $ = require('jquery');
	var shareTmp = require('component/shareTmp');
	//加载内容
	var addContent = function(){
		$('.skin_a').bind('click',function(){
			var data = {
				'sname':$(this).attr('sname')
			}
			var url = '/daren/ajax_daren_tag';
			var callback = function(response){
				var content_item = shareTmp('sc_skinCare' ,response);
				$('.ajaxSkin').remove();
				$('.leftTop').append(content_item);
			}
			$.post(url,data,callback,'json');
		});
	}
	exports.addContent = addContent;
});*/
