hitao.define('component/shareTmp' , ['core/sjt'] , function(require , exports){
    var sjt = require('core/sjt');
    return function(obj,data){
        data = data || Object;
		try{
			var shareTpl = sjt(obj,data); 
		}catch(e){
			hitao.debug(e);	
		}
		return shareTpl;
    };
});
