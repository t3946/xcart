<div class="minicart" data-lng_checkout="{t 'Checkout'}" data-lng_remove="{t 'Remove'}" data-lng_img="{t 'Image not available'}">
    <a class="cart_info" href="{url 'cart:list'}">
        <span class="count">
            <span id="desktop-cart-quantity" class="mc_count">
                {$.app->cart->getQuantity()}
            </span>
        </span>
        <span class="text">
            {t 'Cart' }
        </span>
    </a>
</div>