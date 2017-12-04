{* $Id: checks_deposited.tpl,v 1.15.2.3 2006/11/21 14:12:09 max Exp $ *}
{include file="page_title.tpl" title="Checks deposited"}

{capture name=dialog}

<span style="font-size: .95px; font-weight: bold;">List of deposits</span>

<form action="checks_deposited.php" method="post" name="checks_depositedform">
<input type="hidden" name="mode" value="" />

<table cellpadding="3" cellspacing="1">

<tr class="TableHead">
        <td width="100">Deposit date</td>
        <td width="50">Currency</td>
        <td width="130" nowrap="nowrap">Deposit amount</td>
        <td width="150" nowrap="nowrap">Deposit status</td>
</tr>

{if $checks_deposited}
{foreach from=$checks_deposited item=v key=k}

<tr{cycle name="embed" values=", class='TableSubHead'"}>
<td align="center">{$v.date|date_format:'%d-%b-%Y'}</td>
<td align="center">{$v.currency}</td>
<td align="right"><a href="checks_deposited.php?checks_deposited_id={$v.checks_deposited_id}">{$v.total_deposit_amount}</a></td>
<td align="center">{if $v.status eq "P"}<I>{/if}{$deposite_statuses[$v.status]}{if $v.status eq "P"}</I>{/if}</td>
</tr>

{/foreach}
{/if}

<tr>
	<td colspan="3" class="SubmitBox">
	<input type="button" value="Add new deposit" onclick="javascript: self.location='checks_deposited.php?checks_deposited_id=';" />
	</td>
</tr>

</table>

<br />
<hr />
<span style="font-size: .95rem; font-weight: bold;">Unfreeze operation</span>
<br />
Unfreeze C2B payment status for order # <input type="text" name="unfreeze_orderid" value="" size="9" />
<br />
<input type="button" value="Do it" onclick="javascript: submitForm(this, 'unfreeze_order');" />

</form>

{/capture}
{include file="dialog.tpl" title="Checks deposited" content=$smarty.capture.dialog extra='width="100%"'}

