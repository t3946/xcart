(function() {
    "use strict";

    $(document).ready(function() {
        $('fieldset.collapsible').mfieldset();

        for (let form of $('form'))
        {
            let $form = $(form);
            if ($form.attr('method') && $form.attr('method').toString().toLowerCase() != 'post') {
                let action = $(form).attr('action');

                if (action.indexOf('?') > -1) {
                    action = action.substr(action.indexOf('?') + 1);
                    action = action.split('&');

                    action.map(p => {
                        let vars = p.split('=');
                        let el = document.createElement('input');
                        el.type = 'hidden';
                        el.name = vars[0];

                        if (vars.length > 1) {
                            el.value = decodeURI(vars[1]);
                        }

                        form.prepend(el);
                    });
                }
            }
        }

        if ($('.admin #o_date').length) {
            $('.admin form .date_templates > span').on('click', function () {
                let $this = $(this);
                let $input = $('.admin #o_date');
                let date_value = '';
                let delimiter = ' - ';
                let locale = 'en-US';
                let date = new Date();
                let for_datepicker = [date, date];

                switch ($this.data('range')) {
                    case 'this_month': {
                        let date2 = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                        date.setDate(1);
                        // date_value = date.toLocaleDateString(locale) + delimiter + date2.toLocaleDateString(locale);
                        date_value = 'first day of this month';
                        for_datepicker = [date, date2];
                        break;
                    }
                    case 'this_week': {
                        let first = date.getDate() - date.getDay(); // First day is the day of the month - the day of the week
                        let last = first + 6; // last day is the first day + 6
                        let date1 = new Date(date.setDate(first));
                        let date2 = new Date(date.setDate(last));
                        // date_value = date1.toLocaleDateString(locale) + delimiter + date2.toLocaleDateString(locale);
                        date_value = 'first day of this week';
                        for_datepicker = [date1, date2];
                        break;
                    }
                    case 'last_31': {
                        let date2 = new Date();
                        date2.setDate(date.getDate() - 31);
                        // date_value = date2.toLocaleDateString(locale) + delimiter + date.toLocaleDateString(locale);
                        date_value = '-31 day';
                        for_datepicker = [date2, date];
                        break;
                    }
                    case 'last_7': {
                        let date2 = new Date();
                        date2.setDate(date.getDate() - 7);
                        // date_value = date2.toLocaleDateString(locale) + delimiter + date.toLocaleDateString(locale);
                        date_value = '-7 day';
                        for_datepicker = [date2, date];
                        break;
                    }
                    case 'clear': {
                        for_datepicker = [];
                        break;
                    }
                    default: {
                        // date_value = date.toLocaleDateString(locale);
                        date_value = 'now';
                        for_datepicker = [date, date];
                    }
                }
                if (typeof $input.airdate === "function") {
                    if (for_datepicker.length === 2) {
                        $input.airdate().data('airdate').selectDate(for_datepicker);
                    }
                    else {
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

        $('.tabs').tabs();

        $('.main-block ').on('change', '.viewer', function(){
            let view = this.value;
            $('.dashboard-item .filter_owner').each(function() {
                switch(view) {
                    case '0': $(this).addClass('hide');
                        break;
                    case '1': $(this).removeClass('hide');
                        break;
                    case '2': $(this).addClass('hide');
                        break;
                }
            });
        });


        let $form_bb = $('.smarty-admin-block .buttons-block:not(.fixed)');
        if ($form_bb.length) {
            let $form = $form_bb.closest('form');

            if ($form.length && ($form.innerHeight() + $form.offset()['top']) > $(window).height()) {
                $form_bb.addClass('fixed');
            }
        }

    });

})();
