{* $Id: bonuses_view.tpl,v 1.7 2005/12/07 14:07:31 max Exp $ *}
<table cellpadding="3" cellspacing="5" width="100%">
{if $bonus.points gt 0}
<tr>
	<td>

{include file="main/subheader.tpl" title=$lng.lbl_sp_ttl_bonus_points class="grey"}
<form action="bonuses.php" name="bonuspointsform" method="get">
<input type="hidden" name="mode" value="points" />

<table cellpadding="3" cellspacing="2" width="100%">
<tr>
	<td align="right" width="50%">{$lng.lbl_sp_earned_bonus_points}:</td>
	<td><b>{$bonus.points}</b></td>
</tr>
<tr>
	<td align="right">{$lng.lbl_sp_bonus_points_min_convert}:</td>
	<td><b>{$config.Special_Offers.offers_bp_min}</b></td>
</tr>
<tr>
	<td align="right">{$lng.lbl_sp_bonus_points_convert_rate}:</td>
	<td>
<b>{include file="currency.tpl" value=$config.Special_Offers.offers_bp_rate}</b> {include file="customer/main/alter_currency_value.tpl" alter_currency_value=$config.Special_Offers.offers_bp_rate}
	</td>
</tr>
<tr>
	<td colspan="2" align="center">

<br />

{if $js_enabled}
{include file="buttons/button.tpl" button_title=$lng.lbl_sp_bonus_points_convert2gc style="button" type="input" href="javascript: document.bonuspointsform.submit()" js_to_href="Y"}
{else}
{include file="submit_wo_js.tpl" value=$lng.lbl_sp_bonus_points_convert2gc note="off"}
{/if}

	</td>
</tr>
</table>
</form>

	</td>
</tr>
{/if}

{if $bonus.memberships ne ''}
<tr>
	<td>

{include file="main/subheader.tpl" title=$lng.lbl_membership class="grey"}
<form action="bonuses.php" name="bonusmembershipform" method="post">
<input type="hidden" name="mode" value="membership" />

<table cellpadding="3" cellspacing="2" width="100%">
<tr>
	<td align="right" width="50%">{$lng.lbl_sp_change_membership}:</td>
	<td>
	<select name="change_to">
{foreach from=$bonus.memberships item=membership key=membershipid}
		<option value="{$membershipid}">{$membership.membership|escape}</option>
{/foreach}
	</select>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
<br />

{if $js_enabled}
{include file="buttons/button.tpl" button_title=$lng.lbl_change style="button" type="input" href="javascript: document.bonusmembershipform.submit()" js_to_href="Y"}
{else}
{include file="submit_wo_js.tpl" value=$lng.lbl_change note="off"}
{/if}

	</td>
</tr>
</table>
</form>

	</td>
</tr>
{/if}
</table>
