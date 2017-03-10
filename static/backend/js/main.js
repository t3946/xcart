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
    var Tooltip = function (selector, options) {
        this.init(selector, options);
    };

    Tooltip.prototype = {
        options: {},
        _tooltip: undefined,
        init: function (selector, options) {
            this.options = options || {};
            this._tooltip = $('<div class="tooltip"></div>');

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


(function($) {
    'use strict';
    var AjaxTooltip = function (element, options) {
        return this.init(element, options);
    };

    AjaxTooltip.prototype = {
        options: {
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
            container: 'tooltip'
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
                if (e.which == 3 && ( !self._element || !$(e.target).closest(self._element).length)) {
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
                url: $this.data('action'),
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

jQuery.fn.mfieldset = function (options) {
    "use strict";

    // настройки по умолчанию
    var options = jQuery.extend({
        animation: false,
        speed: 'fast'
    }, options);
    return this.each(function (i) {

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
            interval: 25000,
            selector: '.dashboard-filters a[data-id]'
        },

        __stop: false,

        init: function(element, options) {
            this.options = $.extend(this.options, options);

            this.start();

        },
        processData:function(data) {
            var self = this;
            var texts= [];
            var notify = false;

            $(this.options.selector).each(function(){
                var $this = $(this),
                    id = $this.data('id'),
                    count = parseInt($this.attr('data-count'));

                if (data.filters[id]) {
                    var data_filter = data.filters[id];

                    if (data.filters[id]['count']['orders'] == count) {
                        $this.find('.count').html(data.filters[id]['count']['orders']);
                    }
                    else {
                        var sign = '';
                        var c_chng = data_filter['count']['orders'] - count;
                        if (c_chng > 0) {
                            sign = '+';
                            notify = true;
                        }

                        $this.attr('data-count', data_filter['count']['orders']);
                        $this.find('.count').html(count + ' ' + sign + c_chng);
                        $this.find('.events').html(data.filters[id]['count']['events'] || '');

                        if (data.filters[id].count > 0 && $this.hasClass(self.options.classes.disabled)) {
                            $this.removeClass(self.options.classes.disabled);
                            $this.addClass(self.options.classes.enabled);
                        }
                        else if (data.filters[id].count == 0 && $this.hasClass(self.options.classes.enabled)) {
                            $this.removeClass(self.options.classes.enabled);
                            $this.addClass(self.options.classes.disabled);
                        }

                        if (c_chng > 0) {
                            data.filters[id]['notify_text'] =  '<a target="_blank" href="'+ $this.attr('href') +'">'+ $this.find('.name_events').html() +')</a>';
                        }
                    }
                }
            });

            if (notify)
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

            $.ajax({
                dataType: this.options.ajax.dataType,
                type: this.options.ajax.type,
                url: this.options.ajax.url,
                cache: this.options.ajax.cache,

                success: function (data, textStatus, jqXHR) {

                    self.processData.call(self, data);
                },

                error: function (jqXHR, textStatus, errorThrown) {
                    $.mnotify({
                        title: self.options.notify.titles.err_refresh,
                        message: jqXHR.responseText
                    });

                    self.cycleRefresh();
                }
            });
        },

        cycleRefresh: function() {
            if (!this.__stop) {
                var self = this;

                setTimeout(function () {
                    $(document).trigger(self.options.triggers.refresh);
                }, this.options.interval);
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
            this.cycleRefresh();
        }
    };

    $.fn.dashboard = function(options) {
        return new Dashboard(this, options);
    }
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

(function(){
    "use strict";

    $(document).ready(function(){
        $('fieldset').mfieldset();

        $('form').each(function(i, form)
        {
            if ($(form).attr('method').toString().toLowerCase() != 'post')
            {
                var action = $(form).attr('action');

                if (action.indexOf('?') > -1)
                {
                    action = action.substr(action.indexOf('?')+1);
                    action = action.split('&');

                    action.map(function(p)
                    {
                        var vars = p.split('=');
                        var el = document.createElement('input');
                        el.type = 'hidden';
                        el.name = vars[0];
                        el.value = decodeURI(vars[1]);

                        form.appendChild(el);

                    }.bind(form));
                }
            }
        });


        $('.admin .date_templates > span').on('click', function(){
            var $this = $(this);
            var $input = $('.admin #o_date');
            var date_value = '';
            var delimiter = ' - ';
            var locale = 'en-US';
            var date = new Date();
            var for_datepicker = [date, date];

            switch ($this.data('range')) {
                case 'this_month': {
                    var date2 = new Date(date.getFullYear(), date.getMonth()+1, 0);
                    date.setDate(1);
                    date_value = date.toLocaleDateString(locale) + delimiter + date2.toLocaleDateString(locale);
                    for_datepicker = [date, date2];
                    break;
                }
                case 'this_week': {
                    var first = date.getDate() - date.getDay(); // First day is the day of the month - the day of the week
                    var last = first + 6; // last day is the first day + 6
                    var date1 = new Date(date.setDate(first));
                    var date2 = new Date(date.setDate(last));
                    date_value = date1.toLocaleDateString(locale) + delimiter + date2.toLocaleDateString(locale);
                    for_datepicker = [date1, date2];
                    break;
                }
                case 'last_31': {
                    var date2 = new Date();
                    date2.setDate(date.getDate() -31);
                    date_value = date2.toLocaleDateString(locale) + delimiter + date.toLocaleDateString(locale);
                    for_datepicker = [date2, date];
                    break;
                }
                case 'last_7': {
                    var date2 = new Date();
                    date2.setDate(date.getDate() -7);
                    date_value = date2.toLocaleDateString(locale) + delimiter + date.toLocaleDateString(locale);
                    for_datepicker = [date2, date];
                    break;
                }
                case 'clear': {
                    for_datepicker = [];
                    break;
                }
                default: {
                    date_value = date.toLocaleDateString(locale);
                    for_datepicker = [date, date];
                }
            }
            if (typeof $input.datepicker === "function") {
                if (for_datepicker.length == 2) {
                    $input.datepicker().data('datepicker').selectDate(for_datepicker);
                }
                else {
                    $input.datepicker().data('datepicker').clear();
                }
            }
            else {
                $input.val(date_value);
            }

        });

        $('.tabs .tabs-title a').on('click', function(e) {
            e.preventDefault();

            $('.tabs .tabs-title a').removeClass('active');
            $('.tabs .tabs-content .tab').removeClass('active');

            var id = $(this).addClass('active').attr('href');
            $(id).addClass('active');
        })

    });

})();
