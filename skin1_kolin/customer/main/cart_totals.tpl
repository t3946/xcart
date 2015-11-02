{* $Id: cart_totals.tpl,v 1.91.2.8 2006/12/28 07:16:39 max Exp $ *}
<div align="right">
{assign var="subtotal" value=$cart.subtotal}
{assign var="discounted_subtotal" value=$cart.discounted_subtotal}
{assign var="shipping_cost" value=$cart.display_shipping_cost}

<table cellpadding="3" cellspacing="0" width="30%">

<tr>
<td nowrap="nowrap"><font class="FormButton">{$lng.lbl_total}:</font></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="FormButton">{include file="currency.tpl" value=$cart.display_subtotal}</font></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$cart.display_subtotal}</td>
</tr>

{if $cart.discount gt 0}
<tr>
<td nowrap="nowrap"><font class="FormButton">{$lng.lbl_discount}:</font></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.discount}</font></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$cart.discount}</td>
</tr>
{/if}

{if $cart.coupon_discount ne 0 and $cart.coupon_type ne "free_ship"}
<tr>
<td nowrap="nowrap"><font class="FormButton">{$lng.lbl_discount_coupon} <a href="cart.php?mode=unset_coupons" alt="{$lng.lbl_unset_coupon|escape}"><img src="{$ImagesDir}/clear.gif" width="11" height="11" border="0" valign="top" alt="{$lng.lbl_unset_coupon|escape}" /></a> :</font></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.coupon_discount}</font></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$cart.coupon_discount}</td>
</tr>
{/if}

{if $cart.display_discounted_subtotal ne $cart.display_subtotal}
<tr>
<td nowrap="nowrap"><font class="FormButton">{$lng.lbl_discounted_total}:</font></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.display_discounted_subtotal}</font></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$cart.display_discounted_subtotal}</td>
</tr>
{/if}

{if $config.Shipping.disable_shipping ne "Y"}
<tr>
<td nowrap="nowrap" class="Green2">{$lng.lbl_total_shipping_cost}{if $cart.coupon_discount ne 0 and $cart.coupon_type eq "free_ship"} ({$lng.lbl_discounted} <a href="cart.php?mode=unset_coupons" alt="{$lng.lbl_unset_coupon|escape}"><img src="{$ImagesDir}/clear.gif" width="11" height="11" border="0" valign="top" alt="{$lng.lbl_unset_coupon|escape}" /></a>){/if}
:</td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right" class="Green2">{if $login ne "" or $config.General.apply_default_country eq "Y" or $cart.shipping_cost gt 0}{include file="currency.tpl" value=$shipping_cost}</td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$shipping_cost}{else}{$lng.txt_not_available_value}{assign var="not_logged_message" value="1"}</font></td><td>{/if}</td>
</tr>
{/if}

{if $cart.taxes and $config.Taxes.display_taxed_order_totals ne "Y"}
{foreach key=tax_name item=tax from=$cart.taxes}
<tr>
<td nowrap="nowrap"><font class="FormButton">{$tax.tax_display_name}{if $tax.rate_type eq "%"} {$tax.rate_value}%{/if}:</font></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{if $login ne "" or $config.General.apply_default_country eq "Y"}{include file="currency.tpl" value=$tax.tax_cost}</font></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$tax.tax_cost}{else}{$lng.txt_not_available_value}{assign var="not_logged_message" value="1"}</font></td><td>{/if}</td>
</tr>
{/foreach}
{/if}

{if $cart.payment_surcharge}
<tr>
<td nowrap="nowrap"><font class="FormButton">{if $cart.payment_surcharge gt 0}{$lng.lbl_payment_method_surcharge}{else}{$lng.lbl_payment_method_discount}{/if}:</font></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.payment_surcharge}</font></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$cart.payment_surcharge}</td>
</tr>
{/if}

{if $cart.applied_giftcerts}
<tr>
<td nowrap="nowrap"><font class="FormButton">{$lng.lbl_giftcert_discount}:</font></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.giftcert_discount}</font></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$cart.giftcert_discount}</font></td>
</tr>
{/if}

<tr>
<td colspan="4" height="1"><img src="{$ImagesDir}/spacer_black.gif" width="100%" height="1" alt="" /><br /></td>
</tr>

<tr>
<td nowrap="nowrap"><font class="ProductPriceSmall" style="text-transform: uppercase;">{$lng.lbl_grand_total}:</font></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.total_cost}</font></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$cart.total_cost}</td>
</tr>

{if $cart.taxes and $config.Taxes.display_taxed_order_totals eq "Y"}

{*
<tr>
<td colspan="4" align="right"><b>{$lng.lbl_including}:</b></td>
</tr>
*}

{foreach key=tax_name item=tax from=$cart.whole_taxes}
<tr class="TableSubHead">
<td nowrap="nowrap" align="left"><B>Including {$tax.rate_value}% {$tax.tax_display_name}:</B></td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><B>{include file="currency.tpl" value=$tax.tax_cost}</B></td>
<td nowrap="nowrap" align="right">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$tax.tax_cost}</td>
</tr>
{/foreach}

{/if}


</table>
{if $cart.applied_giftcerts}
<br />
<br />
<font class="FormButton">{$lng.lbl_applied_giftcerts}:</font>
<br />
{section name=gc loop=$cart.applied_giftcerts}
{$cart.applied_giftcerts[gc].giftcert_id} <a href="cart.php?mode=unset_gc&amp;gcid={$cart.applied_giftcerts[gc].giftcert_id}{if $smarty.get.paymentid}&amp;paymentid={$smarty.get.paymentid}{/if}"><img src="{$ImagesDir}/clear.gif" width="11" height="11" border="0" valign="top" alt="{$lng.lbl_unset_gc|escape}" /></a> : <font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.applied_giftcerts[gc].giftcert_cost}</font><br />
{/section}
{/if}

{*
{if $not_logged_message eq "1"}{$lng.txt_order_total_msg}{/if}
*}

{if !$no_form_fields}
<input type="hidden" name="paymentid" value="{$smarty.get.paymentid|escape:"html"}" />
<input type="hidden" name="mode" value="{$smarty.get.mode|escape:"html"}" />
<input type="hidden" name="action" value="update" />
{/if}
</div>
{if $active_modules.Special_Offers ne ""}
<hr align="left" noshade="noshade" size="1" />
{include file="modules/Special_Offers/customer/cart_bonuses.tpl"}
{/if}
