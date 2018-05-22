<div class="row align-center show-for-large">
    <div class="columns small-12">
        <h1 class="text-center">{$header}</h1>
    </div>
</div>
<div class="row info-row contact-info">
    <div class="columns small-12 large-6">
        <div class="row">
            <div class="columns">
                <h2>{t 'Contact information' dict='order'}</h2>
            </div>
        </div>
        <div class="row full-name">
            <div class="columns info-title small-5">{t 'Full name:' dict='order'}</div>
            <div class="columns info-text">{$order->firstname}</div>
        </div>
        <div class="row phone">
            <div class="columns info-title small-5">{t 'Phone:' dict='order'}</div>
            <div class="columns info-text">{$order->phone}</div>
        </div>
        <div class="row email">
            <div class="columns info-title small-5">{t 'Email:' dict='order'}</div>
            <div class="columns info-text">{$order->email}</div>
        </div>
    </div>
</div>
<div class="row info-row address">
    <div class="columns small-12 large-6">
        {include "checkout/_address_view_full.tpl" info=$shipping_address uri='checkout:shipping' header=$.t('Shipping Address','order')}
    </div>
    <div class="columns small-12 large-6">
        {include "checkout/_address_view_full.tpl" info=$billing_address uri='checkout:options' header=$.t('Billing Address','order')}
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="hr-info"></div>
    </div>
</div>
<div class="row delivery">
    <div class="columns small-12 large-6">
        <div class="row">
            <div class="columns">
                <h2>{t 'Delivery methods' dict='order'}</h2>
            </div>
        </div>
        {foreach $order->groups as $group}
            {set $warehouse = $.get_warehouse($group->manufacturerid)}
            {set $shipping_model = $group->shippingModel}
            <div class="row info-row delivery-method">
                <div class="columns">
                    <div class="row">
                        <div class="column info-title small-5">{$warehouse->m_city}, {$warehouse->m_state},
                            {$warehouse->m_country}
                            <div>{t 'warehouse items:' dict='order'}</div>
                        </div>
                        <div class="column info-text"></div>
                    </div>
                </div>
                {if $shipping_model}
                    <div class="columns">{$shipping_model->getFrontendName()} - {$shipping_model->shipping_time}</div>
                {/if}
            </div>
        {/foreach}
        <div class="row align-center">
            <div class="columns small-12">
                <a href="{url 'checkout:shipping'}" class="button yellow-white waves waves-orange waves-effect">{t 'Modify' dict='order'}</a>
            </div>
        </div>
    </div>
    <div class="columns small-12 large-6">
        <div class="row">
            <div class="columns">
                <h2>{t 'Payment method' dict='order'}</h2>
            </div>
        </div>
        <div class="row payment-method info-row">
            <div class="columns small-5 info-title">
                {t 'Payment method:' dict='order'}
            </div>
            <div class="columns info-text">{$order->payment_method}</div>
        </div>
        <div class="row align-center">
            <div class="columns small-12">
                <a href="{url 'checkout:options'}" class="button yellow-white waves waves-orange waves-effect">{t 'Modify' dict='order'}</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="hr-info"></div>
    </div>
</div>