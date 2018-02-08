{* $Id: order_bonuses.tpl,v 1.4 2005/12/07 14:07:30 max Exp $ *}

<tr>
	<td colspan="2">{include file="main/subheader.tpl" title=$lng.lbl_sp_order_bonuses}</td>
</tr>

{if $bonuses.points ne 0}
<tr valign="top"> 
	<td>&nbsp;&nbsp;{$lng.lbl_sp_customer_bonus_points}</td>
	<td>{$bonuses.points}</td>
</tr>
{/if}

{if $bonuses.memberships ne ""}
<tr valign="top"> 
	<td>&nbsp;&nbsp;{$lng.lbl_sp_customer_bonus_memberships}</td>
	<td>
{foreach name=memberships from=$bonuses.memberships item=membership}
{$membership}{if $smarty.foreach.memberships.last ne "1"}, {/if}
{/foreach}
	</td>
</tr>
{/if}

<tr>
	<td colspan="2" height="10">&nbsp;</td>
</tr>
