(function($) { // 2009 Hubsoft.com && SebringCreative.com (written by Jason Sebring, mail@jasonsebring.com) - Dual licensed under the MIT and GPL licenses.
	$.fn.dumbCrossFade = function(settings) {
		var config = {'index':0,'showTime':5000,'transitionTime':1000,'doHoverPause':true, 'maxZIndex':100, 'slideChange':null};
		var timeOut = null;
		var itemArray = [];
		var blockAnimation = false;
		var lastIndexRequest = -1;

		function cancelCrossFade() {
			if (timeOut !== null) { window.clearTimeout(timeOut); timeOut = null; }
		}

		function doCrossFadeNow() {
			if (blockAnimation) {
				if (arguments.length > 0) {
					lastIndexRequest = arguments[0];
				}
				return;
			}
			var currentIndex = config.index;
			var nextIndex = (arguments.length > 0) ? arguments[0] : (config.index >= itemArray.length - 1) ? 0 : config.index + 1;
			if (currentIndex == nextIndex) { return; }
			itemArray[currentIndex].css('z-index',(config.maxZIndex-1)+'');
			itemArray[nextIndex].css('z-index',config.maxZIndex+'');
			blockAnimation = true;
			itemArray[nextIndex].fadeIn(config.transitionTime, function() {
				itemArray[currentIndex].hide();
				blockAnimation = false;
				if (lastIndexRequest != -1) {
					doCrossFadeNow(lastIndexRequest);
					lastIndexRequest = -1;
				}
			});
			if (config.slideChange !== null) {
				config.slideChange(nextIndex);
			}
			config.index = nextIndex;
		}

		function doCrossFade() {
			cancelCrossFade();
			timeOut = window.setTimeout(function() {
				doCrossFadeNow();
				doCrossFade();
			},config.showTime);
		}
		
		if (settings) $.extend(config, settings);

		this.each(function() {
			(itemArray.length === config.index) ? $(this).show() : $(this).hide();
			if (itemArray.length === 0) {
				if (config.doHoverPause) {
					$(this).parent().hover(
						function() {
							cancelCrossFade();
						},
						function() {
							cancelCrossFade();
							doCrossFade();
						}
					);
				}
			}
			itemArray[itemArray.length] = $(this);
		});

		doCrossFade();
		
		var publicAccessor = {
			'jump' : function (index) {
				cancelCrossFade();
				doCrossFadeNow(index);
				return publicAccessor;
			},
			'start' : function () {
				doCrossFade();
				return publicAccessor;
			},
			'stop' : function () {
				cancelCrossFade();
				return publicAccessor;
			}
		};
		
		return publicAccessor;
	};
})(jQuery);