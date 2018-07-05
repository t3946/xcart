<div class="minicart">
    <a class="cart_info" href="{url 'cart:list'}">
        <span class="count">
            <span id="desktop-cart-quantity" class="mc_count">
                {$.app->cart->getQuantity()}
            </span>
        </span>
        <span class="text">
            {t 'Cart' dict='cart'}
        </span>
    </a>
</div>