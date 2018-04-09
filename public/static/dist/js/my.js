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
                    max: 999,
                    callback: null
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
                if (eles.length == 0) return;
                var top = $(window).scrollTop();
                if (eles && eles.length > 0) {
                    for (var i = 0; i < eles.length; i++) {
                        eles[i].MyType = "Animate";
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
            var jq_tabs = $(tabs.ele);
            if (jq_tabs.length == 0) return;
            for (var i = 0; i < jq_tabs.length; i++)
                jq_tabs[i].MyType = "Tabs";
            jq_tabs.click(function () {
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
            if (inputs.length == 0) return;
            for (var i = 0; i < inputs.length; i++)
                inputs[i].MyType = "Number";
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
                if(typeof(options.callback) === "function"){
                    var finalnum = parseInt(input.val(), 10);
                    options.callback(finalnum);
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
                if(typeof(options.callback) === "function"){
                    var finalnum = parseInt(input.val(), 10);
                    options.callback(finalnum);
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
                if(typeof(options.callback) === "function"){
                    var finalnum = parseInt(input.val(), 10);
                    options.callback(finalnum);
                }
            }).bind("paste", function () {
                var tmptxt = $(this).val();
                $(this).val(tmptxt.replace(/\D|^0/g, ''));
                if ($(this).val() < options.min || $(this).val() > options.max) {
                    $(this).val(options.min);
                    btn_minus.addClass('disabled');
                    btn_plus.removeClass('disabled');
                }
                if(typeof(options.callback) === "function"){
                    var finalnum = parseInt(input.val(), 10);
                    options.callback(finalnum);
                }
            }).css("ime-mode", "disabled");
            if(typeof(options.callback) === "function"){
                var finalnum = parseInt(input.val(), 10);
                options.callback(finalnum);
            }
        }
    });
    $.fn.getNumber = function () {
        if (this.length === 0) {
            throw "请选择正确的元素！"
        } else {
            if (this[0].MyType && this[0].MyType === "Number") {
                if (this.length === 1) {
                    return parseInt(this.find(".text-amount").val(), 10);
                }
                else {
                    throw "请选择单一的元素！"
                }
            } else {
                throw "该元素不是数量组件！"
            }
        }
    }
})(jQuery);