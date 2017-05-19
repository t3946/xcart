
<fieldset class="{if $full_expanded}expanded-force{/if}" rel="1">
    <legend>General</legend>

    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_date">Order date:</label>
                </div>

                <div class="columns large-6">
                    <input type="text" name="search[order][date]" id="o_date"
                           value="{$form_data.order.date}"
                           data-range="true"
                           data-toggle-selected="false"
                           data-multiple-dates-separator=" - "
                           data-language="en"
                           data-clear-button="1"
                           class="datepicker-here big">

                    <a href="#help-dates" class="mmodal">
                        <i class="fa fa-question-circle pointer" title="Click me!"></i>
                    </a>

                    <div class="templates as_a date_templates">
                        <span data-range="this_month">This month</span>
                        <span data-range="this_week">This week</span>
                        <span data-range="today">Today</span>
                        <span data-range="last_31">Last 31 days</span>
                        <span data-range="last_7">Last 7 days</span>
                        <span data-range="clear">[ Clear ]</span>
                    </div>
                </div>
                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][date]" id="nod" {if $form_data.not.order.date}checked{/if}>
                    <label for="nod">Not selected</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_total">Order total:</label>
                </div>

                <div class="columns large-6">
                    <input type="text" name="search[order][total][from]" id="o_total" value="{$form_data.order.total.from}">
                    <span>to</span>
                    <input type="text" name="search[order][total][to]" value="{$form_data.order.total.to}">
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][total]" id="not" {if $form_data.not.order.total}checked{/if}>
                    <label for="not">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_name">Customer name:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[customer][name][]" id="c_name" class="big" multiple data-ajax-from="search_customer_name" data-combobox="1">
                        {foreach $form_data.customer.name as $value}
                            <option value="{raw $value}" selected>{raw $value}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][name]" id="ncn" {if $form_data.not.customer.name}checked{/if}>
                    <label for="ncn">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_phone">Customer phone/fax:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[customer][phone][]" id="c_phone" class="big" multiple data-ajax-from="search_phone" data-combobox="1">
                        {foreach $form_data.customer.phone as $value}
                            <option value="{raw $value}" selected>{raw $value}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][phone]" id="ncp" {if $form_data.not.customer.phone}checked{/if}>
                    <label for="ncp">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_email">Customer email:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[customer][email][]" id="c_email" class="big" multiple data-ajax-from="search_email" data-combobox="1">
                        {foreach $form_data.customer.email as $value}
                            <option value="{raw $value}" selected>{raw $value}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][email]" id="nce" {if $form_data.not.customer.email}checked{/if}>
                    <label for="nce">Not</label>
                </div>
            </div>
        </li>


        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_company">Search in address:</label>
                </div>

                <div class="columns large-6">
                    <input type="radio" name="search[customer][in_address]" id="c_in_address_both" value="" {if $form_data.customer.in_address == 'both' or !$form_data.customer.in_address}checked{/if}>
                    <label for="c_in_address_both">Both</label>

                    <input type="radio" name="search[customer][in_address]" id="c_in_address_billig" value="billing" {if $form_data.customer.in_address == 'billing'}checked{/if}>
                    <label for="c_in_address_billig">Billing</label>

                    <input type="radio" name="search[customer][in_address]" id="c_in_address_shipping" value="shipping" {if $form_data.customer.in_address == 'shipping'}checked{/if}>
                    <label for="c_in_address_shipping">Shipping</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_company">Company:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[customer][company][]" id="c_company" class="big" multiple data-ajax-from="company">
                        {foreach $form_data.customer.company as $value}
                            <option value="{raw $value}" selected>{raw $value}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][company]" id="ncc" {if $form_data.not.customer.company}checked{/if}>
                    <label for="ncc">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_street">Street/Home (address):</label>
                </div>

                <div class="columns large-6">
                    <select name="search[customer][address][]" id="c_street" class="big" multiple data-ajax-from="search_street" data-combobox="1">
                        {foreach $form_data.customer.address as $value}
                            <option value="{raw $value.id}" selected>{raw $value.text}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][address]" id="nca" {if $form_data.not.customer.address}checked{/if}>
                    <label for="nca">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_city">City:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[customer][city][]" id="c_city" class="big" multiple data-ajax-from="search_city">
                        {foreach $form_data.customer.city as $value}
                            <option value="{raw $value.id}" selected>{raw $value.text}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][city]" id="ncc2" {if $form_data.not.customer.city}checked{/if}>
                    <label for="ncc2">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_state">State/Province:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[customer][state][]" id="c_state" class="big" multiple data-ajax-from="search_state">
                        {foreach $form_data.customer.state as $value}
                            <option value="{raw $value.id}" selected>{raw $value.text}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][state]" id="ncs" {if $form_data.not.customer.state}checked{/if}>
                    <label for="ncs">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_country">Country:</label>
                </div>

                <div class="columns large-6">
                    {*<select name="search[customer][country][]" id="c_country" class="big" multiple data-ajax-from="search_country">*}
                    <select name="search[customer][country][]" id="c_country" class="big" multiple>
                        {foreach $countries as $country}
                            <option value="{raw $country.id}" {if $form_data.customer.country && $country.id in list $form_data.customer.country}selected{/if}>{raw $country.text}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][country]" id="ncc3" {if $form_data.not.customer.country}checked{/if}>
                    <label for="ncc3">Not</label>
                </div>
            </div>
        </li>


        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="c_zip">Zip/Postal code:</label>
                </div>

                <div class="columns large-6">
                    <input type="text" name="search[customer][zip_code]" id="c_zip" class="big" multiple data-ajax-from="search_zip" data-combobox="1" value="{$form_data.customer.zip_code}">
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][customer][zip_code]" id="ncz" {if $form_data.not.customer.zip_code}checked{/if}>
                    <label for="ncz">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_distributor">Distributors:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][distributor][]" id="o_distributor" class="big" multiple data-ajax-from="distributor">
                        {foreach $form_data.order.distributor as $value}
                            <option value="{raw $value.id}" selected>{raw $value.text}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][distributor]" id="nod" {if $form_data.not.order.distributor}checked{/if}>
                    <label for="nod">Not</label>
                </div>
            </div>
        </li>
    </ul>

</fieldset>


<fieldset class="{if $full_expanded}expanded-force{/if}" rel="3">
    <legend>
        Advanced - Product in order
    </legend>
    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="p_name">Product name:</label>
                </div>

                <div class="columns large-6">
                    <input type="text" name="search[product][name]" id="p_name" class="big" value="{$form_data.product.name}">
                </div>
                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][product][name]" id="npn" {if $form_data.not.product.name}checked{/if}>
                    <label for="npn">Not selected</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="p_sku">SKU:</label>
                </div>

                <div class="columns large-6">
                    <input type="text" name="search[product][sku]" id="p_sku" class="big" value="{$form_data.product.sku}">
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][product][sku]" id="nps" {if $form_data.not.product.sku}checked{/if}>
                    <label for="nps">Not selected</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="p_id">Product ID:</label>
                </div>

                <div class="columns large-6">
                    <input type="text" name="search[product][id]" id="p_id" class="big" value="{$form_data.product.id}">
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][product][id]" id="npi" {if $form_data.not.product.id}checked{/if}>
                    <label for="npi">Not selected</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="p_qs">Question status:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[product][question_status][]" id="p_qs" class="big" multiple>
                        {foreach $question_statuses as $code => $status}
                            <option value="{$code}" {if $form_data.product.question_status && $code in list $form_data.product.question_status}selected{/if}>
                                {$status}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][product][question_status]" id="npq" {if $form_data.not.product.question_status}checked{/if}>
                    <label for="npq">Not</label>
                </div>
            </div>
        </li>
    </ul>
</fieldset>

<fieldset class="{if $full_expanded}expanded-force{/if}"  rel="2">
    <legend>
        Advanced - Payment \ Shipping
    </legend>
    <ul class="ul-main">
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="og_has_payment_processor_all">Payment processor:</label>
                </div>

                <div class="columns large-6">
                    <input name="search[order][has_payment_processor]" type="radio" value="" id="og_has_payment_processor_all" {if !$form_data.order.has_payment_processor}checked{/if}>
                    <label for="og_has_payment_processor_all">All</label>
                    <input name="search[order][has_payment_processor]" type="radio" value="Y" id="og_has_payment_processor_y" {if $form_data.order.has_payment_processor == 'Y'}checked{/if}>
                    <label for="og_has_payment_processor_y">Not empty</label>
                    <input name="search[order][has_payment_processor]" type="radio" value="N" id="og_has_payment_processor_n" {if $form_data.order.has_payment_processor == 'N'}checked{/if}>
                    <label for="og_has_payment_processor_n">Empty</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_payment">Order payment processor:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][payment_processor][]" id="o_payment" class="big" multiple>
                        {foreach $payment_methods as $method}
                            <option value="{$method.paymentid}" title="{$method.payment_details}" {if $form_data.order.payment_processor && $method.paymentid in list $form_data.order.payment_processor}selected{/if}>
                                {$method.payment_method}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][payment_processor]" id="nopp" {if $form_data.not.order.payment_processor}checked{/if}>
                    <label for="nopp">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_payment">Order payment method:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][payment_method][]" id="o_payment" class="big" multiple>
                        {foreach $payment_methods as $method}
                            <option value="{$method.paymentid}" title="{$method.payment_details}" {if $form_data.order.payment_method && $method.paymentid in list $form_data.order.payment_method}selected{/if}>
                                {$method.payment_method}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][payment_method]" id="nopm" {if $form_data.not.order.payment_method}checked{/if}>
                    <label for="nopm">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_delivery">Order delivery method:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][delivery_method][]" id="o_delivery" class="big" multiple>
                        {foreach $shipping_methods as $method}
                            <option value="{$method.shippingid}" {if $form_data.order.delivery_method && $method.shippingid in list $form_data.order.delivery_method}selected{/if}>
                                {if $method.code}[{$method.code}]{/if}
                                {$method.shipping}
                                {if $method.frontend_name}({$method.frontend_name}){/if}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][delivery_method]" id="nodm" {if $form_data.not.order.delivery_method}checked{/if}>
                    <label for="nodm">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_c2b">C2B payment status:</label>
                </div>

                <div class="columns large-6">
                    <select type="text" name="search[order][c2b_status][]" id="o_c2b" class="big" multiple>
                        {foreach $order_statuses.CB as $status}
                            <option value="{$status.code}" {if $form_data.order.c2b_status && $status.code in list $form_data.order.c2b_status}selected{/if}>
                                {$status.name}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][c2b_status]" id="nocb" {if $form_data.not.order.c2b_status}checked{/if}>
                    <label for="nocb">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_d2c">D2C payment status:</label>
                </div>

                <div class="columns large-6">
                    <select type="text" name="search[order][d2c_status][]" id="o_d2c" class="big" multiple>
                        {foreach $order_statuses.DC as $status}
                            <option value="{$status.code}" {if $form_data.order.d2c_status && $status.code in list $form_data.order.d2c_status}selected{/if}>
                                {$status.name}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][d2c_status]" id="nodc" {if $form_data.not.order.d2c_status}checked{/if}>
                    <label for="nodc">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_transit">Check transit status:</label>
                </div>

                <div class="columns large-6">
                    <select type="text" name="search[order][po_transit_status][]" id="o_transit" class="big" multiple>
                        {foreach $order_statuses.PO as $status}
                            <option value="{$status.code}" {if $form_data.order.po_transit_status && $status.code in list $form_data.order.po_transit_status}selected{/if}>
                                {$status.name}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][po_transit_status]" id="nopots" {if $form_data.not.order.po_transit_status}checked{/if}>
                    <label for="nopots">Not</label>
                </div>
            </div>
        </li>


        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_po">PO status:</label>
                </div>

                <div class="columns large-6">
                    <select type="text" name="search[order][po_status][]" id="o_po" class="big" multiple>
                        {foreach $po_statuses as $code => $name}
                            <option value="{$code}" {if $form_data.order.po_status && $code in list $form_data.order.po_status}selected{/if}>
                                {$name}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][po_status]" id="nopos" {if $form_data.not.order.po_status}checked{/if}>
                    <label for="nopos">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_fraud">Fraud check status:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][fraud_status][]" id="o_fraud" class="big" multiple>
                        {foreach $fraud_statuses as $status}
                            <option value="{$status.code}" {if $form_data.order.fraud_status && $status.code in list $form_data.order.fraud_status}selected{/if}>
                                {$status.name}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][fraud_status]" id="nopfs" {if $form_data.not.order.fraud_status}checked{/if}>
                    <label for="nopfs">Not</label>
                </div>
            </div>
        </li>
    </ul>
</fieldset>

<fieldset class="{if $full_expanded}expanded-force{/if}"  rel="3">
    <legend>
        Advanced
    </legend>
    <ul class="ul-main">

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_id">Order #:</label>
                </div>

                <div class="columns large-6">
                    <input type="text" name="search[order][id][from]" id="o_id" value="{$form_data.order.id.from}"/>
                    <span>to</span>
                    <input type="text" name="search[order][id][to]" value="{$form_data.order.id.to}"/>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][id]" id="noi" {if $form_data.not.order.id}checked{/if}>
                    <label for="noi">Not selected</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_sf">Storefront:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][storefront][]" id="o_sf" class="big" multiple>
                        {foreach $storefronts as $id => $name}
                            <option value="{$id}" {if $form_data.order.storefront && $id in list $form_data.order.storefront}selected{/if}>
                                {$name}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][storefront]" id="nosf" {if $form_data.not.order.sf}checked{/if}>
                    <label for="nosf">Not selected</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_operator">Operator:</label>
                </div>

                <div class="columns large-6">
                    <select type="text" name="search[order][operator][]" id="o_operator" class="big" data-ajax-from="operator" multiple>
                        {foreach $form_data.order.operator as $value}
                            <option value="{raw $value.id}" selected>
                                {raw $value.text}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][operator]" id="noo" {if $form_data.not.order.operator}checked{/if}>
                    <label for="noo">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_features">Order features:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[features][]" id="o_features" class="big" multiple>
                        {foreach $features as $code => $name}
                            <option value="{$code}" {if $form_data.features && $code in list $form_data.features}selected{/if}>
                                {$name}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][features]" id="nf" {if $form_data.not.features}checked{/if}>
                    <label for="nf">Not selected</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_source">Order sales channel:</label>
                </div>

                <div class="columns large-6">
                    <select type="text" name="search[order][source][]" id="o_source" class="big" multiple>
                        {foreach $sources as $code => $name}
                            <option value="{$code}" {if $form_data.order.source && $code in list $form_data.order.source}selected{/if}>{$name}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][source]" id="nos" {if $form_data.not.order.source}checked{/if}>
                    <label for="nos">Not selected</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="p_vs">Verification status:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][vn_status][]" id="p_vs" class="big" multiple>
                        {foreach $order_statuses.PV as $status}
                            <option value="{$status.code}" {if $form_data.order.vn_status && $status.code in list $form_data.order.vn_status}selected{/if}>
                                {$status.name}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][vn_status]" id="novs" {if $form_data.not.order.vn_status}checked{/if}>
                    <label for="novs">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_tag">Attention tag:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][tag][]" id="o_tag" class="big" multiple>
                        {foreach $attention_tags as $tag}
                            <option value="{$tag.status_id}" title="{$tag.description}" {if $form_data.order.tag && $tag.status_id in list $form_data.order.tag}selected{/if}>
                                {$tag.status}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][tag]" id="notag" {if $form_data.not.order.tag}checked{/if}>
                    <label for="notag">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_ts">Transaction status:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][transaction_status][]" id="o_ts" class="big" multiple>
                        {foreach $transaction_status as $key => $status}
                            <option value="{$key}" title="{$key}" {if $form_data.order.transaction_status && $key in list $form_data.order.transaction_status}selected{/if}>
                                {$status}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][transaction_status]" id="nots" {if $form_data.not.order.transaction_status}checked{/if}>
                    <label for="nots">Not</label>
                </div>
            </div>
        </li>
        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_tm">Transaction payment method:</label>
                </div>

                <div class="columns large-6">
                    <select name="search[order][transaction_payment_method][]" id="o_tm" class="big" multiple>
                        {foreach $payment_methods as $method}
                            <option value="{$method.paymentid}" title="{$method.payment_details}" {if $form_data.order.transaction_payment_method && $method.paymentid in list $form_data.order.transaction_payment_method}selected{/if}>
                                {$method.payment_method}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="columns large-2 not">
                    <input type="checkbox" value="1" name="search[not][order][transaction_payment_method]" id="notm" {if $form_data.not.order.transaction_payment_method}checked{/if}>
                    <label for="notm">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="o_rs">Reconciliation status:</label>
                </div>

                <div class="columns large-6">
                    <div class="row">
                        <div class="columns large-2 padding-small">
                            <input name="search[order][reconciliation_status]" type="radio" value="" id="og_rs_off" {if !$form_data.order.reconciliation_status}checked{/if}>
                            <label for="og_rs_off">Off</label>
                        </div>
                        {foreach $reconciliation_status as $key => $status index=$index last=$last}
                            <div class="columns large-5 padding-small">
                                <input id="rs_{$key}" type="radio" value="{$key}" name="search[order][reconciliation_status]" {if $form_data.order.reconciliation_status == $key}checked{/if}>
                                <label for="rs_{$key}">
                                    {$status}
                                </label>
                            </div>
                            {if $index % 2 && $index != 0 && !$last}
                                <div class="columns large-2 padding-small">&nbsp;</div>
                            {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="og_all_dx_all">Has Dx invoices:</label>
                </div>

                <div class="columns large-6">
                    <div class="row">
                        <div class="columns large-2 padding-small">
                            <input name="search[order][all_dx]" type="radio" value="" id="og_all_dx_all" {if !$form_data.order.all_dx}checked{/if}>
                            <label for="og_all_dx_all">Off</label>
                        </div>
                        <div class="columns large-5 padding-small">
                            <input name="search[order][all_dx]" type="radio" value="Y" id="og_all_dx_y" {if $form_data.order.all_dx == 'Y'}checked{/if}>
                            <label for="og_all_dx_y" title="Присутствует во всех группах">Always present</label>
                        </div>
                        <div class="columns large-5 padding-small">
                            <input name="search[order][all_dx]" type="radio" value="N" id="og_all_dx_n" {if $form_data.order.all_dx == 'N'}checked{/if}>
                            <label for="og_all_dx_n" title="Присутствует в одной или всех группах">Sometimes present</label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="columns large-2 padding-small">&nbsp;</div>
                        <div class="columns large-5 padding-small">
                            <input name="search[order][all_dx]" type="radio" value="NA" id="og_all_dx_na" {if $form_data.order.all_dx == 'NA'}checked{/if}>
                            <label for="og_all_dx_na" title="Отсутствует в одной или всех группах">Sometimes absent</label>
                        </div>
                        <div class="columns large-5 padding-small">
                            <input name="search[order][all_dx]" type="radio" value="AN" id="og_all_dx_an" {if $form_data.order.all_dx == 'AN'}checked{/if}>
                            <label for="og_all_dx_an" title="Отсутствует во всех группах">Always absent</label>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="og_has_memo_all">Has credit memos:</label>
                </div>

                <div class="columns large-6">
                    <input name="search[order][has_memo]" type="radio" value="" id="og_has_memo_all" {if !$form_data.order.has_memo}checked{/if}>
                    <label for="og_has_memo_all">Off</label>
                    <input name="search[order][has_memo]" type="radio" value="Y" id="og_has_memo_y" {if $form_data.order.has_memo == 'Y'}checked{/if}>
                    <label for="og_has_memo_y">Yes</label>
                    <input name="search[order][has_memo]" type="radio" value="N" id="og_has_memo_n" {if $form_data.order.has_memo == 'N'}checked{/if}>
                    <label for="og_has_memo_n">Not</label>
                </div>
            </div>
        </li>

        <li>
            <div class="row">
                <div class="columns large-4">
                    <label for="og_has_invoice_cx_all">Has payment invoices sent to Cx:</label>
                </div>

                <div class="columns large-6">
                    <input name="search[order][has_icx]" type="radio" value="" id="og_has_invoice_cx_all" {if !$form_data.order.has_icx}checked{/if}>
                    <label for="og_has_memo_all">Off</label>
                    <input name="search[order][has_icx]" type="radio" value="Y" id="og_has_invoice_cx_y" {if $form_data.order.has_icx == 'Y'}checked{/if}>
                    <label for="og_has_invoice_cx_y">Yes</label>
                    <input name="search[order][has_icx]" type="radio" value="N" id="og_has_invoice_cx_n" {if $form_data.order.has_icx == 'N'}checked{/if}>
                    <label for="og_has_invoice_cx_n">Not</label>
                </div>
            </div>
        </li>
    </ul>
</fieldset>
<div class="hidden">
    <div id="help-dates">
        <h2>О датах</h2>
        <p>
            Указание дат доступно в 2х вариантах
        </p>
        <ol>
            <li>
                Выбор строгого диапазона посредством всплывающего календаря
            </li>
            <li>
                Указание относительного диапазона например "-7 day" <br>
                варианты могут выглядеть так
                <ul>
                    <li>
                        +/-N day
                    </li>
                    <li>
                        +/-N week
                    </li>
                    <li>
                        last Monday
                    </li>
                    <li>
                        -1 week 2 days 4 hours 2 seconds
                    </li>
                </ul>
            </li>
        </ol>
    </div>
</div>