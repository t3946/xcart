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
            cookieVar = $.cookie('fieldset_' + indexVar);

        fieldset.attr('rel', indexVar);

        if ( ((cookieVar == '1' || i === 0) && !fieldset.hasClass('collapsed-force'))
            || fieldset.hasClass('expanded-force')
        ) {
            showFieldsetContent(fieldset, options);
        } else
            hideFieldsetContent(fieldset, {animation: false});

        $(legend).on('click', function () {
            if (fieldset.hasClass('expanded')) {
                $.cookie('fieldset_' + indexVar, null, {expires: -1});
                hideFieldsetContent(fieldset, options);
                fieldset.removeClass('expanded').addClass('collapsed');
            } else {
                $.cookie('fieldset_' + indexVar, 1, {expires: 365});
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
