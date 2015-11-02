{* $Id: product_special_price.tpl,v 1.3 2005/11/17 06:55:56 max Exp $ *}

<br /><br />
<font class="SpecialOffersPrice">{$lng.lbl_sp_special_price}: 
{if $product.special_price gt 0}
{include file="currency.tpl" value=$product.special_price}
{else}
{$lng.lbl_sp_special_price_free}
({include file="currency.tpl" value=$product.special_price})
{/if}
</font>
<br /><br />
