{* $Id: condition_total.tpl,v 1.6.2.1 2006/05/03 12:19:45 max Exp $ *}
<table cellpadding="3" cellspacing="1">
<tr valign="top">
	<td>{$lng.lbl_sp_min_amount}:</td>
	<td><input type="text" size="8" name="condition[{$condition.condition_type}][amount_min]" value="{$condition.amount_min|default:0|formatprice}" /></td>
</tr>
<tr valign="top">
	<td>{$lng.lbl_sp_max_amount}:</td>
	<td>
<input type="text" size="8" name="condition[{$condition.condition_type}][amount_max]" value="{$condition.amount_max|default:0|formatprice}" />
<br />
{$lng.lbl_sp_max_amount_note|substitute:"zero":$zero}
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value=" {$lng.lbl_update} "></td>
</tr>
</table>
