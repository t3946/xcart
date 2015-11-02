{* $Id: bonus_discount.tpl,v 1.5 2006/02/01 14:47:59 mclap Exp $ *}

<table>
<tr>
	<td>{$lng.lbl_sp_bonus_discount_amount}:</td>
	<td>{$bonus.amount_min|string_format:"%.2f"} {$bonus.amount_type}</td>
</tr>
{if $bonus.amount_max gt 0}
<tr>
	<td>{$lng.lbl_sp_bonus_discount_limit}:</td>
	<td>{$bonus.amount_max|string_format:"%.2f"}</td>
</tr>
{/if}
</table>
{if $bonus.params ne ""}
{$lng.lbl_sp_bonus_apply_to_list}
<table>
<tr>
	<td width="1%">&nbsp;&nbsp;&nbsp;</td>
	<td>{include file="modules/Special_Offers/view/product_n_category.tpl" params=$bonus.params}</td>
</tr>
</table>
{/if}
