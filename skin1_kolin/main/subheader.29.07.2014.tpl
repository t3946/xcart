{* $Id: subheader.tpl,v 1.9 2005/12/07 14:07:27 max Exp $ *}
{if $class eq 'grey'}
<table cellspacing="0" class="SubHeaderGrey">
<tr>
	<td class="SubHeaderGrey">{$title}</td>
</tr>
<tr>
	<td class="SubHeaderGreyLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
</table>
{elseif $class eq "red"}
<table cellspacing="0" class="SubHeaderRed">
<tr>
	<td class="SubHeader">{$title}</td>
</tr>
<tr>
	<td class="SubHeaderRedLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /><br /></td>
</tr>
</table>
{elseif $class eq "black"}
<table cellspacing="0" class="SubHeaderBlack">
<tr>
	<td class="SubHeaderBlack">{$title}</td>
</tr>
<tr>
	<td class="SubHeaderBlackLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /><br /></td>
</tr>
</table>
{elseif $class eq "just_red_line"}
<table cellspacing="0" class="just_red_line">
<tr>
	<td>{$title}</td>
</tr>
<tr>
	<td class="SubHeaderLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /><br /></td>
</tr>
</table>
{else}
	{if $show_order_help_links eq "Y"}
                <table cellspacing="0" class="SubHeader">
                <tr>
                        <td class="Green2">{$title}</td>
			<td align="right">
{* ------------------ *}
<table cellspacing="0" cellpadding="0" class="ButtonsRow" border="0">
<tr>
<td class="ButtonsRow" align="right" style="padding-right: 0px; padding-left: 12px;">
<a target="_blank" style="color: #550000; font-weight: bold; TEXT-DECORATION: none;" href="order.php?orderid={$order.orderid}&mode=printable">{$lng.lbl_print_order}</a>
</td>
{if $active_modules.RMA ne '' && $current_membership_flag ne 'FS'}
{if ($usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $return_products ne ''}
<td class="ButtonsRow" align="right" style="padding-right: 0px; padding-left: 12px;">
<a target="_blank" style="color: #550000; font-weight: bold; TEXT-DECORATION: none;" href="#returns">{$lng.lbl_create_return}</a>
</td>
{/if}
{if ($usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $order.is_returns}
<td class="ButtonsRow" align="right" style="padding-right: 0px; padding-left: 12px;">
<a target="_blank" style="color: #550000; font-weight: bold; TEXT-DECORATION: none;" href="returns.php?mode=search&search[orderid]={$order.orderid}">{$lng.lbl_order_returns}</a>
</td>
{/if}
{/if}
{if $active_modules.Shipping_Label_Generator ne '' && ($usertype eq 'A' || $usertype eq 'P')}
<td class="ButtonsRow" align="right" style="padding-right: 0px; padding-left: 12px;">
<a target="_blank" style="color: #550000; font-weight: bold; TEXT-DECORATION: none;" href="generator.php?orderid={$order.orderid}">{$lng.lbl_shipping_label}</a>
</td>
{/if}

{if $order.refund_groups ne ""}
<td class="ButtonsRow" align="right" style="padding-right: 0px; padding-left: 12px;">
<a target="_blank" style="color: #550000; font-weight: bold; TEXT-DECORATION: none;" href="order.php?orderid={$order.orderid}&mode=invoice&action=incorrect">Print incorrect invoice</a>
</td>
{/if}

<td class="ButtonsRow" align="right" style="padding-right: 0px; padding-left: 12px;">
<a target="_blank" style="color: #550000; font-weight: bold; TEXT-DECORATION: none;" href="order.php?orderid={$order.orderid}&mode=invoice">{$lng.lbl_print_invoice target}</a>
</td>
{if ($usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode)) and $active_modules.Advanced_Order_Management}
<td class="ButtonsRow" align="right" style="padding-right: 0px; padding-left: 12px;">
<a target="_blank" style="color: #550000; font-weight: bold; TEXT-DECORATION: none;" href="order.php?orderid={$order.orderid}&mode=edit">{$lng.lbl_modify}</a>
</td>
{/if}
</tr>
</table>
{* ------------------ *}
			</td>
                </tr>
                <tr>
                        <td class="SubHeaderLine" colspan="2"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /><br /></td>
                </tr>
                </table>
	{else}
		<table cellspacing="0" class="SubHeader">
		<tr>
			<td class="Green2">{$title}</td>
		</tr>
		<tr>
			<td class="SubHeaderLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /><br /></td>
		</tr>
		</table>
	{/if}
{/if}

