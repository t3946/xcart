{* $Id: product_prices.tpl,v 1.9.2.2 2005/01/19 13:20:22 svowl Exp $ *}
{if $product_wholesale ne ""}
<TABLE border="0" cellpadding="2" cellspacing="0" style="border-top: 1px solid black; border-left: 1px solid black;" width="100%">

<tr bgcolor="#ffffff">
<td colspan="2" style="color: #000000;  border-right: 1px solid black; border-bottom: 1px solid black;" align="center" nowrap="nowrap" >
Discount table
</td>
</tr>

<tr bgcolor="#ffffff">
<td align="center" width="30%" style="color: #006500; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 13px;" nowrap="nowrap">Qty</td>
<td align="center"width="70%" style="color: #CD3335; border-right: 1px solid #000000; border-bottom: 1px solid black; font-size: 13px;" nowrap="nowrap">{$lng.lbl_price}</td>
</tr>

{if $product.taxes}
{capture name=taxdata}
{include file="customer/main/taxed_price.tpl" taxes=$product.taxes display_info="N"}
{/capture}
{/if}

{section name=wi loop=$product_wholesale}
<TR style="background: #ffffff;" id="wp_tr{%wi.index%}">
<TD nowrap="nowrap" id="wp_dt_l{%wi.index%}" style="font-weight: normal; color: #006500; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 13px;" align="center">&nbsp;{if $product_wholesale[wi].quantity lte "0"}1{else}{$product_wholesale[wi].quantity}{if $smarty.section.wi.last && $product_wholesale[wi].next_quantity eq "0"}{if $product.avail gt $product_wholesale[wi].quantity}+{/if}{else}{if $product.mult_order_quantity eq "Y" && $product.min_amount gt 1}{else}{if $product_wholesale[wi].quantity ne $product_wholesale[wi].next_quantity}-{$product_wholesale[wi].next_quantity}{/if}{/if}{/if}{/if}&nbsp;</TD>

<TD nowrap="nowrap" id="wp_dt_r{%wi.index%}" style="color: #CD3335; border-right: 1px solid #000000; border-bottom: 1px solid black; font-size: 13px;" height="20" align="center"><SPAN id="wp{%wi.index%}">&nbsp;{$config.General.currency_symbol}{include file="currency2.tpl" value=$product_wholesale[wi].taxed_price}&nbsp;</SPAN></TD>
</TR>
{/section}
</TABLE>

{*
{if $smarty.capture.taxdata}
<BR>
<TABLE border="0">
<TR>
<TD class="FormButton" valign="top">*{$lng.txt_note}:</B>&nbsp;</TD>
<TD nowrap valign="top">{$smarty.capture.taxdata}</TD>
</TR>
</TABLE>
{/if}
<BR>
*}
{/if}
