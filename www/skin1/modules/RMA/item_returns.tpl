{* $Id: item_returns.tpl,v 1.3 2006/03/17 12:30:42 svowl Exp $ *}
{if $product.returns_sum_R > 0 || $product.returns_sum_A > 0 || $product.returns_sum_C > 0}
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
  <td valign="top"><a href="returns.php?mode=search&amp;search[itemid]={$product.itemid}">{$lng.lbl_returns}</a>:</td>
  <td>{if $product.returns_sum_C eq $product.amount}{$lng.lbl_product_returned}{else}
	{if $product.returns_sum_R > 0}{$lng.lbl_products_requested|substitute:"N":$product.returns_sum_R}<br />{/if}
	{if $product.returns_sum_A > 0}{$lng.lbl_products_authorized|substitute:"N":$product.returns_sum_A}<br />{/if}
	{if $product.returns_sum_C > 0}{$lng.lbl_products_returned|substitute:"N":$product.returns_sum_C}<br />{/if}
{/if}</td>
</tr>
{/if}
