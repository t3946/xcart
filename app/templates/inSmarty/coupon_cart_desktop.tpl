
{if $.app->getModule('Cart')->isCouponActive()}
    {set $couponModel = $.app->getModule('Cart')->getCouponModel()}

    <div class="coupon" style="text-align: right">
    {if !$couponModel}
        <label for="coupon-code" style="margin-right: 1em; color: #CC3333; font-size: 1.1em;">
            Got a coupon code ?
        </label>

        <input type="text" name="coupon_code" id='coupon-code' value="" placeholder="Enter it here">
        <button name="check-code" class="cidev_new_button cidev_new_white" style="font-size: 11px;">
            Apply
        </button>

    {else}

        <div class="discard">
            <span style="margin-right: 1em;">
                Appended coupon:
                {*{if $couponModel->description}*}
                    {*<a href="{$couponModel->getAbsoluteUrl()}" target="_blank" title="Show coupon policy / description.">{$couponModel->code}</a>*}
                {*{else}*}
                    {*{$couponModel->code}*}
                {*{/if}*}
                {$couponModel->code}
            </span>

            <button name="discard-coupon" value="1" class="cidev_new_button cidev_new_white" style="font-size: 1.15em">
                Discard
            </button>
        </div>

        <div class="discount">

            {add $validator = $.app->getModule('Cart')->getValidateComponent()}

            {if !$validator->isValid() && $validator->precalcProducts()}
                <p>
                    Your supposed discount is <span class="ProductPriceSmall">US$ {$.app->getModule('Cart')->getValidateComponent()->getProductDiscount()|number_format:2}</span>.
                    <br>

                    The exact amount of discount will be calculated on Order Review page.
                </p>
            {/if}
        </div>


        {if $couponModel->description}
        <div class="description">
            {$couponModel->description}
        </div>
        {/if}

        <div>
        </div>
    {/if}
    </div>
    <hr size="1" noshade="noshade" />
{/if}