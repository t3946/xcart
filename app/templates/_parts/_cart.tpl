<div class="minicart">
    <div class="cart_info">
        <div class="count">
            <div id="desktop-cart-quantity" class="mc_count">
                {$.app->cart->getQuantity()}
            </div>
        </div>
        <span class="text">
            {t 'Cart' dict='cart'}
        </span>
    </div>
</div>