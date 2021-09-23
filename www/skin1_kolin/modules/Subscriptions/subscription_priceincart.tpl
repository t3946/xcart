<table cellpadding="0" cellspacing="0">

<tr>
<td>{$lng.lbl_subscription_plan}:</td>
<td>&nbsp;</td>
<td>{$product.sub_plan}{if $product.sub_plan eq "By Period"} ({$product.sub_days_remain} {$lng.lbl_days}){/if}</td>
</tr>

{if $product.sub_onedayprice > 0}
<tr>
<td>{$lng.lbl_day_cost_by_subscr_plan}:</td>
<td>&nbsp;</td>
<td>{include file="currency.tpl" value=$product.sub_onedayprice}</td>
</tr>
{/if}

{if $product.sub_days_remain > 0}
<tr>
<td>{$lng.lbl_days_remain}:</td>
<td>&nbsp;</td>
<td>{$product.sub_days_remain}</td>
</tr>
{/if}

</table>
<br />

{if $product.sub_onedayprice > 0 and $product.sub_days_remain > 0}
<font class="ProductPriceConverting">({include file="currency.tpl" value=$product.catalogprice} + {include file="currency.tpl" value=$product.sub_onedayprice} x {$product.sub_days_remain}) x {$product.amount} = </font><font class="ProductPrice">{math equation="(price+days*day_cost)*amount" price=$product.catalogprice amount=$product.amount days=$product.sub_days_remain day_cost=$product.sub_onedayprice format="%.2f" assign=unformatted}{include file="currency.tpl" value=$unformatted}</font><font class="MarketPrice"> {include file="customer/main/alter_currency_value.tpl" alter_currency_value=$unformatted}</font> 
{else}
{assign var="price" value=$product.taxed_price}
<font class="ProductPriceConverting">{include file="currency.tpl" value=$product.catalogprice} x {$product.amount} = </font><font class="ProductPrice">{math equation="price*amount" price=$price amount=$product.amount format="%.2f" assign=unformatted}{include file="currency.tpl" value=$unformatted}</font><font class="MarketPrice"> {include file="customer/main/alter_currency_value.tpl" alter_currency_value=$unformatted}</font> 
{/if}
