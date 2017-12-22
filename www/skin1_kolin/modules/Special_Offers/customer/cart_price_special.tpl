{* $Id: cart_price_special.tpl,v 1.3 2005/11/17 06:55:56 max Exp $ *}

{if $products[product].saved_original_price ne "" and $products[product].special_price_used and $products[product].saved_original_price ne $price}
{$lng.lbl_sp_common_price}: 
<font class="ProductPriceConverting"><s>{include file="currency.tpl" value=$products[product].saved_original_price}</s></font>
<br />
{$lng.lbl_sp_special_price}:
{/if}
