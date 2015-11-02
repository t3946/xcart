{* $Id: cart_contents.tpl,v 1.24 2005/11/17 06:55:37 max Exp $ *}
<table cellpadding="5" cellspacing="1" width="100%">

<tr class="TableHead">
<td><b>{$lng.lbl_sku}</b></td>
<td><b>{$lng.lbl_product}</b></td>
{if $cart.display_cart_products_tax_rates eq "Y"}
<td align="center"><b>{if $cart.product_tax_name ne ""}{$cart.product_tax_name}{else}{$lng.lbl_tax}{/if}</b></td>
{/if}
<td align="right"><b>{$lng.lbl_price}</b></td>
<td align="right"><b>{$lng.lbl_qty}</b></td>
<td align="right"><b>{$lng.lbl_total}</b></td>
</tr>
{assign var="shipping_was_shown" value="N"}
{assign var="anyproducts_was_shown" value="N"}
{assign var="trstyle" value="class='TableSubHead'"}
{foreach from=$cart.shipping_groups item=v key=k}
{assign var="last_group_tax" value=""}
<tr><td colspan="6"><br /></td></tr>
<tr>
<td colspan="6" class="DialogTitle" style="background-color: #FEF6F3;">
{* <br /> *}
<b>{*{$v.group_name}*}{*{$v.manufact_text_displayed}*}{$lng.lbl_items_shipped_from_warehouse} {$v.m_city}, {$v.m_state_code}, {if $v.m_country_code eq "US"}USA{else}{$v.m_country}{/if}</b></td>
</tr>
{assign var="deliv_subt" value="0"}
{*cycle values="class='TableSubHead', " assign="trstyle" *}
{section name=prod_num loop=$products}
{if ($products[prod_num].manufacturerid eq $k and $products[prod_num].shipping_freight ne '0') or ($k eq $artss_manufacturerid and $products[prod_num].shipping_freight eq '0')}
{math equation="x+y" x=$deliv_subt y=$products[prod_num].display_subtotal  assign="deliv_subt"}
{if $shipping_was_shown eq 'Y' or $has_zero_freight_products eq 'N'}{/if}
{if $shipping_was_shown eq 'N' && $products[prod_num].shipping_freight ne 0 && $anyproducts_was_shown eq 'Y' && $has_zero_freight_products eq 'Y'}
<tr {$trstyle}>
<td class="Green2">
{foreach from=$shipping item=s}
{if $cart.shippingid eq $s.shippingid}{$s.shipping}{/if}
{/foreach}
</td>
<td class="Green2">{$lng.lbl_shipping_cost}</td>
{if $cart.display_cart_products_tax_rates eq "Y"}
<td align="center">&nbsp;</td>
{/if}
<td>&nbsp;</td>
<td>&nbsp;</td>
<td class="Green2" align="right">{include file="currency.tpl" value=$cart.display_shipping_cost}</td>
</tr>
{assign var="shipping_was_shown" value="Y"}
{/if}
<tr {$trstyle}>
<td style="width: 110px">{$products[prod_num].productcode}</td>
<td>{$products[prod_num].product}</td>
{if $cart.display_cart_products_tax_rates eq "Y"}
<td align="center">
{foreach from=$products[prod_num].taxes key=tax_name item=tax}
{if $cart.product_tax_name eq ""}<span style="white-space: nowrap;">{$tax.tax_display_name}:</span>{/if}
{if $tax.rate_type eq "%"}{$tax.rate_value}%{else}{include file="currency.tpl" value=$tax.rate_value}{/if}<br />
{/foreach}
{if $last_group_tax eq ""}{assign var="last_group_tax" value=$products[prod_num].taxes}{/if}
</td>
{/if}
<td class="ProductPriceSmall" style="color: black;" align="right">{include file="currency.tpl" value=$products[prod_num].display_price}</td>
{math equation="x*y" x=$products[prod_num].display_price y=$products[prod_num].amount assign="total"}
<td class="ProductPriceSmall" style="color: black;" align="right">{if $config.Appearance.allow_update_quantity_in_cart eq "N" or ($active_modules.Egoods and $products[prod_num].distribution) or ($active_modules.Subscriptions and $products[prod_num].sub_plan)}{$products[prod_num].amount}{else}{if $link_qty eq"Y"}{* <a href="cart.php"> *}{$products[prod_num].amount}{*</a>*}{else}<input type="text" size="3" name="productindexes[{$products[prod_num].cartid}]" value="{$products[prod_num].amount}" />{/if}{/if}</td>
<td class="ProductPriceSmall" style="color: black;"  align="right">{include file="currency.tpl" value=$total}</td>
</tr>
{assign var="anyproducts_was_shown" value="Y"}
{/if}
{/section}
<tr {$trstyle}>
{if $cart.groups_delivery[$k] ne ''}
<td class="Green2">{$lng.lbl_shipping}</td>
<td class="Green2">

{if $cart.groups_delivery[$k] eq "_USE_MY_UPS_FEDEX_ACCOUNT_"}

{$cart.ship_by_shipping_method[$k]} (charge to {$cart.use_my_account[$k]} account # {$cart.use_my_account_number[$k]})

{elseif $cart.groups_delivery[$k] eq "_SHIP_BY_FASTEST_METHOD_"}

The fastest possible shipping method

{else}
{$cart.groups_delivery[$k]|trademark:$insert_trademark}
{/if}

</td>
{if $cart.display_cart_products_tax_rates eq "Y"}
<td align="center">
{if $last_group_tax ne ""}
{foreach from=$last_group_tax key=tax_name item=tax}
{if $cart.product_tax_name eq ""}<span style="white-space: nowrap;">{$tax.tax_display_name}:</span>{/if}
{if $tax.rate_type eq "%"}{$tax.rate_value}%{else}{include file="currency.tpl" value=$tax.rate_value}{/if}<br />
{/foreach}
{/if}
</td>
{/if}
<td>&nbsp;</td>
<td>&nbsp;</td>
<td class="Green2" align="right">{include file="currency.tpl" value=$cart.display_shipping_costs[$k]}</td>
{assign var="deliv_subt" value=$cart.display_shipping_costs[$k]+$deliv_subt}
{else}
<td {if $cart.display_cart_products_tax_rates eq "Y"}colspan="6"{else}colspan="5"{/if}><font class="ErrorMessage">{$lng.lbl_no_shipping_for_location}</font></td>
{/if}
</tr>
<tr {$trstyle}>
<td>&nbsp;</td>
<td>&nbsp;</td>
{if $cart.display_cart_products_tax_rates eq "Y"}<td>&nbsp;</td>{/if}
<td nowrap="nowrap" class="ProductPriceSmall" align="right">
<b>{$lng.lbl_subtotal}:</b>
</td>
<td>&nbsp;</td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$deliv_subt}</font></td>
</tr>
{/foreach}
{if $active_modules.Gift_Certificates ne ""}
{include file="modules/Gift_Certificates/gc_checkout.tpl"}
{/if}
<tr><td colspan="6"><hr size="1" noshade="noshade" /></td></tr>
</table>
