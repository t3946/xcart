{* $Id: condition_membership.tpl,v 1.8 2005/12/07 14:07:32 max Exp $ *}

{$lng.txt_sp_empty_params_membership_edit}
<br />
<br />
<table>
<tr>
	<td>
	<select name="condition[{$condition.condition_type}][memberships][]" size="5" multiple="multiple">
{foreach from=$condition.memberships item=membership key=membershipid}
		<option value="{$membershipid}"{if $membership.selected } selected="selected"{/if}>{$membership.name|escape}</option>
{/foreach}
	</select>
	</td>
</tr>
<tr>
	<td><input type="submit" value=" {$lng.lbl_update} "></td>
</tr>
</table>
