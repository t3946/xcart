<br />
<a href="classification.php?mode=search">Back to Classification page</a><br />

<br />

{if $pc_runs_logs ne ""}

{if $mode eq "search"}
{if $total_items gt "0"}
{$lng.txt_N_results_found|substitute:"items":$total_items}<br />
{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
{else}
{$lng.txt_N_results_found|substitute:"items":0}
{/if}
{/if}
<br />
<br />

{capture name=dialog}

{include file="customer/main/navigation.tpl"}

<form name="sqform" action="classification_log.php" method="post">

<input type="hidden" name="mode" value="" id="mode" />

<table border="0" width="100%" cellpadding="3" cellspacing="1">
<tr class='TableSubHead'>
<td><B>RUN #</B></td>
<td><B>Operator name (username)</B></td>
<td><B>Date</B></td>
<td><B>Duration</B></td>
<td><B>Approved</B></td>
<td><B>Skipped</B></td>
<td><B>Assigned</B></td>
<td><B>Not Assigned</B></td>
<td><B>Total</B></td>
<td><B>Approval rate</B></td>
</tr>

{foreach from=$pc_runs_logs item=v key=k}

   <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>

	<td>{$v.run}</td>
	<td>{$v.firstname} ({$v.login})</td>
	<td>{$v.date_time_end|date_format:'%d-%b-%Y'}</td>
	<td>{$v.duration} min</td>
	<td>{$v.products_approved}</td>
	<td>{$v.products_skipped}</td>
	<td>{$v.products_assigned}</td>
	<td>{$v.products_incorrect_assigned}</td>
	<td>{$v.total}</td>
	<td>{$v.approval_rate}%</td>
   </tr>

{/foreach}

</table>

</form>

{/capture}
{include file="dialog.tpl" title="Classification logs" content=$smarty.capture.dialog extra='width="100%"'}

{else}
<br />Empty
{/if}
