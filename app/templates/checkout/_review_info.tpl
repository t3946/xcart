<section class="checkout-review">
    {set $lbl}{t 'Product ordered'}{/set}
    {include "checkout/_review_order.tpl" order = $order header = $lbl}
</section>

<ul class="accordion order-review-info-tabs" data-accordion  data-allow-all-closed="true" data-multi-expand="true">
    <li class="accordion-item shipping-review" data-accordion-item>
        <a href="#" class="accordion-title shipping-review-title">{t 'Shipping and Billing Information' }</a>
        <div class="accordion-content shipping-review-container" data-tab-content>
            <section class="shipping-review" data-tab-content>
                {include "checkout/_review_shipping.tpl" order = $order }
            </section>
        </div>
    </li>
</ul>