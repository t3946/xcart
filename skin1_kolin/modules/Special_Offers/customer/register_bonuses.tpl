{* $Id: register_bonuses.tpl,v 1.5 2006/03/28 08:21:09 max Exp $ *}

{if $hide_header eq ""}
<tr>
	<td height="20" colspan="3"><b>{$lng.lbl_sp_customer_bonuses}</b><hr class="Line" size="1" /></td>
</tr>
{/if}

<tr>
	<td align="right">{$lng.lbl_sp_earned_bonus_points}</td>
	<td>&nbsp;</td>
	<td nowrap="nowrap">
<input type="text" name="bonus_points" size="6" maxlength="10" value="{$bonus.points}" />
	</td>
</tr>
