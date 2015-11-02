{* $Id: dialog.tpl,v 1.25 2005/12/20 08:50:49 max Exp $ *}
{if $printable ne ''}
{include file="dialog_printable.tpl"}
{else}
{if $title || $product}

{if $new_design eq "Y"}

<table cellspacing="0" {$extra} style="margin-top: -10px;">
<tr>
<td class="DialogTitle valign-top" style="background-color: #FEF6F3;">
{if $new_href ne ""}<a href="{$new_href}">{/if}<B>{$title}</B>{if $new_href ne ""}</a>{/if}
</td>
</tr>
</table>


{else}


<table cellspacing="0" {$extra} {if $usertype eq "C"}style="margin-top: -10px;"{/if}>
<tr>

{if $use_h1 eq "Y"}
<td {if $usertype eq "C"}style="background-color: #FEF6F3;"{/if} class="DialogTitle valign-top">{if $align eq 'center'}<center><h1>{$title}</h1></center>{else}<h1>{$title}</h1>{/if}</td>
{else}
 <td {if $usertype eq "C"}style="background-color: #FEF6F3;"{/if} class="DialogTitle valign-top">{if $align eq 'center'}<center><b>{$title}</b></center>{else}<b>{$title}</b>{/if}</td> 
{/if}

{*
{if $product ne "" and $save_label eq "true" and $current_price gt 0 and $product.list_price gt 0 and $product.list_price gt $current_price}
{math equation="100-(price/lprice)*100" price=$current_price lprice=$product.list_price format="%3.5f" assign=discount}
{if $discount gte 1}
<td align="right" valign="center" style="padding-right: 10px; background-color: #FEF6F3;" id="save_percent_box">
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR><TD nowrap height="20" style="background-color: #cc3333; color: white; font-size: 15px; font-weight: bold;" align="center">
&nbsp;SAVE&nbsp;<SPAN id="save_percent">{$discount|string_format:"%3.0f"}</SPAN>%&nbsp;
</TD>
<TD nowrap="nowrap">
&nbsp;<font style="font-size: 16px; color: #CC3333;">off List!</font>
</TD>
</TR>
</TABLE>
</td>
{/if}
{/if}
*}

</tr>
</table>

{/if}

{/if}
{if $product_sku ne "" || $product_free_ship ne ""}
<table cellspacing="0" {$extra}>
<tr>
<td class="DialogTitle valign-top">
{if $product_sku ne ""}<font color="#006600" class="DialogTitleT">{$lng.lbl_sku}: {$product_sku}</font>{* <br /> *}{/if}
</td>
{if $product_free_ship ne ""}
<td align="right" valign="center" nowrap="nowrap" style="padding-right: 10px;">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td height="20" style="background-color: #006600; color: white; font-size: 15px; font-weight: bold; padding-left: 4px; padding-right: 4px;" align="center">
{$product_free_ship}
</td>
</tr>
</table>
</td>
{/if}
</tr>
</table>
{/if}
<table cellspacing="0" {$extra}>
<tr><td colspan="2" class="DialogBorder"><table cellspacing="1" class="DialogBox">
<tr><td class="DialogBox" valign="{$valign|default:"top"}">{$content}
</td></tr>
</table></td></tr>
</table>
{/if}
