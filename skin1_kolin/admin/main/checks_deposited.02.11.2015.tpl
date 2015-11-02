{* $Id: checks_deposited.tpl,v 1.15.2.3 2006/11/21 14:12:09 max Exp $ *}
{include file="page_title.tpl" title="Checks deposited"}

{capture name=dialog}

<span style="font-size: 12px; font-weight: bold;">List of deposits</span>

<form action="checks_deposited.php" method="post" name="checks_depositedform">
<input type="hidden" name="mode" value="" />

<table cellpadding="3" cellspacing="1">

<tr class="TableHead">
        <td width="100">Deposit date</td>
        <td width="50">Currency</td>
        <td width="130" nowrap="nowrap">Deposit amount</td>
</tr>

{if $checks_deposited}
{foreach from=$checks_deposited item=v key=k}

<tr{cycle name="embed" values=", class='TableSubHead'"}>
<td align="center">{$v.date|date_format:'%d-%b-%Y'}</td>
<td align="center">{$v.currency}</td>
<td align="right"><a href="checks_deposited.php?checks_deposited_id={$v.checks_deposited_id}" target="_blank">{$v.total_deposit_amount}</a></td>
</tr>

{/foreach}
{/if}

<tr>
	<td colspan="3" class="SubmitBox">
	<input type="button" value="Add new deposit" onclick="javascript: self.location='checks_deposited.php?checks_deposited_id=';" />
	</td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title="Checks deposited" content=$smarty.capture.dialog extra='width="100%"'}

