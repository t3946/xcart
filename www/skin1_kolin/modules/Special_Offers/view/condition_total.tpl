{* $Id: condition_total.tpl,v 1.5 2005/12/07 14:07:32 max Exp $ *}

<table cellpadding="3" cellspacing="1">
<tr valign="top">
	<td>{$lng.lbl_sp_min_amount}:</td>
	<td>{$condition.amount_min|string_format:"%.2f"}</td>
</tr>
{if $condition.amount_max gt 0}
<tr valign="top">
	<td>{$lng.lbl_sp_max_amount}:</td>
	<td>{$condition.amount_max|string_format:"%.2f"}</td>
</tr>
{/if}
</table>
