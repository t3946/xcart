{extends 'base/admin.tpl'}

{block 'content'}
    <h1 align="center">Order search</h1>
    <form action="{url 'dashboard:search'}" method="GET">
        <fieldset class="expanded" rel="1">
            <legend>General</legend>

            <ul class="ul-main">
                <li>
                    <div class="label">
                        <label for="o_date">Order date:</label>
                    </div>

                    <div class="input">
                        <input type="text" name="search[order][date]" id="o_date"
                               value="{default_search_date}"
                               data-range="true"
                               data-multiple-dates-separator=" - "
                               data-language="en"
                               data-clear-button="1"
                               class="datepicker-here big">

                        <div class="templates as_a date_templates">
                            <span data-range="this_month">This month</span>
                            <span data-range="this_week">This week</span>
                            <span data-range="today">Today</span>
                            <span data-range="last_31">Last 31 days</span>
                            <span data-range="last_7">Last 7 days</span>
                            <span data-range="clear">[ Clear ]</span>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_total">Order total:</label>
                    </div>

                    <div class="input">
                        <input type="text" name="search[order][total][from]" id="o_total">
                        <span>to</span>
                        <input type="text" name="search[order][total][to]">
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="c_name">Customer name:</label>
                    </div>

                    <div class="input">
                        <select name="search[customer][name]" id="c_name" class="big" multiple data-ajax-from="search_customer_name" data-combobox="1"></select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="c_phone">Customer phone/fax:</label>
                    </div>

                    <div class="input">
                        <select name="search[customer][phone][]" id="c_phone" class="big" multiple data-ajax-from="search_phone" data-combobox="1"></select>
                    </div
                    >
                </li>

                <li>
                    <div class="label">
                        <label for="c_email">Customer email:</label>
                    </div>

                    <div class="input">
                        <select name="search[customer][email][]" id="c_email" class="big" multiple data-ajax-from="search_email" data-combobox="1"></select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="c_zip">Zip/Postal code:</label>
                    </div>

                    <div class="input">
                        <input type="text" name="search[customer][zip_code]" id="c_zip" class="big" multiple data-ajax-from="search_zip" data-combobox="1">
                    </div>
                </li>
            </ul>

        </fieldset>

        <fieldset class="expanded collapsed"  rel="2">
            <legend>
                Advanced
            </legend>
            <ul class="ul-main">

                <li>
                    <div class="label">
                        <label for="o_id">Order ID:</label>
                    </div>

                    <div class="input">
                        <input type="text" name="search[order][id][from]" id="o_id"/>
                        <span>to</span>
                        <input type="text" name="search[order][id][to]"/>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_operator">Operator:</label>
                    </div>

                    <div class="input">
                        <select type="text" name="search[order][operator][]" id="o_operator" class="big" data-ajax-from="operator" multiple></select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_features">Order features:</label>
                    </div>

                    <div class="input">
                        <select name="search[features][]" id="o_features" class="big" multiple>
                            {foreach $features as $code => $name}
                                <option value="{$code}">{$name}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_features">Order sales channel:</label>
                    </div>

                    <div class="input">
                        <select type="text" name="search[order][source][]" id="o_features" class="big" multiple>
                            {foreach $sources as $code => $name}
                                <option value="{$code}">{$name}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_payment">Order payment method:</label>
                    </div>

                    <div class="input">
                        <select name="search[order][payment_method][]" id="o_payment" class="big" multiple>
                            {foreach $payment_methods as $method}
                                <option value="{$method.paymentid}" title="{$method.payment_details}">{$method.payment_method}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_delivery">Order delivery method:</label>
                    </div>

                    <div class="input">
                        <select name="search[order][delivery_method][]" id="o_delivery" class="big" multiple>
                            {foreach $shipping_methods as $method}
                                <option value="{$method.shippingid}">
                                    {if $method.code}[{$method.code}]{/if}
                                    {$method.shipping}
                                    {if $method.frontend_name}({$method.frontend_name}){/if}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_c2b">C2B payment status:</label>
                    </div>

                    <div class="input">
                        <select type="text" name="search[order][c2b_status][]" id="o_c2b" class="big" multiple>
                            {foreach $order_statuses.CB as $status}
                                <option value="{$status.code}">{$status.name}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_d2c">D2C payment status:</label>
                    </div>

                    <div class="input">
                        <select type="text" name="search[order][d2c_status][]" id="o_d2c" class="big" multiple>
                            {foreach $order_statuses.DC as $status}
                                <option value="{$status.code}">{$status.name}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_transit">Check transit status:</label>
                    </div>

                    <div class="input">
                        <select type="text" name="search[order][po_transit_status][]" id="o_transit" class="big" multiple>
                            {foreach $order_statuses.PO as $status}
                                <option value="{$status.code}">{$status.name}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>


                <li>
                    <div class="label">
                        <label for="o_po">PO status:</label>
                    </div>

                    <div class="input">
                        <select type="text" name="search[order][po_status][]" id="o_po" class="big" multiple>
                            {foreach $po_statuses as $code => $name}
                                <option value="{$code}">{$name}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_fraud">Fraud check status:</label>
                    </div>

                    <div class="input">
                        <select name="search[order][fraud_status][]" id="o_fraud" class="big" multiple>
                            {foreach $fraud_statuses as $status}
                                <option value="{$status.code}">{$status.name}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="p_vs">Verification status:</label>
                    </div>

                    <div class="input">
                        <select name="search[order][vn_status][]" id="p_vs" class="big" multiple>
                            {foreach $order_statuses.PV as $status}
                                <option value="{$status.code}">{$status.name}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_tag">Attention tag:</label>
                    </div>

                    <div class="input">
                        <select name="search[order][tag][]" id="o_tag" class="big" multiple>
                            {foreach $attention_tags as $tag}
                                <option value="{$tag.status_id}" title="{$tag.description}">{$tag.status}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="o_distributor">Distributors:</label>
                    </div>

                    <div class="input">
                        <select name="search[order][distributor][]" id="o_distributor" class="big" multiple data-ajax-from="distributor"></select>
                    </div>
                </li>
            </ul>
        </fieldset>

        <fieldset class="expanded collapsed" rel="3">
            <legend>
                Advanced - Product in order
            </legend>
            <ul class="ul-main">
                <li>
                    <div class="label">
                        <label for="p_name">Product name:</label>
                    </div>

                    <div class="input">
                        <input type="text" name="search[product][name]" id="p_name" class="big">
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="p_sku">SKU:</label>
                    </div>

                    <div class="input">
                        <input type="text" name="search[product][sku]" id="p_sku" class="big">
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="p_id">Product ID:</label>
                    </div>

                    <div class="input">
                        <input type="text" name="search[product][id]" id="p_id" class="big">
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="p_qs">Question status:</label>
                    </div>

                    <div class="input">
                        <select name="search[product][question_status][]" id="p_qs" class="big" multiple>
                            {foreach $question_statuses as $code => $status}
                                <option value="{$code}">{$status}</option>
                            {/foreach}
                        </select>
                    </div>
                </li>
            </ul>
        </fieldset>

        <fieldset class="expanded collapsed" rel="4">
            <legend>
                Advanced - Customer
            </legend>
            <ul class="ul-main">
                <li>
                    <div class="label">
                        <label for="c_company">Search in address:</label>
                    </div>

                    <div class="input">
                        <input type="radio" name="search[customer][in_address]" id="c_in_address_both" value="both" checked>
                        <label for="c_in_address_both">Both</label>

                        <input type="radio" name="search[customer][in_address]" id="c_in_address_billig" value="billing">
                        <label for="c_in_address_billig">Billing</label>

                        <input type="radio" name="search[customer][in_address]" id="c_in_address_shipping" value="shipping">
                        <label for="c_in_address_shipping">Shipping</label>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="c_company">Company:</label>
                    </div>

                    <div class="input">
                        <select name="search[customer][company][]" id="c_company" class="big" multiple data-ajax-from="company"></select>
                    </div>
                </li>


                <li>
                    <div class="label">
                        <label for="c_city">City:</label>
                    </div>

                    <div class="input">
                        <select name="search[customer][city][]" id="c_city" class="big" multiple data-ajax-from="search_city"></select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="c_state">State/Province:</label>
                    </div>

                    <div class="input">
                        <select name="search[customer][state][]" id="c_state" class="big" multiple data-ajax-from="search_state"></select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="c_country">Country:</label>
                    </div>

                    <div class="input">
                        <select name="search[customer][state][]" id="c_country" class="big" multiple data-ajax-from="search_country"></select>
                    </div>
                </li>

                <li>
                    <div class="label">
                        <label for="c_street">Street/Home:</label>
                    </div>

                    <div class="input">
                        <select name="search[customer][address][]" id="c_street" class="big" multiple data-ajax-from="search_street" data-combobox="1"></select>
                    </div>
                </li>
            </ul>
        </fieldset>

        <button>Search</button>
        <a href="{url 'dashboard:search'}">Reset</a>

    </form>
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
                        text: '-> ' + term
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

            $('.admin select:not([data-ajax-from])').select2({
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