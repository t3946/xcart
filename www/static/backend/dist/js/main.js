(function($) {
    'use strict';
    var AjaxTooltip = function (element, options) {
        return this.init(element, options);
    };

    AjaxTooltip.prototype = {
        options: {
            onAction: {
                leftClick: false,
                middleClick: false,
                rightClick: true
            },
            ajax: {
                type: "GET",
                dataType: null
            },

            autoclose: false,
            autoclosedelay: 1000,

            onBeforeStart: $.noop,
            onSuccess: $.noop,
            // onBeforeOpen: $.noop,
            // onAfterOpen: $.noop,
            // onBeforeClose: $.noop,
            // onAfterClose: $.noop,
            onSubmit: 'default',
            onAfterSubmit: $.noop,
            onAfterSuccess: $.noop
        },
        classes: {
            container: 'mtooltip'
        },
        _tooltip: null,
        _element: null,
        data: [],
        init: function (element, options) {
            this.options = $.extend(this.options, options);

            var self = this;

            $(element).on('contextmenu.ajax-tooltip', function (e) {
                e.preventDefault();
            });

            $(element).on('mousedown.ajax-tooltip', function (e) {
                if ((
                        (e.which == 1 && self.options.onAction.leftClick)
                        || (e.which == 2 && self.options.onAction.middleClick)
                        || (e.which == 3 && self.options.onAction.rightClick)
                    )
                    && ( !self._element || !$(e.target).closest(self._element).length)) {
                    self.removeAll();
                    self._element = $(this);
                    self.start.call(self, this);
                }
            });

            return this;

        },
        resizeContainer: function ($target, $tooltip, animate) {
            var posLeft, posTop,
                offset = $target.offset(),
                width = $target.attr('data-tooltip-width');

            $tooltip.addClass($target.attr('data-tooltip-cls'));

            if (width) {
                $tooltip.css('width', width);
            } else {
                // $tooltip.css('width', $tooltip.width() / 2);
            }

            posLeft = offset.left + ($target.outerWidth() / 2) - ($tooltip.outerWidth() / 2);
            posTop = offset.top - $tooltip.outerHeight() - 20;

            if (posLeft < 0) {
                posLeft = offset.left + target.outerWidth() / 2 - 20;
                $tooltip.addClass('left');
            } else {
                $tooltip.removeClass('left');
            }

            if (posLeft + $tooltip.outerWidth() > $(window).width()) {
                posLeft = offset.left - $tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                $tooltip.addClass('right');
            } else {
                $tooltip.removeClass('right');
            }

            var isTop = posTop < 0;
            if (posTop < 0) {
                $tooltip.addClass('top');
            } else {
                $tooltip.removeClass('top');
            }

            if (animate) {
                if (isTop) {
                    $tooltip.css({
                        left: posLeft,
                        // top: window.pageYOffset + window.innerHeight,
                        top: posTop + 200,
                    });
                } else {
                    $tooltip.css({
                        left: posLeft,
                        // top: window.pageYOffset,
                        top: posTop - 200,
                    });
                }

                return $tooltip.animate({
                    top: posTop,
                    opacity: 1
                }, 300);
            }
            else {
                if (isTop) {
                    $tooltip.css({
                        left: posLeft,
                        top: posTop + 20,
                        opacity: 1
                    });
                } else {
                    $tooltip.css({
                        left: posLeft,
                        top: posTop,
                        opacity: 1
                    });
                }

                return true
            }
        },
        setContent: function ($html) {
            if (!$html) {
                this.removeTooltip.call(this, this._tooltip);
                return false
            }

            this._tooltip.html($html);
            if (this._tooltip.find('form').length > 0) {
                var self = this;
                this._tooltip.find("[type='submit']").off("click").on("click", function (e) {
                    e.preventDefault();
                    self._submitHandler.call(self, this);
                    return false;
                });
                this._tooltip.find("form").off("submit").on("submit", function (e) {
                    e.preventDefault();
                    self._submitHandler.call(self, this);
                    return false;
                });
            }

            this.resizeContainer($(this._element), this._tooltip)
        },
        _submitHandler: function (element) {
            if (typeof this.options.onSubmit == 'function') {
                this.options.onSubmit.call(this, element);
            } else {
                this._submitHandlerDefault.call(this, element);
            }
        },
        _submitHandlerDefault: function (element) {
            var self = this,
                content = '',
                options = this.options,
                $data = {},
                $context = $($(element).context),
                $form = $(element).closest('form');

            $data = $form.serialize();

            if ($context.is('[type="submit"]')) {
                $data[$context.attr('name')] = $context.val();
                $form = $context.closest('form');
            }
            $.ajax({
                url: $form.attr('action'),
                type: "post",
                cache: false,
                data: $data,
                success: function (data, textStatus, jqXHR) {
                    try {
                        data = $.parseJSON(data);
                    } catch (e) {
                    }

                    options.onSuccess.call(this, data, textStatus, jqXHR);
                    if (data.close){
                        return self.close();
                    }
                    if (data) {
                        content = data['content'] || data['title'] || data;
                        self.setContent(content);
                        self.options.onAfterSuccess.call(self);
                    }

                    if (!data) {
                        self.close();
                    } else if (data.status === 'success' && options.autoclose) {
                        setTimeout(function () {
                            return self.close.call(self);
                        }, options.autoclosedelay);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $.mnotify({
                        title: 'Tooltip error',
                        message: jqXHR.responseText
                    });

                    self.close.call(self);
                }
            });

            this.options.onAfterSubmit.call(this);
        },
        renderContainer: function(element) {
            this._tooltip = $('<div class="'+ this.classes.container +'"><div class="load"></div></div>');
            this._tooltip.css('opacity', 0).appendTo('body');

            // this.resizeContainer($(element), this._tooltip, false);
            return this.resizeContainer($(element), this._tooltip, true);
        },
        getContent: function (element) {
            this._element = element;
            var $this = $(element),
                self = this,
                def = new $.Deferred();


            $.ajax({
                dataType: this.options.ajax.dataType,
                type: this.options.ajax.type,
                url: $this.data('tooltip-action'),
                cache: false,
                success: function (data, textStatus, jqXHR) {
                    var $html = null;

                    if (self.options.ajax.dataType  == 'json') {
                        $html = data.content;
                    } else {
                        $html = data;
                    }

                    def.resolve($html);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    def.resolve(jqXHR.responseText);
                }
            });

            return def.promise();
        },
        start: function (element) {
            var self = this;
            this.options.onBeforeStart();

            $.when(this.renderContainer(element), self.getContent(element)).done(function(animation, html){
                self.setContent.call(self, html);
                self.bindEvents();
            });
        },
        close: function() {
            this.removeTooltip.call(this, this._tooltip);
        },
        removeAll: function() {
            var self = this;
            this.unbindEvents();

            $('body .' + this.classes.container).each(function(){
                self.removeTooltip($(this));
            });
        },
        bindEvents: function () {
            var self = this;

            $(window).on('resize.ajax-tooltip', function () {
                self.resizeContainer.call(self, $(this), self._tooltip);
            });

            $(document).on('mousedown.ajax-tooltip-remove', function (e) {
                if (!$(e.target).closest(self._tooltip).length
                    && !$(e.target).closest(self._element).length
                ) {
                    self.removeTooltip.call(self, self._tooltip);
                }
            });
        },
        unbindEvents:function() {
            $(document).unbind('mousedown.ajax-tooltip-remove');
            $(window).unbind('resize.ajax-tooltip');
        },
        removeTooltip: function (tooltip, $target) {
            this.unbindEvents();
            this._element = null;
            // tooltip.remove();
            tooltip.animate({top: '-=100', opacity: 0}, 300, function () {
                tooltip.remove();
            });
        }
    };

    $.fn.majaxtooltip = function (options) {
        return new AjaxTooltip(this, options)
    };

})(jQuery);

(function($){
    "use strict";

    var Dashboard = function (element, options) {
        return this.init(element, options);
    };

    Dashboard.prototype = {
        options : {
            ajax: {
                type: 'get',
                dataType: 'json',
                url: '',
                cache: false
            },
            triggers: {
                refresh: 'dashboard:refresh'
            },
            classes: {
                enabled: 'button',
                disabled: 'empty'
            },
            notify: {
                lifetime: 20000,
                titles : {
                    new_events: 'Dashboard new events',
                    err_refresh: 'Dashboard refresh error'
                }
            },
            interval: 10000,
            selector: '.dashboard-filters a[data-id]',
            questionSelector: '.admin .question_products'
        },

        __stop: false,
        __first: true,

        init: function(element, options) {
            this.options = $.extend(this.options, options);

            this.start();

        },
        processData:function(data) {
            var self = this;
            var texts= [];
            var notify = false;

            $(this.options.questionSelector).html(data.questions);

            $(this.options.selector).each(function(){
                var $this = $(this),
                    id = $this.data('id'),
                    count = parseInt(this.dataset.count);

                if (data.filters[id]) {
                    var data_filter = data.filters[id];
                    var t_count = data_filter['count']['orders'];
                    var t_events = data_filter['count']['events'];
                    var t_priority = data_filter['count']['priority'];
                    var count_events ='', count_priority = '', c_chng = t_count - count;

                    if (c_chng > 0) {
                        count += ' +' + c_chng;
                        notify = true;
                    }

                    if (t_events && t_events > 0) {
                        count_events = '+' + t_events;
                    }

                    if (t_priority && t_priority > 0) {
                        count_priority = t_priority;
                    }

                    this.dataset.count = t_count;
                    $this.find('.events').toggleClass(self.options.classes.disabled, (t_events == 0)).html(count_events);
                    $this.find('.priority').toggleClass(self.options.classes.disabled, (t_priority == 0)).html(count_priority);

                    if (self.__first) {
                        $this.find('.count').html(t_count);
                    }
                    else {
                        $this.find('.count').html(count);
                    }

                    if (t_count > 0 ) {
                        $this.toggleClass(self.options.classes.disabled, false);
                        $this.toggleClass(self.options.classes.enabled, true);

                    }
                    else {
                        $this.toggleClass(self.options.classes.enabled, false);
                        $this.toggleClass(self.options.classes.disabled, true);
                    }

                    if (c_chng > 0) {
                        data.filters[id]['notify_text'] =  '<a target="_blank" href="'+ $this.attr('href') +'">'+ $this.find('.name_events').html() +'</a>';
                    }
                }
            });

            notify = false;

            if (notify && !self.__first)
            {
                for (var i in data.filters)
                {
                    if (data.filters[i]['notify_text']) {
                        texts.push(data.filters[i]['notify_text'])
                    }
                }

                if (texts.length)
                {
                    texts = texts.map(function(el){
                        return '<li>'+el+'</li>';
                    });

                    $.mnotify({
                        title: self.options.notify.titles.new_events,
                        message: '<ul>'+texts.join('')+'</ul>'
                    }, {lifetime: self.options.notify.lifetime});
                }
            }


            this.cycleRefresh();
        },
        refresh: function() {
            var self = this;
            var url = this.options.ajax.url;

            url += ((url.indexOf('?') !== -1) ? "&" : "?") + "_=" +(new Date()).getTime();


            $.ajax({
                dataType: this.options.ajax.dataType,
                type: this.options.ajax.type,
                url: url,
                cache: this.options.ajax.cache,

                success: function (data, textStatus, jqXHR) {

                    self.processData.call(self, data);
                },

                error: function (jqXHR, textStatus, errorThrown) {
                    /*$.mnotify({
                        title: self.options.notify.titles.err_refresh,
                        message: jqXHR.responseText
                    });*/

                    self.cycleRefresh();
                }
            });
        },

        cycleRefresh: function() {
            if (!this.__stop) {
                var self = this;
                self.__first = false;

                setTimeout(function () {
                    $(document).trigger(self.options.triggers.refresh);
                }, this.options.interval);
            }
            else {
                this.__stop = false;
            }
        },
        firstRefresh: function() {
            if (!this.__stop) {
                var self = this;

                setTimeout(function () {
                    $(document).trigger(self.options.triggers.refresh);
                }, 400);

            }
            else {
                this.__stop = false;
            }
        },
        bindEvents: function() {
            var self = this;
            $(document).on(self.options.triggers.refresh, function(){
                self.refresh.call(self);
            });
        },
        unbindEvents: function() {
            $(document).unbind(self.options.triggers.refresh);
        },
        unbind:function() {
            this.unbindEvents();
            this.__stop = true;
        },
        start: function() {
            this.bindEvents();
            this.firstRefresh();
        }
    };

    $.fn.dashboard = function(options) {
        return new Dashboard(this, options);
    }
})(jQuery);
$(function () {
    var flashOutTime = 7000;

    var $flashList = $('.flash-messages-block .flash-list');

    $(document).on('click', '.close-flash, .flash-message', function (e) {
        e.preventDefault();
        $(this).closest('.flash-message').fadeOut(400, function () {
            $(this).remove();
        });
        return false;
    });

    window.addFlashMessage = function (message, type, time) {
        type = type ? type : 'success';
        var outTime = (time && time > flashOutTime) ? time : flashOutTime;

        var $item = $('<div class="flash-message"></div>').addClass(type);
        var $closer = $('<a class="close-flash right"><i class="icon-delete_in_filter"></i></a>');
        var $text = $('<span/>').addClass('message').innerHTML = message;

        $item.append($closer);
        $item.append($text);
        $flashList.append($item);

        setTimeout(function () {
            if ($item && $item.length > 0) {
                $item.fadeOut(400, function () {
                    $(this).remove();
                });
            }
        }, outTime);
    };

    $(document).ready(function () {
        if (window['flashStack'] && window['flashStack'].length) {
            for (var i in window['flashStack']) {
                var f = window['flashStack'][i];
                addFlashMessage(f.message, f.type, f.time);
            }
        }
    });
});
$(function () {
    $(document).on('form-removed', function (e, $element, data) {
        if ($element.data('all')) {
            window.location.href = $element.data('all');
        }
    });
});
window['forms'] = {
    loadForm: function(form) {
        var url = forms.extendUrl(window.location.toString(), 'form', form);

        $.ajax(url, {
            type: 'GET',
            success: (data) => {

                var $page = $('<div/>').append(data);
                var newForm = $page.find('.form-page');

                $(document).find('.form-page').replaceWith(newForm);
            }
        });
    },

    extendUrl: function(url, key, value) {
        var params = {};
        var cleanUrl = url;

        if (url.indexOf('?') !== -1) {
            cleanUrl = url.substr(0, url.indexOf('?'));
            var paramsString = url.substr(url.indexOf('?') + 1);
            params = $.deparam(paramsString);
        }
        params[key] = value;
        paramsString = $.param(params);

        return cleanUrl + '?' + paramsString;
    }
};
/**
 * @author Falaleev Maxim <max@studio107.ru>
 *
 * Simple usage:
 * $.mmodal('<h1>Hello!</h1>'); or $("a.mmodal").mmodal();
 * or
 * $("a.mmodal").on('click', function(e) {
 *      $.mmodal('<h1>Hello!</h1>');
 *      e.preventDefault();
 *      return false;
 * });
 * or
 * $('a#inline').mmodal()
 * <div id="inline" class="mmodal-modal">
 *     <h2>Beautiful!</h2>
 * </div>
 */

(function ($) {
    var mmodal = function (element, options) {
        return this.init(element, options);
    };

    mmodal.prototype = {
        version: "1.1",
        locked: true,
        $element: undefined,
        $content: undefined,
        $bg: undefined,
        $container: undefined,

        params: {},

        inline: false,
        inlineIndex: undefined,
        $inlineParent: undefined,

        classes: {
            content: 'mmodal-content',
            container: 'mmodal-container',
            animation: 'animated',
            background: 'mmodal-modal-bg',
            closeButton: 'mmodal-close',
            bodyClass: 'mmodal-opened'
        },
        init: function (element, options) {
            var defaultOptions = {
                ajax: {
                    type: "GET",
                    dataType: null
                },
                animation: false,
                animationdelay: 1.3,
                skin: 'default',
                width: undefined,
                closeonclick: true,
                closeonescape: true,

                touchEvents: false,

                autoclose: false,
                autoclosedelay: 1450,

                onBeforeStart: $.noop,
                onSuccess: $.noop,
                onBeforeOpen: $.noop,
                onAfterOpen: $.noop,
                onBeforeClose: $.noop,
                onAfterClose: $.noop,
                onSubmit: 'default'
            };
            this.locked = true;

            this.$element = element instanceof Object ? element : $(element);

            this.options = $.extend(defaultOptions, options);

            if (this.$element.is("a")) {
                this._prepareLink();
            } else {
                this.start(this.$element.clone());
            }

            this.params['lineHeight'] = this.getLineHeight($('html'));
            this.params['pageHeight'] = this.getPageHeight($('html'));

            return this;
        },
        getLineHeight: function(elem) {
            var $elem = $(elem),
                $parent = $elem['offsetParent' in $.fn ? 'offsetParent' : 'parent']();
            if (!$parent.length) {
                $parent = $('body');
            }
            return parseInt($parent.css('fontSize'), 10) || parseInt($elem.css('fontSize'), 10) || 16;
        },

        getPageHeight: function(elem) {
            return $(elem).height();
        },

        getContainer: function () {
            return this.$container == undefined ? this.renderContainer() : this.$container;
        },
        setContent: function ($html) {
            var $content = this.$content;

            $content.html($html);
            var $forms = $content.find('form');
            if ($forms.length > 0) {
                var self = this;
                $forms = $forms.filter(function (index, el) {
                    if ($(el).data('ajax-send') == 'off') {
                        return false;
                    }
                    return true;
                });

                $forms.find("[type='submit']").off("click").on("click", function (e) {
                    e.preventDefault();
                    self._submitHandler.call(self, this);
                    return false;
                });
                $forms.off("submit").on("submit", function (e) {
                    e.preventDefault();
                    self._submitHandler.call(self, this);
                    return false;
                });
            }
        },
        renderContainer: function () {
            this.$content = $('<div />')
                .addClass(this.classes.content);

            this.$close = $('<a href="#">&times;</a>')
                .addClass(this.classes.closeButton);

            this.$container = $('<div />')
                .addClass(this.classes.container + ' ' + this.options.skin);

            if (this.options.animation) {
                this.$container.addClass(this.classes.animation + ' ' + this.options.animation + 'In');
            }

            this.$container.append(this.$close)
                .append(this.$content);

            this.$bg = $("<div />")
                .addClass(this.classes.background + ' ' + this.options.skin)
                .append(this.$container)
                .appendTo('body');

            return this.$container;
        },
        _prepareLink: function () {
            var self = this,
                href = this.$element.attr('href'),
                $html;

            if (href.match(/^#/)) {
                var $targetContainer = $(href);
                // Get container and inner html content
                $html = $targetContainer[0];

                this.$inlineParent = $targetContainer.parent();
                this.inlineIndex = $targetContainer.index();
                this.inline = true;

                $targetContainer.detach();
                this.start($html);
            } else {
                $.ajax({
                    dataType: this.options.ajax.dataType,
                    type: this.options.ajax.type,
                    url: href,
                    cache: false,
                    success: function (data, textStatus, jqXHR) {
                        if (self.options.ajax.dataType == 'json') {
                            $html = data.content;
                        } else {
                            $html = data;
                        }
                        self.start.call(self, $html);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        self.start.call(self, jqXHR.responseText);
                    }
                });
            }
        },
        _submitHandler: function (element) {
            if (typeof this.options.onSubmit == 'function') {
                this.options.onSubmit.call(this, element);
            } else {
                this._submitHandlerDefault.call(this, element);
            }
        },
        _submitHandlerDefault: function (element) {
            var self = this,
                content = '',
                options = this.options,
                $data = {},
                $form = $(element),
                $context = $form;

            if ($context.attr('type') === 'submit') {
                $data[$context.attr('name')] = $context.val();
                $form = $context.closest('form');
            }

            $form.ajaxSubmit({
                type: "post",
                data: $data,
                success: function (data, textStatus, jqXHR) {
                    try {
                        data = $.parseJSON(data);
                    } catch (e) {
                    }

                    options.onSuccess.call(this, data, textStatus, jqXHR);
                    if (data.close) {
                        return self.close();
                    }
                    if (data) {
                        content = data['content'] || data['title'] || data;
                        self.setContent(content);
                    }

                    if (!data) {
                        self.close();
                    } else if (data.status === 'success' && options.autoclose) {
                        setTimeout(function () {
                            return self.close();
                        }, options.autoclosedelay);
                    }
                }
            });
            self.close();
        },
        start: function (html) {
            this.options.onBeforeStart();
            this.renderContainer();
            this.setContent(html);
            this.bindEvents();
            this.registerInDocument();
            this.open();
        },
        registerInDocument: function() {
            $(document).data('mmodal', this);
        },
        open: function () {
            var $body = $('body'),
                before = $body.outerWidth();

            this.options.onBeforeOpen.call(this, this.$content[0]);

            this.$bg.show();
            if(this.options.windowClass !== 'undefined') {
                this.$container.addClass(this.options.windowClass);
            }

            if(this.options.setWidth !== 'undefined' && this.options.setWidth == false) {
                this.$container.show();
            } else {
                this.$container.css('width', this.options.width || this.$container.width()).show();
            }

            $body.css({
                'overflow': 'hidden',
            }).addClass(this.classes.bodyClass);

            $body.css({
                'padding-right': $body.outerWidth() - before
            });

            this.options.onAfterOpen();
        },
        close: function () {
            this.options.onBeforeClose();

            $('body').off('keyup').css({
                'overflow': '',
                'padding-right': ''
            }).removeClass(this.classes.bodyClass);

            this.$close.off('click');
            this.$bg.off('click');

            if (this.inline) {
                var href = this.$element.attr('href');
                var $target = this.$inlineParent.children(":eq(" + this.inlineIndex + ")");
                var $originalContent = this.$content.children();
                if ($target > -1) {
                    $target.prepend($originalContent);
                } else {
                    this.$inlineParent.append($originalContent);
                }
            }

            if (this.options.animation) {
                this.$container.addClass(this.classes.animation + ' ' + this.options.animation + 'Out');

                var self = this;
                setTimeout(function () {
                    self.$bg.remove();
                }, this.options.animationdelay * 1000);
            } else {
                this.$bg.remove();
            }

            this.options.onAfterClose();
        },
        bindEvents: function () {
            var self = this, options = this.options;

            this.$close.on('click', function (e) {
                e.preventDefault();
                self.close();
                return false;
            });

            if (options.closeonclick == true) {
                this.$bg.addClass('clickable');
                this.$bg
                    .on('click', function (e) {
                        if (e.target === this) {
                            e.preventDefault();
                            self.close();
                            return false;
                        }
                    });
            }

            this.$bg
                .on('mousewheel', function (e) {
                    var $this = $(this);
                    var deltaY = e.originalEvent.wheelDeltaY || e.originalEvent.deltaY;
                    var mode = e.originalEvent.deltaMode;

                    if (mode === 1) {
                        deltaY *= self.params.lineHeight * -1;
                    }
                    else if (mode === 2) {
                        deltaY *= self.params.pageHeight;
                    }

                    $this.scrollTop($this.scrollTop() - deltaY);

                    return false;
                });

            if (this.options.touchEvents) {
                this.$bg
                    .on("touchstart", function (e) {
                        self.touches = {
                            'startingY': e.originalEvent.touches[0].pageY,
                            'startingX': e.originalEvent.touches[0].pageX,
                        };
                    })


                    .on('touchmove', function (e) {
                        var $this = $(this),
                            $window = $(window);

                        var deltaY = e.originalEvent.touches[0].pageY - self.touches.startingY;
                        var deltaX = e.originalEvent.touches[0].pageX - self.touches.startingX;

                        $this.scrollTop($this.scrollTop() - deltaY);
                        $this.scrollLeft($this.scrollLeft() - deltaX);

                        self.touches.startingY = e.originalEvent.touches[0].pageY;
                        self.touches.startingX = e.originalEvent.touches[0].pageX;

                        return false;
                    });
            }


            if (options.closeonescape == true) {
                $('body').on('keyup', function (e) {
                    if (e.which === 27) {
                        self.close();
                    }
                });
            }
        }
    };

    $.fn.mmodal = function (options) {
        return new mmodal(this, options);
    };
})(jQuery);

$(function () {
    var list = {
        options: {
            url: undefined,
            groupActionUrl: undefined,
            sortUrl: undefined,
            columnsUrl: undefined,
            searchTimeout: 500
        },
        id: undefined,
        currentUrl: undefined,
        $listBlock: undefined,

        _searchTimer: undefined,
        _searchQuery: undefined,
        _searchRequest: undefined,

        init: function (options) {
            this.options = $.extend(this.options, options);
            this.currentUrl = this.options.url;
            this.id = this.$listBlock.data('id');
            this.initSort();
        },
        setUrl: function (url) {
            if (window.history) {
                window.history.pushState({}, document.title, url);
            }

            this.currentUrl = url;
            this.update();
        },
        modifyUrl: function (key, value) {
            var url = this.currentUrl;
            var params = {};
            var cleanUrl = url;
            if (url.indexOf('?') !== -1) {
                cleanUrl = url.substr(0, url.indexOf('?'));
                var paramsString = url.substr(url.indexOf('?') + 1);
                params = $.deparam(paramsString);
            }
            params[key] = value;
            paramsString = $.param(params);
            this.setUrl(cleanUrl + '?' + paramsString)
        },
        extendUrl: function(url, key, value) {
            var params = {};
            var cleanUrl = url;

            if (url.indexOf('?') !== -1) {
                cleanUrl = url.substr(0, url.indexOf('?'));
                var paramsString = url.substr(url.indexOf('?') + 1);
                params = $.deparam(paramsString);
            }
            params[key] = value;
            paramsString = $.param(params);

            return cleanUrl + '?' + paramsString;
        },
        setListBlock: function ($listBlock) {
            this.$listBlock = $listBlock;
        },
        getListSelector: function () {
            return '[data-id="' + this.id + '"]';
        },
        getUpdateBlockSelector: function () {
            return this.getListSelector() + ' .list-update-block';
        },
        getTable: function () {
            return this.$listBlock.find('[data-list-table]');
        },
        setLoading: function () {
            this.$listBlock.addClass('loading');
        },
        unsetLoading: function () {
            this.$listBlock.removeClass('loading');
        },
        update: function () {
            var me = this;
            me.setLoading();

            this._searchRequest = $.ajax({
                url: this.currentUrl,
                beforeSend: () => {
                    this._searchRequest?.abort()
                },
                success: function (page) {
                    var $page = $('<div/>').append(page);
                    var ubSelector = me.getUpdateBlockSelector();
                    $(ubSelector).replaceWith($page.find(ubSelector));
                    me.initSort();
                    me.unsetLoading();
                }
            });
        },
        getPkList: function () {
            var pkList = [];
            this.$listBlock.find('input[type=checkbox][name="pk_list[]"]:checked').each(function () {
                var $checkbox = $(this);
                pkList.push($checkbox.val());
            });
            return pkList;
        },
        groupAction: function (action) {
            var me = this;
            me.setLoading();
            $.ajax({
                url: me.options.groupActionUrl,
                type: 'post',
                dataType: 'json',
                data: {
                    action: action,
                    pk_list: me.getPkList()
                },
                success: function (data) {
                    me.unsetLoading();
                    var type = 'success';
                    if (!data.success) {
                        type = 'error';
                    }
                    if (data.message) {
                        window.addFlashMessage(data.message, type);
                    }

                    if (data.success) {
                        me.update();
                    }
                }
            })
        },
        search: function (search) {
            var me = this;
            if (me._searchQuery !== search) {
                me._searchQuery = search;
                me.setLoading();
                clearTimeout(me._searchTimer);
                me._searchTimer = setTimeout(function () {
                    me.processSearch(search);
                }, me.options.searchTimeout);
            }
        },
        processSearch: function (search) {
            var me = this;
            me.modifyUrl('search', search);
        },
        initSort: function () {
            var me = this;
            var $table = me.getTable();

            if (typeof $table.attr('data-sorting') !== typeof undefined)
            {
                $table.find("tbody").sortable({
                    axis: 'y',
                    placeholder: "highlight",
                    handle: ".sort",
                    start: function(e, ui){
                        ui.placeholder.height(ui.item.height());
                    },
                    helper: function (e, ui) {
                        ui.children().each(function () {
                            var $this = $(this);
                            $this.width($this.width());
                        });
                        return ui;
                    },
                    update: function (event, ui) {
                        var $to = $(ui.item),
                            $prev = $to.prev(),
                            $next = $to.next();

                        var pk_list = $(this).sortable('toArray', {
                            attribute: 'data-pk'
                        });

                        me.setSort(pk_list, $to.data('id'), $prev.data('id'), $next.data('id'))
                    }
                });
            }
        },
        setSort: function (pk_list, to, prev, next) {
            var me = this;
            $.ajax({
                url: me.options.sortUrl,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'sort',
                    pk_list: pk_list,
                    to: to,
                    prev: prev,
                    next: next
                },
                success: function (data) {
                    me.update();
                }
            })
        },
        saveColumns: function () {
            var columns = [];
            var me = this;

            this.$listBlock.find('[name="columns_list[]"]:checked').each(function() {
                columns.push($(this).val());
            }).val();

            $.ajax({
                url: me.options.columnsUrl,
                type: 'post',
                dataType: 'json',
                data: {
                    columns: columns
                },
                success: function (data) {
                    if (data.success) {
                        me.update();
                    }
                }
            });
        }
    };

    $.fn.adminList = function(options) {
        var item = $.extend(true, {}, list);
        item.setListBlock(this);
        this.data('object', item);
        item.init(options);
    };


    function getListBlock($element)
    {
        return $element.closest('.list-block');
    }

    function getList($element)
    {
        var $listBlock = getListBlock($element);
        return $listBlock.data('object');
    }

    function showPopup($this)
    {
        var w = 900;
        var h = 600;
        var left = (window.screen.width/2)-(w/2);
        var top = (window.screen.height/2)-(h/2);

        var list = getList($this);
        var hndl = window.open(list.extendUrl($this.attr('href'),'popup', true), document.title, "width="+w+", height="+h+", left="+left+", top="+top+", scrollbars=yes");
        // hndl.moveTo(left, top);

        var fnc = function(e) {
            setTimeout(()=>{
                if (hndl.closed) {
                    list.update();
                }
                else {
                    hndl.onbeforeunload = fnc;
                }
            }, 1000);
        };

        hndl.onbeforeunload = fnc;
    }

    $(document).on('click', '.list-block .pagination-block a', function (e) {
        e.preventDefault();
        var $this = $(this);
        var list = getList($this);
        list.setUrl($this.attr('href'));
        return false;
    });

    $(document).on('click', '.list-block a.ajax, .ajax a', function (e) {
        e.preventDefault();
        const $this = $(this);

        if (typeof this.dataset.prevention === 'undefined') {
            const list = getList($this)
            list.setLoading();
            $this.mmodal({
                onSubmit: function (element)  {
                    list.setLoading();
                    this._submitHandlerDefault.call(this, element);
                },
                onAfterOpen: () => list.unsetLoading(),
                onSuccess: () => list.update(),
            });
        }

        return false;
    });

    $(document).on('click', '.list-block table thead a.title', function (e) {
        e.preventDefault();
        var $this = $(this);
        var list = getList($this);
        list.setUrl($this.attr('href'));
        return false;
    });

    $(document).on('change', '.list-block .pagination-block [data-pagesize]', function (e) {
        var $this = $(this);
        var url = $this.val();
        var list = getList($this);
        list.setUrl(url);
    });

    $(document).on('click', '.list-block [data-group-remove]', function (e) {
        e.preventDefault();
        var $this = $(this);
        var url = $this.val();
        var list = getList($this);
        list.groupAction('remove');
        return false;
    });

    $(document).on('click', '.list-block [data-group-submit]', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $listBlock = getListBlock($this);
        var action = $listBlock.find('[data-group-action]').val();
        if (action) {
            var list = getList($this);
            list.groupAction(action);
        }
        return false;
    });

    $(document).on('change keyup', '.list-block [data-list-search]',function (e) {
        e.preventDefault();
        var $this = $(this);
        var list = getList($this);
        list.search($this.val());
    });

    $(document).on('list-update', function (e, $element) {
        var list = getList($element);
        list.update();
    });

    $(document).on('click', '.appender-columns', function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.closest('.columns-list-appender').toggleClass('list');
        return false;
    });

    $(document).on('change', '.columns-list-appender input', function () {
        var list = getList($(this));
        list.saveColumns();
    });
});
storage = {
    LocalStorageChecked: null,
    hasLocalStorage: function (){
        // "use strict";

        if (this.LocalStorageChecked === null)
        {
            var test = 'test';
            try {
                localStorage.setItem(test, test);
                localStorage.removeItem(test);
                this.LocalStorageChecked = true;
            } catch(e) {
                this.LocalStorageChecked = false;
            }
        }

        return this.LocalStorageChecked;
    },

    get: function (key, def) {
        if (def == 'undefined') {
            def = null;
        }

        if (this.hasLocalStorage()) {
            value = localStorage.getItem(key);
        }
        else {
            var value = $.cookie(key);
        }

        if (!value) {
            value = def;
        }
        return value;
    },

    set: function (key, value, expires) {
        if (value === null) {
            this.remove(key);
            return;
        }

        if (this.hasLocalStorage()) {

            localStorage.setItem(key, value)
        }
        else {
            $.cookie(key, value, {expires: expires});
        }
    },

    remove: function (key) {
        if (this.hasLocalStorage()) {
            localStorage.removeItem(key);
        }
        else {
            $.cookie(key, null, {expires: -1});
        }
    }
};
(function($) {
    "use strict";

    $.fn.mfieldset = function (options_init) {
        // настройки по умолчанию
        var options = jQuery.extend({
            animation: false,
            speed: 'fast'
        }, options_init);

        return this.each(function (i) {
            var storage = window['storage'];
            var fieldset = $(this),
                legend = fieldset.children('legend'),
                indexVar = fieldset.parent().children('fieldset').index(this),
                cookieVar = storage.get('fieldset_' + indexVar);

            fieldset.attr('rel', indexVar);

            if ( ((cookieVar == '1' || i === 0) && !fieldset.hasClass('collapsed-force'))
                || fieldset.hasClass('expanded-force')
            ) {
                showFieldsetContent(fieldset, options);
            } else
                hideFieldsetContent(fieldset, {animation: false});

            $(legend).on('click', function () {
                if (fieldset.hasClass('expanded')) {
                    storage.remove('fieldset_' + indexVar);
                    hideFieldsetContent(fieldset, options);
                    fieldset.removeClass('expanded').addClass('collapsed');
                } else {
                    storage.set('fieldset_' + indexVar, 1, {expires: 365});
                    showFieldsetContent(fieldset, options);
                    fieldset.removeClass('collapsed').addClass('expanded');
                }
            });

            function hideFieldsetContent(obj, options) {
                if (options.animation == true)
                    obj.find(":eq(1)").slideUp(options.speed);
                else
                    obj.find(":eq(1)").hide();

                obj.removeClass("expanded").addClass("collapsed");
            }

            function showFieldsetContent(obj, options) {
                if (options.animation == true)
                    obj.find(":eq(1)").slideDown(options.speed);
                else
                    obj.find(":eq(1)").show();

                obj.removeClass("collapsed").addClass("expanded");
            }
        });
    };

})(jQuery);

(function($) {
    "use strict";

    $.mnotify = function(textOptions, options) {
        var stackContainer, messageWrap, messageBox, messageBody, messageTextBox, closeButton, messagePicture, image, messageHeader;

        textOptions = $.extend({
            title: undefined,
            message: undefined,
            image: undefined
        }, textOptions);

        options = $.extend({
            lifetime: 3000,
            click: undefined
        }, options);

        stackContainer = $('#notifier-box');
        if (!stackContainer.length) {
            stackContainer = $('<div>', {id: 'notifier-box'}).prependTo(document.body);
        }

        messageWrap = $('<div>').addClass('message-wrap').css('display', 'none');
        messageBox = $('<div>').addClass('message-box');

        messageHeader = $('<div>', {
            text: textOptions.title
        }).addClass('message-header');

        messageBody = $('<div>').addClass('message-body');

        messageTextBox = $('<span>');
        messageTextBox.append(textOptions.message);

        closeButton = $('<a>', {
            href: '#',
            title: 'Close notify',
            click: function(event) {
                $(this).parent().parent().fadeOut(300, function() {
                    $(this).remove();
                });
                event.preventDefault();
                return false;
            }
        }).addClass('message-close');

        if (textOptions.image != undefined) {
            messagePicture = $('<div>').addClass('thumb');
            image = $('<img>', {
                src: textOptions.image
            });
        }

        messageWrap.appendTo(stackContainer).fadeIn();
        messageBox.appendTo(messageWrap);
        closeButton.appendTo(messageBox);
        messageHeader.appendTo(messageBox);
        messageBody.appendTo(messageBox);

        if (messagePicture != undefined) {
            messagePicture.appendTo(messageBody);
            image.appendTo(messagePicture);
        }
        messageTextBox.appendTo(messageBody);

        if (options.lifetime > 0) {
            setTimeout(function() {
                $(messageWrap).fadeOut(300, function() {
                    $(this).remove();
                });
            }, options.lifetime);
        }

        if (options.click != undefined) {
            messageWrap.click(function(e) {
                if (!jQuery(e.target).is('.message-close')) {
                    options.click.call(this);
                }
            });
        }

        return this;
    }
})(jQuery);

(function($) {
    var Tooltip = function (selector, options) {
        this.init(selector, options);
    };

    Tooltip.prototype = {
        options: {},
        _tooltip: undefined,
        init: function (selector, options) {
            this.options = options || {};
            this._tooltip = $('<div class="mtooltip"></div>');

            var self = this;
            $(document).on('mouseenter', selector, function () {
                self._mouseEnterHandler.call(self, this);
            });
        },
        initTooltip: function ($target, $tooltip) {
            var posLeft, posTop, offset = $target.offset(),
                width = $target.attr('data-tooltip-width');

            $tooltip.addClass($target.attr('data-tooltip-cls'));

            if (width) {
                $tooltip.css('width', width);
            } else {
                $tooltip.css('width', $tooltip.width() / 2);
            }

            posLeft = offset.left + ($target.outerWidth() / 2) - ($tooltip.outerWidth() / 2);
            posTop = offset.top - $tooltip.outerHeight() - 25;

            if (posLeft < 0) {
                posLeft = offset.left + target.outerWidth() / 2 - 20;
                $tooltip.addClass('left');
            } else {
                $tooltip.removeClass('left');
            }

            if (posLeft + $tooltip.outerWidth() > $(window).width()) {
                posLeft = offset.left - $tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                $tooltip.addClass('right');
            } else {
                $tooltip.removeClass('right');
            }

            var isTop = posTop < 0;
            if (posTop < 0) {
                posTop = offset.top + target.outerHeight();
                $tooltip.addClass('top');
            } else {
                $tooltip.removeClass('top');
            }

            if (isTop) {
                $tooltip.css({
                    left: posLeft,
                    top: posTop + 30
                }).animate({
                    top: '-=10', opacity: 1
                }, 50);
            } else {
                $tooltip.css({
                    left: posLeft,
                    top: posTop
                }).animate({
                    top: '+=10',
                    opacity: 1
                }, 50);
            }
        },
        _mouseEnterHandler: function (element) {
            var $this = $(element),
                tip = $this.attr('title'),
                tooltip = $('<div class="tooltip"></div>');

            if (!tip || tip == '') {
                return false;
            }

            $this.removeAttr('title');
            tooltip.css('opacity', 0).html(tip).appendTo('body');

            this.initTooltip($this, tooltip);

            var self = this;
            $(window).on('resize', function () {
                self.initTooltip.call(self, $this, tooltip);
            });
            $this.bind('mouseleave', function () {
                self.removeTooltip.call(self, tooltip, $this, tip);
            });
            tooltip.bind('click', function () {
                self.removeTooltip.call(self, tooltip, $this, tip);
            });
        },
        removeTooltip: function (tooltip, $target, tip) {
            tooltip.animate({top: '-=10', opacity: 0}, 50, function () {
                $(this).remove();
            });

            $target.attr('title', tip);
        }
    };

    $.extend({
        mtooltip: function (selector, options) {
            return new Tooltip(selector, options)
        }
    });
})(jQuery);
$(function () {
    $(document).on('click', 'a[data-prevention]', function (e) {
        e.preventDefault();
        var $this = $(this);
        var data = $this.data();
        var url = $this.attr('href');
        var type = data.type ? data.type : 'post';
        var trigger = data.trigger ? data.trigger : null;
        var text = data.text ? data.text : null;
        var title = data.title ? data.title : null;

        $.confirm({
            title: title,
            text: text,
            confirm: function () {
                $.ajax({
                    url: url,
                    type: type,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success && trigger) {
                            $(document).trigger(trigger, [$this, data]);
                        }
                        $('.modal-closer').click();
                    }
                });
            }
        });
        return false;
    });
});

jQuery.fn.tablePositions = function (options) {
    "use strict";
    var option = jQuery.extend({
        draggableSelector: 'a',
        hoveredSelector: 'table',
        dropSelector: 'td',
        onMove: null
    }, options);

    var globals = [];

    function move(elem, to) {
        setTimeout(function () {
            $(to).removeClass('drag-over');
            $(to).append($(elem).detach());
            // $(to).append(elem);

            show(elem);
        }.bind(to, elem), 800);
    }

    function show(elem) {
        setTimeout(function () {
            $(elem).removeClass('move-event');
        }.bind(elem), 20);
    }


    return this.each(function (i) {
        var $container = $(this);

        globals[i] = {
            original: null,
            table: this,
            stop: true
        };

        // $container.find(option.hoveredSelector).on('dragover', function (e) {
        //     // e.originalEvent.dataTransfer.animation = 'move';
        //     $(this).closest(option.hoveredSelector).addClass('hovered');
        //     return (e.target.innerHTML.trim() != '');
        // });

        $container.find(option.dropSelector)
            .on('dragover', function (e) {
                // e.originalEvent.dataTransfer.animation = 'move';
                // $(this).closest(option.hoveredSelector).addClass('hovered');
                return (e.target.innerHTML.trim() != '');
            })
            .on('dragenter', function (e) {
                var has = (e.target.innerHTML.trim() != '');
                if (!has) {
                    $(this).addClass('drag-over');
                }

                return has;
            })
            .on('dragleave', function (e) {
                $(this).removeClass('drag-over');
                // $(this).closest(option.hoveredSelector).removeClass('hovered');
            })

            // .on('drag', function(e) {
            //     if (e.stopPropagation) {
            //         e.stopPropagation();
            //     }
            //     // $(this).closest(option.hoveredSelector).addClass('hovered');

            // })

            .on('drop', function (e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                $(this).removeClass('drag-over');
                // $(this).closest(option.hoveredSelector).removeClass('hovered');

                if (this != globals[i].original) {
                    var o_el = globals[i].original;
                    $(o_el).addClass('move-event');

                    if (typeof option.onMove == 'function') {
                        var to = this;
                        // var parent = $(o_el).parent(option.dropSelector);
                        // var t_el = o_el.detach();
                        // $.when(option.onMove(o_el, this))

                        $.when(option.onMove(o_el, to)).done(function(arg){
                            if (arg || arg == 'undefined') {
                                move(o_el, to);
                            }
                        }).fail(function(){
                            show(o_el);
                        });
                    }
                    else {
                        move(o_el, this);
                    }
                }

                return false;
            })
        ;

        $container.find(option.dropSelector + ' > ' + option.draggableSelector)
            .on('dragstart', function (e) {
                globals[i].original = this;

                $(globals[i].table).addClass('drag');
            })

            .on('dragend', function (e) {
                $(globals[i].table).removeClass('drag');
                globals[i].stop = true;
            })
        ;
    });
};
( function () {
    function getLangCode() {
        const name = 'TranslatesFilterForm[name][]';
        const value = ( new RegExp( '[?&]' + encodeURIComponent( name ) + '=([^&]*)' ) ).exec( location.search );

        return value ? decodeURIComponent( value[ 1 ] ) : null;
    }

    $( document ).ready( function () {
        const lang_code = getLangCode();

        if ( lang_code === null ) {
            return;
        }

        const $downloadTranslationsButton = $( '.download-translations-button' );

        $downloadTranslationsButton.click( function () {
            $.ajax( {
                url: `/admin/translates/upload-translates?lang_code=${ lang_code }`,
                method: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                error() {
                    alert( 'Something went wrong' );
                }
            } );
        } );

        const $uploadTranslatesForm = $( '.upload-translations-form' );

        $uploadTranslatesForm.submit( function ( e ) {
            e.preventDefault();

            const data = new FormData();
            const $files = $uploadTranslatesForm.find( 'input[name="translates-list"]' )[ 0 ].files;

            $.each( $files, function ( i, file ) {
                data.append( 'file-' + i, file );
            } );

            $.ajax( {
                url: `/admin/translates/upload-translates?lang_code=${ lang_code }`,
                method: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success() {
                    document.location.reload();
                },
                error() {
                    alert( 'Something went wrong' );
                }
            } );
        } );
    } );
} )();

'use strict';

(function () {
    "use strict";

    $(document).ready(function () {
        var f = $('fieldset.collapsible');
        if (f.length) {
            f.mfieldset();
        }

        var _loop = function _loop(form) {
            var $form = $(form);
            if ($form.attr('method') && $form.attr('method').toString().toLowerCase() != 'post') {
                var action = $(form).attr('action');

                if (action.indexOf('?') > -1) {
                    action = action.substr(action.indexOf('?') + 1);
                    action = action.split('&');

                    action.map(function (p) {
                        var vars = p.split('=');
                        var el = document.createElement('input');
                        el.type = 'hidden';
                        el.name = vars[0];

                        if (vars.length > 1) {
                            el.value = decodeURI(vars[1]);
                        }

                        form.prepend(el);
                    });
                }
            }
        };

        for (var _iterator = $('form'), _isArray = Array.isArray(_iterator), _i = 0, _iterator = _isArray ? _iterator : _iterator[Symbol.iterator]();;) {
            var _ref;

            if (_isArray) {
                if (_i >= _iterator.length) break;
                _ref = _iterator[_i++];
            } else {
                _i = _iterator.next();
                if (_i.done) break;
                _ref = _i.value;
            }

            var form = _ref;

            _loop(form);
        }

        if ($('.admin #o_date').length) {
            $('.admin form .date_templates > span').on('click', function () {
                var $this = $(this);
                var $input = $('.admin #o_date');
                var date_value = '';
                var delimiter = ' - ';
                var locale = 'en-US';
                var date = new Date();
                var for_datepicker = [date, date];

                switch ($this.data('range')) {
                    case 'this_month':
                        {
                            var date2 = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                            date.setDate(1);
                            // date_value = date.toLocaleDateString(locale) + delimiter + date2.toLocaleDateString(locale);
                            date_value = 'first day of this month';
                            for_datepicker = [date, date2];
                            break;
                        }
                    case 'this_week':
                        {
                            var first = date.getDate() - date.getDay(); // First day is the day of the month - the day of the week
                            var last = first + 6; // last day is the first day + 6
                            var date1 = new Date(date.setDate(first));
                            var _date = new Date(date.setDate(last));
                            // date_value = date1.toLocaleDateString(locale) + delimiter + date2.toLocaleDateString(locale);
                            date_value = 'first day of this week';
                            for_datepicker = [date1, _date];
                            break;
                        }
                    case 'last_31':
                        {
                            var _date2 = new Date();
                            _date2.setDate(date.getDate() - 31);
                            // date_value = date2.toLocaleDateString(locale) + delimiter + date.toLocaleDateString(locale);
                            date_value = '-31 day';
                            for_datepicker = [_date2, date];
                            break;
                        }
                    case 'last_7':
                        {
                            var _date3 = new Date();
                            _date3.setDate(date.getDate() - 7);
                            // date_value = date2.toLocaleDateString(locale) + delimiter + date.toLocaleDateString(locale);
                            date_value = '-7 day';
                            for_datepicker = [_date3, date];
                            break;
                        }
                    case 'clear':
                        {
                            for_datepicker = [];
                            break;
                        }
                    default:
                        {
                            // date_value = date.toLocaleDateString(locale);
                            date_value = 'now';
                            for_datepicker = [date, date];
                        }
                }
                if (typeof $input.airdate === "function") {
                    if (for_datepicker.length === 2) {
                        $input.airdate().data('airdate').selectDate(for_datepicker);
                    } else {
                        $input.airdate().data('airdate').clear();
                    }
                }

                $input.val(date_value);
            });
        }

        $('a.mmodal').on('click', function (e) {
            $(this).mmodal();
            e.preventDefault();
        });

        /*$('.tabs .tabs-title a').on('click', function (e) {
            e.preventDefault();
             $('.tabs .tabs-title a').removeClass('active');
            $('.tabs .tabs-content .tab').removeClass('active');
             let id = $(this).addClass('active').attr('href');
            $(id).addClass('active');
        });*/

        $('.main-block ').on('change', '.viewer', function () {
            var view = this.value;
            $('.dashboard-item .filter_owner').each(function () {
                switch (view) {
                    case '0':
                        $(this).addClass('hide');
                        break;
                    case '1':
                        $(this).removeClass('hide');
                        break;
                    case '2':
                        $(this).addClass('hide');
                        break;
                }
            });

            if ($(this[this.selectedIndex]).attr('data-loc')) {
                document.location = $(this[this.selectedIndex]).attr('data-loc');
            }
        });

        var $form_bb = $('.smarty-admin-block .buttons-block:not(.fixed)');
        if ($form_bb.length) {
            var $form = $form_bb.closest('form');

            if ($form.length && $form.innerHeight() + $form.offset()['top'] > $(window).height()) {
                $form_bb.addClass('fixed');
            }
        }
    });
})();
(function ($) {

    "use strict";

    /**
     * Описание объекта
     */
    var fileapi = function () {
        return fileapi.init.apply(this, arguments);
    };

    /**
     * Расширение объекта
     */
    $.extend(fileapi, {
        /**
         * Настройки по умолчанию
         */
        options: {
            field: null,
            option: 500,
            startPath: undefined,
            listUrl: undefined,
            uploadUrl: undefined,
            filemanSelector: '.file-manager',
            manageSelector: '.manage',
            messagesSelector: '.messages',
            messagesTimeout: 10000,
            csrfName: undefined,
            csrf: undefined,
            deletePrevention: 'Do you really want to delete the file?'
        },
        /**
         * Элемент, над которым выполняются действия
         */
        element: undefined,
        $element: undefined,
        currentPath: undefined,
        manage: {},
        /**
         * Инициализация
         * @param element
         * @param options
         */
        init: function (element, options) {
            if (element === undefined) return;

            this.element = element;
            this.$element = $(element);
            this.options = $.extend(this.options, options);

            this.currentPath = this.options.startPath;

            this.initManagement();
            this.initUploader();
            this.bind();

            return this;
        },
        initManagement: function () {
            var me = this;

            me.manage['manage'] = $(this.options.manageSelector);
            me.manage['create_folder'] = {
                'button': me.manage.manage.find('.create-folder-button'),
                'input': me.manage.manage.find('.create-folder-input')
            };
        },
        initUploader: function () {
            var me = this;

            var query = {
                'path': this.currentPath
            };
            query[this.options.csrfName] = this.options.csrf;

            var flow = new Flow({
                target: this.options.uploadUrl,
                testChunks: false,
                query: query,
                allowDuplicateUploads: true
            });

            flow.assignBrowse(document.getElementById('select'));
            flow.assignDrop(document.getElementById('zone'));

            flow.on('fileAdded', function(file, event){});

            flow.on('filesSubmitted', function(){
                flow.opts['query']['path'] = me.currentPath;
                flow.upload();
            });

            flow.on('uploadStart', function(){
                $('#progress_bar').css({
                    'width': 0
                });
                me.setUploading();
            });

            flow.on('progress', function(){
                var width = flow.progress() * 100 + '%';
                $('#progress_bar').css({
                    'width': width
                });
            });

            flow.on('complete', function(){
                $('#progress_bar').css({
                    'width': 0
                });
                me.unsetUploading();
                me.updateList();
            });
        },
        /**
         * "Навешиваем" события
         */
        bind: function () {
            var me = this;

            this.$element.on('click', '.files .file-check', function (e) {
                if (!$(e.target).is('input')){
                    e.preventDefault();
                    $(this).find('input').trigger('click');
                    return false;
                }
            });

            //this.$element.on('click', '.files .file-name', function (e) {
            //    e.preventDefault();
            //    $(this).find('a').click();
            //    return false;
            //});

            this.$element.on('click', '.files .file .file-link', function (e) {
                e.preventDefault();
                me.openFile($(this).data('url'));
                return false;
            });

            this.$element.on('click', '.files .dir .file-link', function (e) {
                e.preventDefault();
                me.openFolder($(this).data('path'));
                return false;
            });

            this.$element.on('click', '.files .delete-link', function (e) {
                e.preventDefault();
                if (confirm(me.options.deletePrevention)) {
                    me.deleteFile($(this).data('path'));
                }
                return false;
            });

            this.$element.on('click', '.create-folder-button', function (e) {
                e.preventDefault();
                me.createFolder(me.manage.create_folder.input.val());
                return false;
            });

            this.$element.on('click', '.remove-selected', function(e) {
                e.preventDefault();
                if (confirm(me.options.deletePrevention)) {
                    me.deleteAll();
                }
                return false;
            });

            // var collection = $();
            // $(document).on('dragenter', function (e) {
            //     me.showDropInfo();
            //     collection = collection.add(e.target);
            // }).on('dragleave',function (e) {
            //     collection = collection.not(e.target);
            //     if (!collection.length) {
            //         me.hideDropInfo();
            //     }
            // }).on('drop', function () {
            //     collection = $();
            //     me.hideDropInfo();
            // });
        },
        showDropInfo: function() {
            var fileman = this.$element;
            if (!fileman.hasClass('drop'))
                fileman.addClass('drop');
        },
        hideDropInfo: function() {
            this.$element.removeClass('drop');
        },
        setUploading: function() {
            this.$element.addClass('uploading');
        },
        unsetUploading: function() {
            this.$element.removeClass('uploading');
        },
        openFile: function (url) {
            var me = this;
            $('#' + this.options.field).val(url);
            $('.modal-closer').trigger('click');
        },
        openFolder: function (path) {
            var me = this;
            me.updateList(path);
        },
        createFolder: function (name) {
            var me = this;
            me.api('make', {'name': name}, function (data) {
                if (data.statement == 'success') {
                    me.manage.create_folder.input.val('');
                    me.updateList();
                }
            });
        },
        deleteAll: function() {
            var me = this;
            var files = [];
            $('input.delete-checker:checked').each(function(){
                files.push($(this).val());
            });
            me.api('deleteAll', {'files': files}, function (data) {
                $('input.delete-checker').removeAttr('checked');
                me.updateList();
            });
        },
        deleteFile: function (name) {
            var me = this;
            me.api('delete', {'name': name}, function (data) {
                if (data.statement == 'success') {
                    me.updateList();
                }
            });
        },
        api: function (action, sendData, callback) {
            var me = this;
            sendData = sendData || {};
            if (!sendData.path) {
                sendData.path = me.currentPath
            }
            sendData['action'] = action;
            sendData[me.options.csrfName] = me.options.csrf;
            $.ajax({
                'type': 'post',
                'url': me.options.apiUrl,
                'data': sendData,
                'dataType': 'json',
                'success': function (data) {
                    if (data.statement && data.message) {
                        if (data.statement == 'error') {
                            me.error(data.message);
//                        }else{
//                            me.message(data.message);
                        }
                    }
                    if (callback) {
                        callback(data);
                    }
                }
            })
        },
        message: function (message, type) {
            var me = this;
            type = type || 'message';

            var $notification = $('<div/>').addClass('notification').addClass(type).html(message);
            var $messages = $(me.options.messagesSelector);
            $messages.append($notification);

            setTimeout(function () {
                $notification.remove();
            }, me.options.messagesTimeout)
        },
        error: function (message) {
            this.message(message, 'error');
        },
        updateList: function (path) {
            var me = this;
            path = path || me.currentPath;
            var sendData = {
                'path': path
            };
            $.ajax({
                'url': me.options.listUrl,
                'data': sendData,
                'dataType': 'html',
                'success': function (data) {
                    var wrapped_data = $('<div/>').append(data);
                    $(me.element).find('.files').replaceWith(wrapped_data.find('.files'));
                    me.currentPath = path;
                }
            });
        }
    });

    /**
     * Инициализация функции объекта для jQuery
     */
    return $.fn.fileapi = function (options) {
        return fileapi.init(this, options);
    };

})($);
