{* $Id: product_prices.tpl,v 1.9.2.2 2005/01/19 13:20:22 svowl Exp $ *}
{if $product_wholesale ne ""}
<BR>
<TABLE border="0" cellpadding="2" cellspacing="0" style="border-top: 1px solid black; border-left: 1px solid black;">
<TR bgcolor="#ffffff">
{section name=wi loop=$product_wholesale}
{if $smarty.section.wi.first}<TD align="right"  style="border-right: 1px solid #000000; color: #006500; border-bottom: 1px solid black;font-size: 13px;" height="25">&nbsp;{$lng.lbl_quantity}:&nbsp;</TD>{/if}
<TD style="color: #006500; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 13px;" align="center">&nbsp;{$product_wholesale[wi].quantity}{if $smarty.section.wi.last}+{else}-{$product_wholesale[wi].next_quantity}{/if}{*&nbsp;{if $product_wholesale[wi].quantity eq "1"}{$lng.lbl_item}{else}{$lng.lbl_items}{/if}*}&nbsp;</TD>
{/section}
</TR>
{if $product.taxes}
{capture name=taxdata}
{include file="customer/main/taxed_price.tpl" taxes=$product.taxes display_info="N"}
{/capture}
{/if}
<TR bgcolor="#ffffff">
{section name=wi loop=$product_wholesale}
{if $smarty.section.wi.first}<TD align="right" style="border-right: 1px solid black; color: #CD3335; border-bottom: 1px solid black; font-size: 13px;" height="25">{$lng.lbl_price}{if $smarty.capture.taxdata}*{/if}:&nbsp;</TD>{/if}
<TD style="color: #CD3335; border-right: 1px solid #000000; border-bottom: 1px solid black; font-size: 13px;" height="20" align="center"><SPAN id="wp{%wi.index%}">&nbsp;{include file="currency2.tpl" value=$product_wholesale[wi].taxed_price}&nbsp;</SPAN></TD>
{/section}
</TR>
</TABLE>
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
{/if}
