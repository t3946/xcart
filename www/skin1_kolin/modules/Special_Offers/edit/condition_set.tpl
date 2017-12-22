{* $Id: condition_set.tpl,v 1.8 2005/12/07 14:07:32 max Exp $ *}
{if $condition.params eq ""}
{$lng.txt_sp_empty_params_generic_edit}
{else}
<table cellpadding="3" cellspacing="1">
<tr valign="middle">
	<td>{$lng.lbl_sp_condition_set_action}:</td>
	<td>
	<select name="condition[{$condition.condition_type}][amount_type]">
		<option value="E"{if $condition.amount_type eq "E"} selected="selected"{/if}>{$lng.lbl_sp_action_one}</option>
		<option value="N"{if $condition.amount_type eq "N"} selected="selected"{/if}>{$lng.lbl_sp_action_copy}</option>
	</select>
	</td>
</tr>
</table>
<br />
{/if}
{include file="modules/Special_Offers/edit/product_n_category.tpl" params=$condition.params mainid=$condition.condition_type form_name="wizardform" with_qnty="Y" join_type="and"}
