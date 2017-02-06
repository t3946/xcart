
<fieldset class="expanded" rel="1">
    <legend>General</legend>

    <ul class="ul-main">
        <li>
            <div class="label">
                <label for="o_date">Order date:</label>
            </div>

            <div class="input">
                <input type="text" name="search[order][date]" id="o_date"
                       value="{$form_data.order.date}"
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
                <input type="text" name="search[order][total][from]" id="o_total" value="{$form_data.order.total.from}">
                <span>to</span>
                <input type="text" name="search[order][total][to]" value="{$form_data.order.total.to}">
            </div>
        </li>

        <li>
            <div class="label">
                <label for="c_name">Customer name:</label>
            </div>

            <div class="input">
                <select name="search[customer][name][]" id="c_name" class="big" multiple data-ajax-from="search_customer_name" data-combobox="1">
                    {foreach $form_data.customer.name as $value}
                        <option value="{raw $value}" selected>{raw $value}</option>
                    {/foreach}
                </select>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="c_phone">Customer phone/fax:</label>
            </div>

            <div class="input">
                <select name="search[customer][phone][]" id="c_phone" class="big" multiple data-ajax-from="search_phone" data-combobox="1">
                    {foreach $form_data.customer.phone as $value}
                        <option value="{raw $value}" selected>{raw $value}</option>
                    {/foreach}
                </select>
            </div
            >
        </li>

        <li>
            <div class="label">
                <label for="c_email">Customer email:</label>
            </div>

            <div class="input">
                <select name="search[customer][email][]" id="c_email" class="big" multiple data-ajax-from="search_email" data-combobox="1">
                    {foreach $form_data.customer.email as $value}
                        <option value="{raw $value}" selected>{raw $value}</option>
                    {/foreach}
                </select>
            </div>
        </li>


        <li>
            <div class="label">
                <label for="c_company">Search in address:</label>
            </div>

            <div class="input">
                <input type="radio" name="search[customer][in_address]" id="c_in_address_both" value="both" {if $form_data.customer.in_address == 'both' or !$form_data.customer.in_address}checked{/if}>
                <label for="c_in_address_both">Both</label>

                <input type="radio" name="search[customer][in_address]" id="c_in_address_billig" value="billing" {if $form_data.customer.in_address == 'billing'}checked{/if}>
                <label for="c_in_address_billig">Billing</label>

                <input type="radio" name="search[customer][in_address]" id="c_in_address_shipping" value="shipping" {if $form_data.customer.in_address == 'shipping'}checked{/if}>
                <label for="c_in_address_shipping">Shipping</label>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="c_company">Company:</label>
            </div>

            <div class="input">
                <select name="search[customer][company][]" id="c_company" class="big" multiple data-ajax-from="company">
                    {foreach $form_data.customer.company as $value}
                        <option value="{raw $value}" selected>{raw $value}</option>
                    {/foreach}
                </select>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="c_street">Street/Home (address):</label>
            </div>

            <div class="input">
                <select name="search[customer][address][]" id="c_street" class="big" multiple data-ajax-from="search_street" data-combobox="1">
                    {foreach $form_data.customer.address as $value}
                        <option value="{raw $value.id}" selected>{raw $value.text}</option>
                    {/foreach}
                </select>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="c_city">City:</label>
            </div>

            <div class="input">
                <select name="search[customer][city][]" id="c_city" class="big" multiple data-ajax-from="search_city">
                    {foreach $form_data.customer.city as $value}
                        <option value="{raw $value.id}" selected>{raw $value.text}</option>
                    {/foreach}
                </select>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="c_state">State/Province:</label>
            </div>

            <div class="input">
                <select name="search[customer][state][]" id="c_state" class="big" multiple data-ajax-from="search_state">
                    {foreach $form_data.customer.state as $value}
                        <option value="{raw $value.id}" selected>{raw $value.text}</option>
                    {/foreach}
                </select>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="c_country">Country:</label>
            </div>

            <div class="input">
                <select name="search[customer][country][]" id="c_country" class="big" multiple data-ajax-from="search_country">
                    {foreach $form_data.customer.country as $value}
                        <option value="{raw $value.id}" selected>{raw $value.text}</option>
                    {/foreach}
                </select>
            </div>
        </li>


        <li>
            <div class="label">
                <label for="c_zip">Zip/Postal code:</label>
            </div>

            <div class="input">
                <input type="text" name="search[customer][zip_code]" id="c_zip" class="big" multiple data-ajax-from="search_zip" data-combobox="1" value="{$form_data.customer.zip_code}">
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
                <input type="text" name="search[product][name]" id="p_name" class="big" value="{$form_data.product.name}">
            </div>
        </li>

        <li>
            <div class="label">
                <label for="p_sku">SKU:</label>
            </div>

            <div class="input">
                <input type="text" name="search[product][sku]" id="p_sku" class="big" value="{$form_data.product.sku}">
            </div>
        </li>

        <li>
            <div class="label">
                <label for="p_id">Product ID:</label>
            </div>

            <div class="input">
                <input type="text" name="search[product][id]" id="p_id" class="big" value="{$form_data.product.sku}">
            </div>
        </li>

        <li>
            <div class="label">
                <label for="p_qs">Question status:</label>
            </div>

            <div class="input">
                <select name="search[product][question_status][]" id="p_qs" class="big" multiple>
                    {foreach $question_statuses as $code => $status}
                        <option value="{$code}" {if $code|in:$form_data.product.question_status}selected{/if}>
                            {$status}
                        </option>
                    {/foreach}
                </select>
            </div>
        </li>
    </ul>
</fieldset>

<fieldset class="expanded collapsed"  rel="2">
    <legend>
        Advanced - Payment \ Shipping
    </legend>
    <ul class="ul-main">
        <li>
            <div class="label">
                <label for="o_payment">Order payment method:</label>
            </div>

            <div class="input">
                <select name="search[order][payment_method][]" id="o_payment" class="big" multiple>
                    {foreach $payment_methods as $method}
                        <option value="{$method.paymentid}" title="{$method.payment_details}" {if $method.paymentid|in:$form_data.order.payment_method}selected{/if}>
                            {$method.payment_method}
                        </option>
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
                        <option value="{$method.shippingid}" {if $method.shippingid|in:$form_data.order.delivery_method}selected{/if}>
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
                        <option value="{$status.code}" {if $status.code|in:$form_data.order.c2b_status}selected{/if}>
                            {$status.name}
                        </option>
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
                        <option value="{$status.code}" {if $status.code|in:$form_data.order.d2c_status}selected{/if}>
                            {$status.name}
                        </option>
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
                        <option value="{$status.code}" {if $status.code|in:$form_data.order.po_transit_status}selected{/if}>
                            {$status.name}
                        </option>
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
                        <option value="{$code}" {if $code|in:$form_data.order.po_status}selected{/if}>
                            {$name}
                        </option>
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
                        <option value="{$status.code}" {if $status.code|in:$form_data.order.fraud_status}selected{/if}>
                            {$status.name}
                        </option>
                    {/foreach}
                </select>
            </div>
        </li>
    </ul>
</fieldset>

<fieldset class="expanded collapsed"  rel="3">
    <legend>
        Advanced
    </legend>
    <ul class="ul-main">

        <li>
            <div class="label">
                <label for="o_id">Order ID:</label>
            </div>

            <div class="input">
                <input type="text" name="search[order][id][from]" id="o_id" value="{$form_data.order.id.from}"/>
                <span>to</span>
                <input type="text" name="search[order][id][to]" value="{$form_data.order.id.to}"/>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="o_operator">Operator:</label>
            </div>

            <div class="input">
                <select type="text" name="search[order][operator][]" id="o_operator" class="big" data-ajax-from="operator" multiple>
                    {foreach $form_data.order.operator as $value}
                        <option value="{raw $value.id}" selected>
                            {raw $value.text}
                        </option>
                    {/foreach}
                </select>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="o_features">Order features:</label>
            </div>

            <div class="input">
                <select name="search[features][]" id="o_features" class="big" multiple>
                    {foreach $features as $code => $name}
                        <option value="{$code}" {if $code|in:$form_data.features}selected{/if}>
                            {$name}
                        </option>
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
                        <option value="{$code}" {if $code|in:$form_data.order.source}selected{/if}>{$name}</option>
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
                        <option value="{$status.code}" {if $status.code|in:$form_data.order.vn_status}selected{/if}>
                            {$status.name}
                        </option>
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
                        <option value="{$tag.status_id}" title="{$tag.description}" {if $tag.status_id|in:$form_data.order.tag}selected{/if}>
                            {$tag.status}
                        </option>
                    {/foreach}
                </select>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="o_distributor">Distributors:</label>
            </div>

            <div class="input">
                <select name="search[order][distributor][]" id="o_distributor" class="big" multiple data-ajax-from="distributor">
                    {foreach $form_data.order.distributor as $value}
                        <option value="{raw $value.id}" selected>{raw $value.text}</option>
                    {/foreach}
                </select>
            </div>
        </li>

        <li>
            <div class="label">
                <label for="og_has_dx_all">Has Dx invoice:</label>
            </div>

            <div class="input">
                <input name="search[order][has_dx]" type="radio" value="" id="og_has_dx_all" {if !$form_data.order.has_dx}checked{/if}>
                <label for="og_has_dx_all">All</label>
                <input name="search[order][has_dx]" type="radio" value="Y" id="og_has_dx_y" {if $form_data.order.has_dx == 'Y'}checked{/if}>
                <label for="og_has_dx_y">Yes</label>
                <input name="search[order][has_dx]" type="radio" value="N" id="og_has_dx_n" {if $form_data.order.has_dx == 'N'}checked{/if}>
                <label for="og_has_dx_n">Not</label>
            </div>
        </li>
        <li>
            <div class="label">
                <label for="og_has_memo_all">Has credit memos:</label>
            </div>

            <div class="input">
                <input name="search[order][has_memo]" type="radio" value="" id="og_has_memo_all" {if !$form_data.order.has_memo}checked{/if}>
                <label for="og_has_memo_all">All</label>
                <input name="search[order][has_memo]" type="radio" value="Y" id="og_has_memo_y" {if $form_data.order.has_memo == 'Y'}checked{/if}>
                <label for="og_has_memo_y">Yes</label>
                <input name="search[order][has_memo]" type="radio" value="N" id="og_has_memo_n" {if $form_data.order.has_memo == 'N'}checked{/if}>
                <label for="og_has_memo_n">Not</label>
            </div>
        </li>
        <li>
            <div class="label">
                <label for="og_has_invoice_cx_all">Has payment invoices sent to Cx:</label>
            </div>

            <div class="input">
                <input name="search[order][has_icx]" type="radio" value="" id="og_has_invoice_cx_all" {if !$form_data.order.has_icx}checked{/if}>
                <label for="og_has_memo_all">All</label>
                <input name="search[order][has_icx]" type="radio" value="Y" id="og_has_invoice_cx_y" {if $form_data.order.has_icx == 'Y'}checked{/if}>
                <label for="og_has_invoice_cx_y">Yes</label>
                <input name="search[order][has_icx]" type="radio" value="N" id="og_has_invoice_cx_n" {if $form_data.order.has_icx == 'N'}checked{/if}>
                <label for="og_has_invoice_cx_n">Not</label>
            </div>
        </li>
    </ul>
</fieldset>

<ul class="ul-main">
    <li>
        <div class="label">
            <label for="fo_nlist">New order list:</label>
        </div>

        <div class="input">
            <input type="hidden" name="search[new_list]" value="0">
            <input type="checkbox" name="search[new_list]" id="fo_nlist" value="1" {if $form_data.new_list}checked{/if}>
        </div>
    </li>
</ul>