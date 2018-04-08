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
                },
                number: {
                    ele: ".item-amount",
                    min: 1,
                    max: 999
                }
            };
            option = $.extend(true, default_option, option);
            $.setAnimate(option.animate);
            $.setTabs(option.tabs);
            $.setNumberInput(option.number);
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
                var index = $(this).parent().find(tabs.ele).index(this);
                var count = $(this).parent().find(tabs.ele).length;
                actived_tab.removeClass(tabs.activeClass);
                $(this).addClass(tabs.activeClass);
                actived_form.css('display', 'none');
                $($(this).data('for')).css('display', 'block');
                underLine.css('left', (100 * index / count) + '%');
            })
        },
        setNumberInput: function (options) {
            var inputs = $(options.ele);
            var strHtml = "<a class='item-amount-minus' href='javascript:;'>-</a>" +
                "<input type='text' class='text-amount' value='" + options.min + "'/>" +
                "<a class='item-amount-plus' href='javascript:;'>+</a>";
            inputs.html(strHtml);
            var btn_minus = inputs.find(".item-amount-minus");
            var input = inputs.find(".text-amount");
            var btn_plus = inputs.find(".item-amount-plus");
            btn_minus.addClass('disabled');
            btn_minus.click(function () {
                var cur_num = parseInt(input.val(), 10);
                if (cur_num <= options.min) return;
                if (cur_num - 1 >= options.min) {
                    input.val(cur_num - 1);
                }
                if (cur_num - 1 < options.max) {
                    btn_plus.removeClass('disabled');
                }
                if (cur_num - 1 <= options.min) {
                    btn_minus.addClass('disabled');
                }
            });
            btn_plus.click(function () {
                var cur_num = parseInt(input.val(), 10);
                if (cur_num >= options.max) return;
                if (cur_num + 1 <= options.max) {
                    input.val(cur_num + 1);
                }
                if (cur_num + 1 > options.min) {
                    btn_minus.removeClass('disabled');
                }
                if (cur_num + 1 >= options.max) {
                    btn_plus.addClass('disabled');
                }
            });
            input.keyup(function () {
                var tmptxt = $(this).val();
                $(this).val(tmptxt.replace(/\D|^0/g, ''));
                if ($(this).val() < options.min || $(this).val() > options.max) {
                    $(this).val(options.min);
                    btn_minus.addClass('disabled');
                    btn_plus.removeClass('disabled');
                }
            }).bind("paste", function () {
                var tmptxt = $(this).val();
                $(this).val(tmptxt.replace(/\D|^0/g, ''));
                if ($(this).val() < options.min || $(this).val() > options.max) {
                    $(this).val(options.min);
                    btn_minus.addClass('disabled');
                    btn_plus.removeClass('disabled');
                }
            }).css("ime-mode", "disabled");
        }
    });
})(jQuery);