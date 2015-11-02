{* $Id: dialog.tpl,v 1.25 2005/12/20 08:50:49 max Exp $ *}
{if $printable ne ''}
{include file="dialog_printable.tpl"}
{else}
<table cellspacing="0" {$extra}>
<tr> 
<td class="DialogTitle">{if $align eq 'center'}<center>{$title}</center>{else}{$title}{/if}</td>

{if $product ne "" and $save_label eq "true"}
{if $product.taxed_price gt 0 and $product.list_price gt 0}
	<td align="right" valign="center" style="padding-right: 10px;" id="save_percent_box"{if $product.taxed_price >= $product.list_price} style="display: none;"{/if}>

<TABLE border="0" cellpadding="0" cellspacing="0">
<TR><TD nowrap height="20" style="background-color: #cc3333; color: white; font-size: 15px; font-weight: bold;" align="center">
&nbsp;SAVE&nbsp;{math equation="100-(price/lprice)*100" price=$product.taxed_price lprice=$product.list_price format="%3.0f" assign=discount}<SPAN id="save_percent">{$discount}</SPAN>%&nbsp;
</TD></TR>
</TABLE>

	</td>
{/if}
{/if}

</tr>
<tr><td colspan="2" class="DialogBorder"><table cellspacing="1" class="DialogBox">
<tr><td class="DialogBox" valign="{$valign|default:"top"}">{$content}
</td></tr>
</table></td></tr>
</table>
{/if}
