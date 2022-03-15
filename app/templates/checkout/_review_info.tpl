<section class="checkout-review">
    {set $lbl}{t 'Product ordered'}{/set}
    {include "checkout/_review_order.tpl" order = $order header = $lbl}
</section>

<style>
.review-accordion-title:before {
    margin-top: -0.42em;
}

.review-accordion-title.minus:before {
    content: '-';
    padding-right: 5px;
}
</style>

<ul class="accordion order-review-info-tabs px-0" data-accordion  data-allow-all-closed="true" data-multi-expand="true">
    <li id="checkout-review-accordion" class="accordion-item shipping-review border-0 align-items-center" data-accordion-item>
        <div class="accordion-title shipping-review-title review-accordion-title m-0">{t 'Shipping and Billing Information' }</div>
        <div id="shipping-review-container" class="shipping-review-container w-100" data-tab-content>
            <section class="shipping-review" data-tab-content>
                {include "checkout/_review_shipping.tpl" order = $order }
            </section>
        </div>
    </li>
</ul>