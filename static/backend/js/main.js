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

            show(elem);
        }.bind(to, elem), 800);
    }

    function show(elem) {
        setTimeout(function () {
            $(elem).removeClass('move-event');
        }.bind(elem), 50);
    }
    // function scroll(index, step) {
    //     var scrollY = $(window).scrollTop();
    //     $(window).scrollTop(scrollY + step);
    //     if (!globals[index].stop) {
    //         setTimeout(function () { scroll(index, step) }, 20);
    //     }
    // }


    return this.each(function (i) {
        var $container = $(this);

        globals[i] = {
            original: null,
            table: this,
            stop: true
        };

        $container.find(option.hoveredSelector).on('dragover', function (e) {
            // e.originalEvent.dataTransfer.animation = 'move';
            $(this).closest(option.hoveredSelector).addClass('hovered');
            return (e.target.innerHTML.trim() != '');
        });

        $container.find(option.dropSelector)
            .on('dragover', function (e) {
                // e.originalEvent.dataTransfer.animation = 'move';
                $(this).closest(option.hoveredSelector).addClass('hovered');
                return (e.target.innerHTML.trim() != '');
            })
            .on('dragenter', function (e) {
                console.log(e.target);
                var has = (e.target.innerHTML.trim() != '');
                if (!has) {
                    $(this).addClass('drag-over');
                }

                return has;
            })
            .on('dragleave', function (e) {
                $(this).removeClass('drag-over');
                $(this).closest(option.hoveredSelector).removeClass('hovered');
            })

            .on('drag', function(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                globals[i].stop = true;
                $(this).closest(option.hoveredSelector).addClass('hovered');

                //
                // if (e.originalEvent.pageY > ($(window).height() / 2 - 150)) {
                //     console.log('up');
                //     globals[i].stop = false;
                //     scroll(i, -1)
                // }
                //
                // if (e.originalEvent.pageY < ($(window).height() / 2 + 150)) {
                //     console.log('down');
                //     globals[i].stop = false;
                //     scroll(i, 1)
                // }
            })

            .on('drop', function (e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                $(this).removeClass('drag-over');
                $(this).closest(option.hoveredSelector).removeClass('hovered');

                if (this != globals[i].original) {
                    var o_el = globals[i].original;
                    $(o_el).addClass('move-event');

                    if (typeof option.onMove == 'function') {
                        if (option.onMove(o_el, this)) {
                            move(o_el, this);
                        }
                        else {
                            show(o_el);
                        }
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
    // "use strict";

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

(function(){
    "use strict";

    $(document).ready(function(){
        $('fieldset').mfieldset();
        $('.admin .admin-dashboard-filters-list').tablePositions({
            draggableSelector:'.button',
            dropSelector:'.container',

            onMove:function (el, to)  {
                console.log(el);
                console.log(to);
            return true;
        }});

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

    });
})();
