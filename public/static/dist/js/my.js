/**
 * 项目js文件，需要jQuery
 */
(function ($) {
    $.extend({
        /*初始化*/
        my_init: function (option) {
            var default_option = {
                animate: {
                    enable: true,
                    ele: ".animated-end"
                },
                tabs: {
                    enable: false,
                    ele: ".form-type-tab",
                    activeClass: 'tab-active',
                    underLine: '.active-line'
                }
            };
            option = $.extend(true, default_option, option);
            $.setAnimate(option.animate);
            $.setTabs(option.tabs);
            $(window).scroll(function (event) {
                if (option.animate) {
                    $.setAnimate(option.animate);
                }
            });
        },
        /*设置动画*/
        setAnimate: function (animate) {
            if (animate.enable) {
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
        setTabs: function (tabs) {
            if (!tabs.enable) return;
            $(tabs.ele).click(function () {
                if ($(this).hasClass(tabs.activeClass)) return;
                var actived_tab = $(tabs.ele + '.' + tabs.activeClass);
                var actived_form = $(actived_tab.data('for'));
                var underLine = $(this).parent().find(tabs.underLine);
                var index=  $(this).parent().find(tabs.ele).index(this);
                var count = $(this).parent().find(tabs.ele).length;
                actived_tab.removeClass(tabs.activeClass);
                $(this).addClass(tabs.activeClass);
                actived_form.css('display', 'none');
                $($(this).data('for')).css('display', 'block');
                underLine.css('left', (100 * index / count) + '%');
            })
        }
    });
})(jQuery);