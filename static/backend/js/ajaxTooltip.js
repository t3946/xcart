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