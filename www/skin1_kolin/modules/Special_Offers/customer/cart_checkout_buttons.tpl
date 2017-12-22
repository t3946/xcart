{if $cart.not_used_free_products ne ""}
	<td align="right">
{include file="buttons/button.tpl" button_title=$lng.lbl_sp_add_free_products style="button"  href="offers.php?mode=add_free"}
	</td>
{elseif $customer_unused_offers ne ""}
	<td align="right">
{include file="buttons/button.tpl" button_title=$lng.lbl_sp_unused_offers style="button"  href="offers.php?mode=unused"}
	</td>
{/if}
