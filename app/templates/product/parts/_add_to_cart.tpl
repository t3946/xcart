{set $add_to_cart_button_class = 'add-to-cart-button'}
{set $add_to_cart_button_add_class = 'add-to-cart-button-add'}
{set $add_to_cart_button_checkout_class = 'add-to-cart-button-checkout'}
{set $add_button_complex_class = 'add-to-cart-button-add__complex'}
{set $checkout_button_complex_class = 'add-to-cart-button-checkout__complex'}

{if isset($type)}
    {set $add_to_cart_button_class = "$add_to_cart_button_class {$add_to_cart_button_class}_{$type}"}
    {set $add_to_cart_button_add_class = "$add_to_cart_button_add_class {$add_to_cart_button_add_class}__{$type}"}
    {set $add_to_cart_button_checkout_class = "$add_to_cart_button_checkout_class {$add_to_cart_button_checkout_class}_{$type}"}
    {set $add_button_complex_class = "{$add_button_complex_class}-{$type}"}
    {set $checkout_button_complex_class = "$checkout_button_complex_class-{$type}"}
{/if}

<div
        class="{$add_to_cart_button_class}"
        data-add-complex-class="{$add_button_complex_class}"
        data-checkout-complex-class="{$checkout_button_complex_class}"
>
    <a class="add button yellow wait-button {$add_to_cart_button_add_class}">
        <span class="text">{t 'Add to cart'}</span>
        <span class="wait-text">{t 'Added'}</span>
    </a>
    <div class="add-to-cart-button-wrapper">
        <a href="{Modules\Order\Helpers\OrderHelper::getCheckoutUrl()}" class="button yellow-white waves waves-orange waves-effect {$add_to_cart_button_checkout_class}">Checkout</a>
        {if isset($noAccount) && $noAccount === true}<div class="no-account">{t "No account needed! \n Checkout only takes 3 minutes."}</div>{/if}
    </div>
</div>
