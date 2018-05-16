<ul class="accordion order-review-info-tabs" data-accordion  data-allow-all-closed="true">

    <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">{t 'Product ordered' dict='order'}</a>
        <div class="accordion-content" data-tab-content>
            <section class="checkout-review">
                {include "checkout/_review_order.tpl" order = $order header = $.t('Product ordered','order')}
            </section>
        </div>
    </li>

    <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">{t 'Shipping and Billing Address' dict='order'}</a>
        <div class="accordion-content" data-tab-content>
            <section class="shipping-review" data-tab-content>
                {include "checkout/_shipping_review.tpl" order = $order header = $.t('Shipping and Billing Address','order')}
            </section>
        </div>
    </li>
</ul>