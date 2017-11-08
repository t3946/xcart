{set $couponModel = $.app->getModule('Cart')->getCouponModel()}

<div class="coupon" style="text-align: right">
{if !$couponModel}
    <label for="coupon-code" style="margin-right: 1em;">
		Got a coupon code ?
	</label>

    <input type="text" name="coupon_code" id='coupon-code' value="" placeholder="Enter it here">
    <button name="check-code" class="cidev_new_button cidev_new_white" style="font-size: 11px;">
		Apply
	</button>

{else}
	<div class="coupon_info">
		<a href="{$couponModel->getAbsoluteUrl()}" target="_blank">
			Show me coupon polisy
		</a>
	</div>
	<br>

	<div class="discard">
		<span style="margin-right: 1em;">
			Discard coupon code ?
		</span>

		<button name="discard-coupon" value="1" class="cidev_new_button cidev_new_white" style="font-size: 11px;">
			Discard
		</button>
	</div>
{/if}
</div>
<hr size="1" noshade="noshade" />