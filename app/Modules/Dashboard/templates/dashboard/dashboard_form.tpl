{extends 'base/admin.tpl'}

{block 'js-head'}
    <link href="/static/vendors/air-datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <script src="/static/vendors/air-datepicker/dist/js/datepicker.min.js" type="text/javascript"></script>
    <script src="/static/vendors/air-datepicker/dist/js/i18n/datepicker.en.js" type="text/javascript"></script>


    <link href="/static/vendors/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css">
    {*<link href="/static/vendors/select2/dist/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css">*}
    <script src="/static/vendors/select2/dist/js/select2.min.js" type="text/javascript"></script>
{/block}

{block 'menu_block'}
{/block}

{block 'js'}
    <script type="text/javascript">
        (function(){
            console.log(new Date());
            console.log('jQuery', $.fn.jquery);
            {ignore}
            $('.admin select[data-ajax-from]').on("select2:select",  function(e) {
                $(this).append($('option[selected]', {value: e.params.data.id, text: e.params.data.text}));
            });
            {/ignore}

            $('.admin select:not([data-ajax-from])').not('.page-size select, .not-select2').select2({
                allowClear: true,
                closeOnSelect: false,
                placeholder: 'Select options'
            });

            $('.admin select[data-ajax-from]').select2({
                allowClear: true,
                placeholder: 'Start typing for hint',
                tags: true,
                closeOnSelect: false,
                minimumInputLength: 3,
                createTag : function (params) {
                    if (!this.$element.data('combobox')) {
                        return null;
                    }

                    var term = $.trim(params.term);

                    if (term === '') {
                        return null;
                    }

                    return {
                        id: '{$manual_string}' + term,
                        text: '{raw $manual_string}' + term
                    }
                },
                ajax: {
                    cache: true,
                    dataType: 'json',
                    delay: 500,
                    url : function(params)
                    {
                        var combobox = 0;
                        if ($(this).data('combobox')) {
                            combobox = 1;
                        }
                        return '{url 'dashboard:search_suggestion'}' + '&from=' + $(this).data('ajax-from') + '&combobox=' + combobox;
                    },
                    processResults: function (data) {
                        if (data) {
                            return {
                                results: data
                            };
                        }
                        {ignore}
                        return {results:{}};
                        {/ignore}
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
        })();
    </script>
{/block}