/**
 * 项目js文件，需要jQuery
 */
(function ($) {
    $.extend({
        /*初始化*/
        my_init: function (option) {
            var default_option = {
                animate: {
                    ele: ".animated-end"
                }
            };
            option = $.extend(default_option, option);
            $.setAnimate(option.animate);
            $(window).scroll(function (event) {
                if (option.animate) {
                    $.setAnimate(option.animate);
                }
            });
        },
        /*设置动画*/
        setAnimate: function (animate) {
            if (animate) {
                var eles = $(animate.ele);
                var top = $(window).scrollTop();
                if (eles && eles.length > 0) {
                    for (var i = 0; i < eles.length; i++) {
                        var _top = $(window).height();
                        if (top + _top - eles[i].offsetTop >= $(eles[i]).height() - 80) {
                            $(eles[i]).removeClass('transparent');
                            $(eles[i]).children().addClass('animated fadeInUp');
                        }
                    }
                }
            }
        },
    });
})(jQuery);