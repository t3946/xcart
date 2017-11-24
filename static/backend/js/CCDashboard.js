
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
                    count = parseInt($this.attr('data-count'));

                if (data.filters[id]) {
                    var data_filter = data.filters[id];

                    if (data.filters[id]['count']['orders'] == count) {
                        $this.find('.count').html(data.filters[id]['count']['orders']);
                    }
                    else {
                        var count_events ='',
                            sign = '',
                            c_chng = data_filter['count']['orders'] - count;

                        if (c_chng > 0) {
                            sign = '+';
                            notify = true;
                        }

                        if (data.filters[id]['count']['events']) {
                            count_events = '+' + data.filters[id]['count']['events'];
                        }

                        if (data.filters[id]['count']['priority']) {
                            $this.find('.priority').removeClass('empty');
                            $this.find('.priority').html(data.filters[id]['count']['priority']);
                        }
                        else {
                            $this.find('.priority').addClass('empty');
                            $this.find('.priority').html('');
                        }

                        $this.attr('data-count', data_filter['count']['orders']);
                        $this.find('.count').html(count + ' ' + sign + c_chng);
                        $this.find('.events').html(count_events);

                        if (data.filters[id]['count']['orders'] > 0 && $this.hasClass(self.options.classes.disabled)) {
                            $this.removeClass(self.options.classes.disabled);
                            $this.addClass(self.options.classes.enabled);
                        }
                        else if (data.filters[id]['count']['orders'] == 0 && $this.hasClass(self.options.classes.enabled)) {
                            $this.removeClass(self.options.classes.enabled);
                            $this.addClass(self.options.classes.disabled);
                        }

                        if (c_chng > 0) {
                            data.filters[id]['notify_text'] =  '<a target="_blank" href="'+ $this.attr('href') +'">'+ $this.find('.name_events').html() +'</a>';
                        }
                    }
                }
            });

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