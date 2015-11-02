<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>

<br />

{if $backprocess_logs ne ""}

<a href="backprocess_logs.php">Backprocess logs</a><br /><br />

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

<table border="0" width="100%" cellpadding="3" cellspacing="1">
<tr class='TableSubHead'>
<td width="10"><B>id</B></td>
<td width="10" nowrap="nowrap"><B>date</B></td>
<td width="10" nowrap="nowrap"><B>process_id</B></td>
<td width="*"><B>log_text</B></td>
</tr>

{assign var="tmp_counter" value=0}

{foreach from=$backprocess_logs item=v key=k}

   <tr {cycle values=", class='TableSubHead'"}>
	<td>{$v.id}</td>
	<td nowrap="nowrap">{$v.date|date_format:'%d-%b-%Y %H:%M:%S'}</td>
	<td nowrap="nowrap">{$v.process_id}</td>
	<td>{$v.log_text}</td>
   </tr>

{/foreach}

</table>

{/capture}
{include file="dialog.tpl" title="Shipping quotes log" content=$smarty.capture.dialog extra='width="100%"'}

{else}
<br />
<form name="sqform" action="backprocess_logs.php" method="post">
<input type="hidden" name="mode" value="search" id="mode" />

<table>
<tr>
        <td class="FormButton" nowrap="nowrap">From:</td>
        <td>
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_start_date").datepicker();
  });
{/literal}
-->
</script>

<input id="id_start_date" type="text" size="11" name="posted_data[start_date]" value="{if $search_data.start_date_str ne ""}{$search_data.start_date_str}{/if}" />
        </td>
        <td width="10">&nbsp;</td>
        <td class="FormButton" nowrap="nowrap">To:</td>
        <td>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_end_date").datepicker();
  });
{/literal}
-->
</script>

<input id="id_end_date" type="text" size="11" name="posted_data[end_date]" value="{if $search_data.end_date_str ne ""}{$search_data.end_date_str}{/if}" />

        </td>
	<td width="10">&nbsp;</td>
	<td width="10">process_id</td>
	<td>
          <select name="posted_data[process_id][]" {* multiple="multiple" size="5" *}>
		<option value="all">All</option>
	  {if $process_ids ne ""}
          {foreach from=$process_ids item=item key=key}
                <option value="{$item.process_id}">{$item.process_id}</option>
          {/foreach}
	  {/if}
          </select>
	</td>
	<td>
<input type="submit" name="submit" value="submit">
	</td>
</tr>
</table>

</form>
{/if}


