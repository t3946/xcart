{include file="page_title.tpl" title="Fraud page"}

{capture name=dialog}

<form name="fraudform" action="fraud_page.php" method="post">

<input type="hidden" name="mode" value="" id="mode" />
<input type="hidden" name="orderid" value="{$orderid}" />

<table width="100%" style="background-color: #000000;" cellpadding="1" cellspacing="1">
<tr style="background-color: #cccccc;">
<td><B>Fraud check question</B></td>
<td><B>Manual action</B></td>
<td align="right"><B>Bare fraud score</B></td>
<td align="right"><B>Importance factor</B></td>
<td align="right"><B>Fraud score</B></td>
</tr>

{*
<tr>
<td colspan="6">
<hr />
</td>
</tr>
*}

{if $fraud_checks ne ""}
{foreach from=$fraud_checks item=item key=key}
<tr
{if $item.bare_fraud_score ne ""}

	{if $item.manual_action eq "" && $item.auto ne "Y"}
		style="background-color: #FFFFFF;"
	{else}
		{if $item.fraud_score gte 1}
			style="background-color: #00e679;"
		{else}
			style="background-color: #ff8600;"
		{/if}
	{/if}
{else}
	style="background-color: #FFFFFF;" 
{/if}
>
<td>
<I>Question code: {$item.question_code}</I><br />
{$item.question_template_body}
</td>
<td nowrap="nowrap">
<input type="hidden" name="posted_data[{$key}][question_code]" value="{$item.question_code}" />

{if $item.auto eq "Y"}
	Auto
{else}
	<input type="radio" name="posted_data[{$key}][manual_action]" value="Y"{if $item.manual_action eq "Y"} checked="checked"{/if} />Yes
	<br />
	<input type="radio" name="posted_data[{$key}][manual_action]" value="N"{if $item.manual_action eq "N"} checked="checked"{/if} />No
{/if}
</td>
<td nowrap="nowrap" align="right">{if $item.bare_fraud_score eq "" || ($item.auto ne "Y" && $item.manual_action eq "")}To be calculated{else}{$item.bare_fraud_score}{/if}</td>
<td nowrap="nowrap" align="right">{$item.importance_factor}</td>
<td nowrap="nowrap" align="right">{if $item.bare_fraud_score eq "" || ($item.auto ne "Y" && $item.manual_action eq "")}To be calculated{else}{$item.fraud_score}{/if}</td>
</tr>
{*
<tr><td colspan="6"><hr /></td></tr>
*}
{/foreach}
{/if}

<tr style="background-color: #FFFFFF;">
<td colspan="4" align="right"><B>Overall fraud score:</B></td>
<td align="right">{if $overall_fraud_score eq 0}0{else}{$overall_fraud_score|default:"To be calculated"}{/if}</td>
</tr>

{* <tr><td colspan="6"><hr /></td></tr> *}

<tr style="background-color: #FFFFFF;">
<td colspan="5" align="right"><B>Current fraud check status:</B> {include file="main/fraud_status.tpl" fraud_status=$order.fraud_status fraud_static="Y"}</td>
</tr>

<tr style="background-color: #FFFFFF;">
<td colspan="5" align="right"><B>Change fraud check status to:</B> {include file="main/fraud_status.tpl" fraud_status=$order.fraud_status}</td>
</tr>

{*
<tr><td colspan="6"><hr /></td></tr>
*}

<tr style="background-color: #FFFFFF;">
<td colspan="5">
<input type="button" value="Apply changes and update fraud scores" onclick="javascript: $('#mode').val('apply_changes_and_update_fraud_scores'); document.fraudform.submit();">
<input type="button" value="Don't apply changes and close this window" onclick="javascript: window.close();">
<input type="button" value="Apply changes, update fraud scores and change fraud check status" onclick="javascript: $('#mode').val('apply_changes_and_update_fraud_scores_and_change_fraud_check_status'); document.fraudform.submit();">
</td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title="Fraud check questions" content=$smarty.capture.dialog extra='width="100%"'}
