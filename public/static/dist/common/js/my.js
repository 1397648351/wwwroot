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
                },
                slide: {
                    ele: ".slide",
                    boxClass: 'slide-box',
                    animateClass: 'slide-animate',
                    interval: 5000
                }
            };
            option = $.extend(true, default_option, option);
            $.setAnimate(option.animate);
            $.setTabs(option.tabs);
            $.setNumberInput(option.number);
            $.setSlide(option.slide);
            $(window).scroll(function (event) {
                if (option.animate) {
                    $.setAnimate(option.animate);
                }
            });
        },
        /*设置动画*/
        setAnimate: function (options) {
            if (options.enable) {
                var eles = $(options.ele);
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
        /*标签页*/
        setTabs: function (options) {
            if (!options.enable) return;
            var jq_tabs = $(options.ele);
            if (jq_tabs.length == 0) return;
            for (var i = 0; i < jq_tabs.length; i++)
                jq_tabs[i].MyType = "Tabs";
            jq_tabs.click(function () {
                if ($(this).hasClass(options.activeClass)) return;
                var actived_tab = $(options.ele + '.' + options.activeClass);
                var actived_form = $(actived_tab.data('for'));
                var underLine = $(this).parent().find(options.underLine);
                var index = $(this).parent().find(options.ele).index(this);
                var count = $(this).parent().find(options.ele).length;
                actived_tab.removeClass(options.activeClass);
                $(this).addClass(options.activeClass);
                actived_form.css('display', 'none');
                $($(this).data('for')).css('display', 'block');
                underLine.css('left', (100 * index / count) + '%');
            })
        },
        /*数字Input*/
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
                if (typeof(options.callback) === "function") {
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
                if (typeof(options.callback) === "function") {
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
                if (typeof(options.callback) === "function") {
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
                if (typeof(options.callback) === "function") {
                    var finalnum = parseInt(input.val(), 10);
                    options.callback(finalnum);
                }
            }).css("ime-mode", "disabled");
            if (typeof(options.callback) === "function") {
                var finalnum = parseInt(input.val(), 10);
                options.callback(finalnum);
            }
        },
        /**
         * 轮播
         * option.ele: 所在元素
         * option.boxClass: 单个元素的类
         * option.interval: 轮播时间间隔
         */
        setSlide: function (options) {
            var cur = 1, interval;
            var eles = $(options.ele);
            if (eles.length == 0) return;
            for (var i = 0; i < eles.length; i++)
                eles[i].MyType = "Slide";
            for (var i = 0; i < eles.length; i++) {
                var ele = $(eles[i]);
                var children = ele.children('.' + options.boxClass);
                var count = children.length;
                ele.css('width', (count + 2) * 100 + "%");
                ele.prepend($(children[count - 1]).clone(true));
                ele.append($(children[0]).clone(true));
                ele.children().css('width', 100 / (count + 2) + '%');
                ele.css('left', -100 * cur + '%');
                var imgs = ele.find('.content-box.slide-box>.slide-image-box>img');
                var height = 0;
                for (var i = 0; i < imgs.length; i++) {
                    if (imgs[i].height > height) {
                        height = imgs[i].height;
                    }
                }
                imgs[0].onload = function (e) {
                    $(window).trigger("resize");
                }
                ele.parent().css('height', height);
                $(window).bind('resize', function (e) {
                    height = 0;
                    for (var i = 0; i < imgs.length; i++) {
                        if (imgs[i].height > height) {
                            height = imgs[i].height;
                        }
                    }
                    ele.parent().css('height', height);
                });
                var hover = false;
                children.mouseover(function () {
                    hover = true;
                });
                children.mouseleave(function () {
                    hover = false;
                });
                var html = '<ul class="slide-btn">';
                for (var i = 0; i < count; i++) {
                    if (i == 0)
                        html += '<li class="slide-btn-cur"><span>' + i + '</span></li>';
                    else
                        html += '<li><span>' + i + '</span></li>';
                }
                html += '</ul>';
                ele.parent().append(html);
                var slideBtns = ele.parent().children('.slide-btn').children('li');
                slideBtns.mouseover(function () {
                    hover = true;
                    window.clearInterval(interval);
                    if ($(this).hasClass('.slide-btn-cur')) return;
                    cur = slideBtns.index(this) + 1;
                    ele.parent().children('.slide-btn').children('li.slide-btn-cur').removeClass('slide-btn-cur');
                    $(this).addClass('slide-btn-cur');
                    ele.css('left', -100 * cur + '%');
                });
                slideBtns.mouseleave(function () {
                    hover = false;
                    interval = window.setInterval(function () {
                        fun_interval();
                    }, options.interval);
                });
                slideBtns.click(function () {
                    if ($(this).hasClass('.slide-btn-cur')) return;
                    window.clearInterval(interval);
                    cur = slideBtns.index(this) + 1;
                    ele.parent().children('.slide-btn').children('li.slide-btn-cur').removeClass('slide-btn-cur');
                    $(this).addClass('slide-btn-cur');
                    ele.css('left', -100 * cur + '%');
                    interval = window.setInterval(function () {
                        fun_interval();
                    }, options.interval);
                });

                function fun_interval() {
                    if (hover) return;
                    if (cur == count) {
                        cur = 1;
                    } else {
                        cur++;
                    }
                    ele.css('left', -100 * cur + '%');
                    ele.parent().children('.slide-btn').children('li.slide-btn-cur').removeClass('slide-btn-cur');
                    ele.parent().children('.slide-btn').children().eq(cur - 1).addClass('slide-btn-cur');
                }

                interval = window.setInterval(function () {
                    fun_interval();
                }, options.interval);
                //阻止事件冒泡
                //不仅仅要stopPropagation，还要preventDefault
                function pauseEvent(e) {
                    if (e.stopPropagation) e.stopPropagation();
                    if (e.preventDefault) e.preventDefault();
                    e.cancelBubble = true;
                    e.returnValue = false;
                    return false;
                }

                var startPos, endPos, startLeft;
                ele.on('touchstart', (function (e) {
                    $(this).removeClass(options.animateClass);
                    hover = true;
                    window.clearInterval(interval);
                    startPos = e.touches[0].clientX;
                    startLeft = $(this).offset().left;
                }));
                ele.on('touchmove', (function (e) {
                    var left, move_x;
                    endPos = e.touches[0].clientX;
                    move_x = endPos - startPos;
                    left = startLeft + move_x;
                    $(this).css('left', left);
                }));
                ele.on('touchend', (function (e) {
                    $(this).addClass(options.animateClass);
                    var move_x = 0;
                    if(endPos)
                        move_x = endPos - startPos;
                    if (Math.abs(move_x) < 150) {
                        $(this).css('left', -100 * cur + '%');
                        $(this).parent().children('.slide-btn').children('li.slide-btn-cur').removeClass('slide-btn-cur');
                        $(this).parent().children('.slide-btn').children().eq(cur - 1).addClass('slide-btn-cur');
                    } else {
                        if (move_x < 0) {
                            if (cur == count) {
                                cur = 1;
                            } else {
                                cur++
                            }
                        } else {
                            if (cur == 1) {
                                cur = count;
                            } else {
                                cur--
                            }
                        }
                        $(this).css('left', -100 * cur + '%');
                        $(this).parent().children('.slide-btn').children('li.slide-btn-cur').removeClass('slide-btn-cur');
                        $(this).parent().children('.slide-btn').children().eq(cur - 1).addClass('slide-btn-cur');
                    }
                    hover = false;
                    interval = window.setInterval(function () {
                        fun_interval();
                    }, options.interval);
                }));
            }
        }
    });
    /*获取数字Input值*/
    $.fn.getNumber = function () {
        if (this.length === 0) {
            throw "请选择正确的元素！"
        } else {
            if (this.length === 1) {
                if (this[0].MyType && this[0].MyType === "Number") {
                    return parseInt(this.find(".text-amount").val(), 10);
                } else {
                    throw "该元素不是数量组件！"
                }
            }
            else {
                throw "请选择单一的元素！"
            }

        }
    };
    /*获取数字Input值*/
    $.fn.getTabVal = function () {
        if (this.length === 0) {
            throw "请选择正确的元素！"
        } else {
            if (this.length === 1) {
                var ele = this.find(".form-type-tab.tab-active");
                if (ele[0].MyType && ele[0].MyType === "Tabs") {
                    return ele.data("for");
                } else {
                    throw "该元素不是数量组件！"
                }
            }
            else {
                throw "请选择单一的元素！"
            }
        }
    }
})(jQuery);