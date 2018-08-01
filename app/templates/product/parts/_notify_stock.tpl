{extends"ajax.tpl"}
{block 'content'}
    <div class="mmodal_notify_stock default-form text-center">

        <h2>Stock Notification</h2>

        <p>Email notification will be sent to your email address when the product is in stock.<br>
            <span class="notice-notify-me">The fields marked with <span style="color: #ff0000;">*</span> are mandatory</span></p>

            {raw $form->renderBegin([
            'action' => $.app->router->url('catalog:notify_stock'),
            'method' => 'POST',
            'class' => 'checkout-options-form',
            ])}

            <div class="row">
                <div class="column no-padding small-12">
                    {raw $form->render()}
                </div>
            </div>

        <div class="row align-center submit-button-container">
            <div class="column no-padding small-12">
                <div class="buttons text-center">
                    <button type="submit" class="button yellow waves waves-orange waves-effect">
                        Submit
                    </button>
                </div>
            </div>
        </div>
            {raw $form->renderEnd()}

    </div>
    <div class="row">
        <div class="small-12 column slider-related">
            {set $link}{url 'catalog:related' id=$productid}{/set}
            {include 'slider/base_product_slider.tpl' title="Similar products" link=$link hide=false hide_link=true}
        </div>
    </div>
{/block}