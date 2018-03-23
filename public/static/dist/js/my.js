/**
 * 
 */
(function($) {
	$.extend({
		my_init: function(option) {
			var default_option = {
				animate: {
					ele: ".animated-end"
				}
			}
			option = $.extend(default_option, option);
			if(option.animate) {
				$(option.animate.ele).setAnimate();
			}
		},
	});
	$.fn.extend({
		setAnimate: function() {
			var eles = this;
			$(window).scroll(function(event) {
				var top = $(window).scrollTop();
				if(eles && eles.length > 0) {
					for(var i = 0; i < eles.length; i++) {
						var _top = $(window).height();
						if(top + _top - eles[i].offsetTop >= $(eles[i]).height() - 80) {
							$(eles[i]).removeClass('transparent')
							$(eles[i]).children().addClass('animated fadeInUp')
						}
					}
				}
			});
		},
	});
})(jQuery);