<ul class="accordion order-review-info-tabs" data-accordion  data-allow-all-closed="true">

    <li class="accordion-item is-active" data-accordion-item>
        <a href="#" class="accordion-title checkout-review-title">{t 'Product ordered' dict='order'}</a>
        <div class="accordion-content checkout-review-container" data-tab-content>
            <section class="checkout-review">
                {include "checkout/_review_order.tpl" order = $order header = $.t('Product ordered','order')}
            </section>
        </div>
    </li>

    <li class="accordion-item shipping-review" data-accordion-item>
        <a href="#" class="accordion-title shipping-review-title">{t 'Shipping and Billing Address' dict='order'}</a>
        <div class="accordion-content shipping-review-container" data-tab-content>
            <section class="shipping-review" data-tab-content>
                {include "checkout/_review_shipping.tpl" order = $order }
            </section>
        </div>
    </li>
</ul>