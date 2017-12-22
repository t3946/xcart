{* $Id: add_coupon.tpl,v 1.11.2.3 2006/10/25 13:58:54 svowl Exp $ *}
<table cellpadding="5" cellspacing="5" width="100%">
<tr>
	<td valign="top" width="30%">
		{include file="customer/main/subheader.tpl" title=$lng.lbl_redeem_discount_coupon}
		{$lng.txt_add_coupon_header}
		<br><br>
		{if $gcheckout_enabled}
			{$lng.txt_gcheckout_add_coupon_note}
			<br />
			<br />
		{/if}
	</td>
	<td valign="top" width="70%">
		{include file="customer/main/subheader.tpl" title=$lng.lbl_coupon_code}
		<form action="cart.php" name="couponform">

		<table>
		<tr>
	<td><input type="text" size="32" name="coupon" /></td>
		</tr>
		<tr>
	<td>
				{if $js_enabled}
					{include file="buttons/submit.tpl" href="javascript: document.couponform.submit();" js_to_href="Y"}
				{else}
					<input type="submit" value="{$lng.lbl_submit|strip_tags:false|escape}" />
				{/if}
			</td>
		</tr>
		</table>
		
		<input type="hidden" name="mode" value="add_coupon" />
		</form>
	</td>
</tr>
</table>
