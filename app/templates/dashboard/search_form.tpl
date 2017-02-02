{extends 'base/admin.tpl'}

{block 'content'}

    <div class="row">
        <div class="columns large-12">
            <h1 align="center">Order search</h1>


            <fieldset class="{if $form_collapse}collapsed-force collapsed{else}expanded{/if}">
                <legend>Order search form</legend>

                <form action="{url 'dashboard:search'}" method="GET">
                    {include 'dashboard/form_fields.tpl'}

                    <button>Search</button>
                    <button name="search[reset]" value="reset">Reset</button>
                </form>
            </fieldset>
        </div>
    </div>


    {if count($models) > 0}
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {include 'order/orders_list.tpl' orders=$models}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
    {/if}
{/block}

{block 'js'}
    <link href="/static/vendors/air-datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <script src="/static/vendors/air-datepicker/dist/js/datepicker.min.js"></script>
    <script src="/static/vendors/air-datepicker/dist/js/i18n/datepicker.en.js"></script>


    <link href="/static/vendors/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css">
    <script src="/static/vendors/select2/dist/js/select2.full.min.js"></script>

    <script type="text/javascript">
        (function(){
            $('.admin form').each(function(i, form)
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


            $('.admin select').on('select2:select select2:opening', function (e) {
                $(this).closest('form').off('keyup', '.select2-selection',  function (e) {
                    console.log(e);
                    if (e.keyCode === 13) {
                        $(this).closest('form').submit();
                    }
                });
            });

            $('.admin select[data-ajax-from]').on("select2:select",  function(e) {
                // append the new item to the default select
                {ignore}
                $(this).append($('<option>', {value: e.params.data.id, text: e.params.data.text}));
                {/ignore}
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

            $('.admin select:not([data-ajax-from])').not('.page-size select').select2({
                allowClear: true,
                placeholder: 'Select options'
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
        })()
    </script>
{/block}