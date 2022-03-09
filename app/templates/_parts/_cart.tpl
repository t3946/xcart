<div class="minicart mini-cart-container mini-cart-container__old"
     data-quantity="{$.app->cart->getQuantity()}"
     data-cart-url="{url 'cart:list'}"
     data-checkout-url="{$.call.Modules.Order.Helpers.OrderHelper::getCheckoutUrl()}"
     data-lng_checkout="{t 'Checkout'}"
     data-lng_remove="{t 'Remove'}"
     data-lng_img="{t 'Image not available'}"
></div>
