<div id="outer_wrapper">
    <div id="wrapper">
        <header id="main_header">
            <a id="close"></a>
            <div id="retail_logo">
                <a href="#" title="RetailTrust"></a>
            </div>
            <h1>3 month no-hustle 100% money-back guarantee</h1>
        </header>
        <div id="container">
            <section id="retail_block">
                <div id="fig_block">
                    <figure>
                        <div class="round retail_img">
                            <img src="{$ImagesDir}/popups/no_additional_fees.svg" alt="No additional fees" title="No additional fees" id="rt1" />
                        </div>
                        <div class="descr_container">
                            <figcaption>No additional fees, charges or deductibles</figcaption>
                        </div>
                    </figure>
                    <figure>
                        <div class="round retail_img">
                            <img src="{$ImagesDir}/popups/fast_refunds.svg" alt="Fast refunds" title="Fast refunds" id="rt2" />
                        </div>
                        <div class="descr_container">
                            <figcaption>Fast 100% refunds</figcaption>
                        </div>
                    </figure>
                    <figure>
                        <div class="round retail_img">
                            <img src="{$ImagesDir}/popups/quick_response.svg" alt="Quick response" title="Quick response" id="rt3" />
                        </div>
                        <div class="descr_container">
                            <figcaption>Quick response from our Customer Service team</figcaption>
                        </div>
                    </figure>
                </div>
                <p>Here is the service to make purchasing better for you. Do you know this awkward feeling when you not
                    sure whether your next purchase will be useful or not?
                    Just try it then with
                </p>
                <h2>3 months of immediate 100% refund for any reason</h2>
            </section>
            <section id="items_table">
                <form name="retail_form" id="retail_form">
                    <input type="hidden" id="retail_trust_order_id" name="retail_trust_order_id" value="{$oOrder->getOrderId()}"/>
                    <table>
                        <caption>Purchase Retail Trust For:</caption>
                        <thead>
                        <tr>
                            <th>Item name</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <td colspan="3">Retail Trust total: US$ 63.54</td>
                        </tr>
                        </tfoot>
                        <tbody>
                        {foreach from=$aRetailTrustProductDetails item=oRetailTrustDetail name=retailTrustForeach}
                            {assign var=oRetailTrustDetailProduct value=$oRetailTrustDetail->getOrderDetailProduct()}
                            <tr>
                                <td>
                                    <input type="checkbox" value="{$oRetailTrustDetailProduct->getProductId()}" name="retail_trust_item" id="retail_item{$smarty.foreach.retailTrustForeach.index}"/>
                                    <label for="retail_item{$smarty.foreach.retailTrustForeach.index}">{$oRetailTrustDetailProduct->getProductName()}</label>
                                    <div class="sum">
                                       {$oRetailTrustDetail->getAmount()}  х  {include file="currency.tpl" value=$oRetailTrustDetail->calculateRetailTrustPricePerProduct()}  =  <strong> {include file="currency.tpl" value=$oRetailTrustDetail->calculateRetailTrustPrice()}</strong>
                                    </div>
                                </td>
                                <td>{$oRetailTrustDetail->getAmount()}</td>
                                <td>{include file="currency.tpl" value=$oRetailTrustDetail->calculateRetailTrustPrice()}</td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </form>
            </section>
        </div>
        <footer id="main_footer">
            <div class="full_terms">
                <a href="#">Full Terms & Conditions</a>
            </div>
            <div id="order">
                <a href="#" class="thanks">No, thanks</a>
                <a class="green_btn">Add to order</a>
            </div>
        </footer>
    </div>
</div>
