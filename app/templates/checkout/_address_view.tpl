<h2 class="title">{$header}</h2>

{set $address = $order->getAddress()}

<ul class="address-view">
    <li>{$address[0]}</li>
    {if $address[1]}<li>{$address[1]}</li>{/if}
    <li>{$order->s_city}</li>
    <li>{$order->s_state}</li>
    <li>{$order->s_country}</li>
    <li>{$order->s_zipcode}</li>
</ul>

<div class="row align-center">
    <div class="columns small-12">
        <a href="{url $uri}" class="button yellow-white waves waves-orange waves-effect">{t 'Modify' dict='order'}</a>
    </div>
</div>