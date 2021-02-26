{set $add_to_cart_button_class = 'add-to-cart-button'}
{set $add_to_cart_button_main_class = 'add-to-cart-button-main'}

{if isset($type)}
    {set $add_to_cart_button_class = "$add_to_cart_button_class {$add_to_cart_button_class}_{$type}"}
    {set $add_to_cart_button_main_class = "$add_to_cart_button_main_class {$add_to_cart_button_main_class}_{$type}"}
{/if}

<div class="{$add_to_cart_button_class}">
    <a class="add button yellow wait-button {$add_to_cart_button_main_class}">
        <span class="text">{t 'Add to cart'}</span>
        <span class="wait-text">{t 'Added'}</span>
    </a>
    <a href="/cart/" class="button yellow-white waves waves-orange waves-effect add-to-cart-button-checkout">Checkout</a>
</div>
