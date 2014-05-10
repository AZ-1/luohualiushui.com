hitao.define('component/waterfall',['jquery' , 'component/windowResize'],function(require , exports){	var  $ = require('jquery')
		,resizer = require('component/windowResize')
		,_settings = {
					  'colWidth' : 250 
					 ,'colNumMin' : 4 
					 ,'containerId' : '.goods_wall'
					 ,'autoResize' :false 
					 ,'pinMark' : 'poster_wall'
					 ,'offset' : {x : 0 ,y : 0}
					 ,'resizeCallback' : function(){}
					}	
		,container
		,_resizeCallback
		,_inited = false		
		,pinPath 
		,_data = {
				'totalColNum' : -1 
				,'minHeight' : 0
				,'colsHeight' : []
				,'colsCount' : []
				,'containerCols' : []
				,'indexNum' : 0
				} 
	function _dataInit(){
		if (_inited) return
		container.height(1500)
		_inited = true
		_data.minHeight = 0
		_data.indexNum = 0
		_data.colsHeight = []
		_data.colsCount = []
		for (var i = 0 ;i < _data.totalColNum;i++){
			_data.colsHeight[i] = 0
			_data.colsCount[i] = 0  
			}
		_preLayout()
	}

	function _preLayout(){
		if (!_settings.preLayout ) return
		for (var brick in _settings.preLayout){
			var col = _settings.preLayout[brick]
				,colr = null

			if ('number' != typeof col){
				colr = col[1]
				col = col[0]
				if (colr <0 ) colr +=  _data.totalColNum	
				}	
			if (col <0 ) col +=  _data.totalColNum	

			$(brick).each(function(index, preBrick){
				layout(index , preBrick , col , colr , brick.charAt(0) != '<')
				})
			}
		}	

	function onLayed(){
		_inited = false
		container.height(Math.max.apply(Math , _data.colsHeight))
		}
	function getSettings(options){
		if(_settings[options]) return _settings[options];	
	}	

	exports.init = function(settings){
		$.extend(_settings , settings)

		container = $(_settings.containerId).eq(0)
		if ('static' == container.css('position') ) container.css('position' , 'relative')
		_settings.colWidth += _settings.offset.x * 2

		_data.totalColNum =   Math.floor(container.width() / _settings.colWidth)
		if (_data.totalColNum < _settings.colNumMin) _data.totalColNum = _settings.colNumMin
		
		if (_settings.autoResize){
			resizer.bind(function(){
				if($("body").css("overflow") != "hidden" && $("html").css("overflow") != "hidden"){
					_settings.resizeCallback && _settings.resizeCallback();
					_data.totalColNum =   Math.floor(container.width() / _settings.colWidth)
					if (_data.totalColNum < _settings.colNumMin) _data.totalColNum = _settings.colNumMin
					exports.reload()
				}
				})
			}

		pinPath = _settings.containerId + '>.' + _settings.pinMark
		_dataInit()

		return this
		}
	
	function  layout (index , pin , putTo , pinTo, preHolded){
		if (!pin || 1 != pin.nodeType) return

		pin = $(pin)
		if (pinTo) pin.width((pinTo - putTo + 1) * _settings.colWidth)
		if (undefined == putTo){
			pin.addClass(_settings.pinMark)//.data('pi' ,   _data.indexNum++)
			for (var i = 0 ;i < _data.totalColNum;i++ ) {
				if (_data.colsHeight[i] <= _data.minHeight){
					putTo = i
					break
					}	
				}
		}
		pin.attr('col' , putTo).attr('colI' , _data.colsCount[putTo]++)
			.css({'position':'absolute' , 'top': _data.colsHeight[putTo] + _settings.offset.y , 'left':  putTo * _settings.colWidth + _settings.offset.x})

        var pinHeight = pin.data('height')

        if (!pin.data('layed')){
            pin.data('layed' , 1)
            if (!preHolded) pin.appendTo(container)
            }
        if (!pinHeight)   
            pin.data('height' , pinHeight =  pin.height())
        
		//pin.data('pinData' , {col : putTo , num : _data.indexNum})
				
		_data.colsHeight[putTo] += pinHeight + _settings.offset.y * 2
		if (pinTo){
			for (var i = putTo+1 ; i<= pinTo;i++)
				_data.colsHeight[i] += pinHeight + _settings.offset.y * 2
			}
		_data.minHeight = Math.min.apply(Math , _data.colsHeight)
			}

	exports.reload = function(){
		if (_data.totalColNum < 1 ) return this
		_inited = false
		
		_dataInit()
		$(pinPath).each(layout)
		onLayed()
		return this
		}

	exports.append = function(elems){
		if (_data.totalColNum < 1) return this
		container.height(Math.max.apply(Math , _data.colsHeight) + 1000);
		$(elems).each(layout)
		onLayed()
		
		return this
		}
	exports.prepend = function(elems){
		var first = $(pinPath + ':first')
		if (!first.length) 
			exports.append(elems)
		else{
			first.before( $(elems).addClass(_settings.pinMark).data('layed',1) )
			exports.reload()
			}

		}
	exports.getMinCH = function(){
		return _data.minHeight
		
		}
	exports.colReload = function (pin){
		if (!pin)
			exports.reload()	
		else{
			pin = $(pin)
			pin.removeData('height')
			var col = pin.attr('col')
				,colI = parseInt(pin.attr('colI'))

			var needReLayout = false
			var colHeight = parseInt(pin.css('top')) + pin.height() + _settings.offset.y
			_data.colsHeight[col] =  colHeight
			_data.colsCount[col] = colI + 1
			_data.minHeight = Math.min.apply(Math , _data.colsHeight)
			$(pinPath + '[col='+col+']').each(function(npin){
				npin = $(this)
				if (parseInt(npin.attr('colI')) <= colI) return
				layout(0 , npin[0] )
				})
			
			onLayed()
			}

	}
	exports.getSettings = getSettings;
	})
