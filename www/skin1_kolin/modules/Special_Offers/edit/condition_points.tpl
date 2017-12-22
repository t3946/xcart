{* $Id: condition_points.tpl,v 1.6.2.1 2006/05/03 13:45:02 max Exp $ *}
<table cellpadding="3" cellspacing="1">
<tr valign="top">
	<td>{$lng.lbl_sp_min_amount}:</td>
	<td><input type="text" size="5" name="condition[{$condition.condition_type}][amount_min]" value="{$condition.amount_min|string_format:"%d"}" /></td>
</tr>
<tr valign="top">
	<td>{$lng.lbl_sp_max_amount}:</td>
	<td>
<input type="text" size="5" name="condition[{$condition.condition_type}][amount_max]" value="{$condition.amount_max|string_format:"%d"}" />
<br />
{$lng.lbl_sp_max_amount_note|substitute:"zero":0}
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value=" {$lng.lbl_update} "></td>
</tr>
</table>
