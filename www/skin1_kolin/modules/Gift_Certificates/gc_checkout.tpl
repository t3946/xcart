{* $Id: gc_checkout.tpl,v 1.10 2005/11/17 06:55:46 max Exp $ *}
{if $cart.giftcerts ne ""}
{section name=giftcert loop=$cart.giftcerts}
<tr>
<td colspan="6" class="DialogTitle">
<br />
<b>{$lng.lbl_gift_certificate}</b></td>
</tr>
<tr {$trstyle}>
<td style="width: 110px">&nbsp;</td>
<td>{$lng.lbl_gc_for} {$cart.giftcerts[giftcert].recipient|truncate:30:"...":true}</td>
{if $cart.display_cart_products_tax_rates eq "Y"}
<td>&nbsp;</td>
{/if}
<td class="ProductPriceSmall font-black" align="right">{include file="currency.tpl" value=$cart.giftcerts[giftcert].amount}</td>
<td class="ProductPriceSmall font-black" align="right">1</td>
<td class="ProductPriceSmall font-black" align="right">{include file="currency.tpl" value=$cart.giftcerts[giftcert].amount}</td>
</tr>
<tr {$trstyle}>
    <td class="Green2">{$lng.lbl_shipping}</td>
    <td class="Green2">
        {if $cart.giftcerts[giftcert].send_via eq "E"}
        {$lng.lbl_email}
        {elseif $cart.giftcerts[giftcert].send_via eq "P"}
        {$lng.lbl_gc_postal_mail}
        {/if}
    </td>
    {if $cart.display_cart_products_tax_rates eq "Y"}
    <td>&nbsp;</td>
    {/if}
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="Green2" align="right">{include file="currency.tpl" value=0}</td>
</tr>
<tr {$trstyle}>
<td>&nbsp;</td>
<td>&nbsp;</td>
{if $cart.display_cart_products_tax_rates eq "Y"}<td>&nbsp;</td>{/if}
<td nowrap="nowrap" class="ProductPriceSmall" align="right">
<b>{$lng.lbl_subtotal}:</b>
</td>
<td>&nbsp;</td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall">{include file="currency.tpl" value=$cart.giftcerts[giftcert].amount}</font></td>
</tr>
{/section}
{/if}
