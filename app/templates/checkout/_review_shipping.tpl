<div class="row align-center show-for-large">
    <div class=" col-12">
        <h1 class="text-center title-margin-small">{$header}</h1>
    </div>
</div>
<div class="row info-row contact-info">
    <div class=" col-12 col-lg-6">
        <div class="row">
            <div class="">
                <h2 class="">{t 'Contact information' }</h2>
            </div>
        </div>
        <div class="row full-name">
            <div class=" info-title col-6">{t 'Full name' }:</div>
            <div class=" info-text col">{$order->firstname}</div>
        </div>
        <div class="row phone">
            <div class=" info-title col-6">{t 'Phone' }:</div>
            <div class=" info-text col">{$order->phone}</div>
        </div>
        <div class="row email">
            <div class=" info-title col-6">{t 'Email' }:</div>
            <div class=" info-text col">{$order->email}</div>
        </div>
    </div>
</div>
<div class="row info-row address">
    <div class=" col-12 col-lg-6">
        {set $lbl}{t 'Shipping Address'}{/set}
        {include "checkout/_address_view_full.tpl" info=$shipping_address uri='checkout:shipping' header=$lbl}
    </div>
    <div class=" col-12 col-lg-6">
        {set $lbl}{t 'Billing Address'}{/set}
        {include "checkout/_address_view_full.tpl" info=$billing_address uri='checkout:options' header=$lbl}
    </div>
</div>
{*<div class="row">*}
    {*<div class="col-12 ">*}
        {*<div class="hr-info"></div>*}
    {*</div>*}
{*</div>*}
<div class="row delivery">
    <div class=" col-12 col-lg-6">
        <div class="row">
            <div class="">
                <h2 class="">{t 'Delivery methods' }</h2>
            </div>
        </div>
        {foreach $order->groups as $group}
            {set $warehouse = $.get_warehouse($group->manufacturerid)}
            {set $shipping_model = $group->shippingModel}
            <div class="row info-row delivery-method">
                <div class=" info-title col-6">
                    {$warehouse->m_city},
                    {if $config.show_full_state_country}{$warehouse->state_model}{else}{$warehouse->m_state}{/if},
                    {if $config.show_full_state_country}{$warehouse->country_model->countryNameBySite()}{else}{$warehouse->m_country}{/if}
                    <br/>{t 'warehouse items:' }
                </div>
                {if $shipping_model}
                    <div class="info-text col">{$shipping_model->getFrontendName()}<br/>{$shipping_model->shipping_time}</div>
                {/if}
            </div>
        {/foreach}
        <div class="row align-center">
            <div class=" col-12">
                <a href="{url 'checkout:options'}" class="button yellow-white waves waves-orange waves-effect small yellow-border text-decoration-none">{t 'Modify' }</a>
            </div>
        </div>
    </div>
    <div class=" col-12 col-lg-6">
        <div class="row">
            <div class="">
                <h2 class="">{t 'Payment method' }</h2>
            </div>
        </div>
        <div class="row payment-method info-row">
            <div class=" col-6 info-title">
                {t 'Payment method' }:
            </div>
            <div class=" info-text col">{$order->payment_method}</div>
        </div>
        <div class="row align-center">
            <div class=" col-12">
                <a href="{url 'checkout:options'}" class="button yellow-white waves waves-orange waves-effect small yellow-border text-decoration-none">{t 'Modify' }</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 ">
        <div class="hr-info"></div>
    </div>
</div>