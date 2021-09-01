{* $Id: checkout_notes.tpl,v 1.6 2005/12/07 14:07:21 max Exp $ *}
{* {include file="customer/main/subheader.tpl" title=$lng.txt_notes class="grey"} *}
<table cellspacing="0" cellpadding="0" align="center">
<tr valign="top">
{*
	<td><b>{$lng.lbl_customer_notes}:</b></td>
	<td>&nbsp;</td>
*}
	<td nowrap="nowrap"><textarea cols="70" rows="10" name="Customer_Notes">{if ($customer_notes)}{$customer_notes}{/if}</textarea></td>
	</tr>
</table>
