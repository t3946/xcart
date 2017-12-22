'use strict';

(function () {
    "use strict";

    $(document).ready(function () {
        $('fieldset.collapsible').mfieldset();

        var _loop = function _loop(form) {
            if ($(form).attr('method').toString().toLowerCase() != 'post') {
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

        $('.tabs .tabs-title a').on('click', function (e) {
            e.preventDefault();

            $('.tabs .tabs-title a').removeClass('active');
            $('.tabs .tabs-content .tab').removeClass('active');

            var id = $(this).addClass('active').attr('href');
            $(id).addClass('active');
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