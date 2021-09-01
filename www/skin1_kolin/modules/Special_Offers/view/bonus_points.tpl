{* $Id: bonus_points.tpl,v 1.5 2005/12/07 14:07:32 max Exp $ *}

<table>
{if $bonus.amount_type ne "S"}
<tr>
	<td>{$lng.lbl_sp_points_fixed_amount}:</td>
	<td>{$bonus.amount_min|string_format:"%d"}</td>
</tr>
{else}
<tr>
	<td>{$lng.lbl_sp_points_per_subtotal}:</td>
	<td>{$bonus.amount_min|string_format:"%d"} {$lng.lbl_sp_points_per_} {$config.General.currency_symbol}{$bonus.amount_max|string_format:"%d"} {$lng.lbl_sp_points_of_subtotal_}</td>
</tr>
{/if}
</table>
