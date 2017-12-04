{*
$Id: order_reports.tpl, v 1.0.0 2010/04/12 18:25:21 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
{include file="page_title.tpl" title="Operators activity report"}
<br /><br />
{if ($mode ne "report") }

{*<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>*}

{include file="main/include_js.tpl" src="reset.js"}
<script type="text/javascript">
<!--
var searchform_def = [
	['posted_data[date_period]', '{$search_prefilled.date_period}'],
	['StartDay', '{$search_prefilled.start_date|default:$smarty.now|date_format:"%d"}'],
	['StartMonth', '{$search_prefilled.start_date|default:$smarty.now|date_format:"%m"}'],
	['StartYear', '{$search_prefilled.start_date|default:$smarty.now|date_format:"%Y"}'],
	['EndDay', '{$search_prefilled.end_date|default:$smarty.now|date_format:"%d"}'],
	['EndMonth', '{$search_prefilled.end_date|default:$smarty.now|date_format:"%m"}'],
	['EndYear', '{$search_prefilled.end_date|default:$smarty.now|date_format:"%Y"}']
];
{literal}
function managedate(status) {
	var fields = ['StartDay','StartMonth','StartYear','EndDay','EndMonth','EndYear', 'posted_data[start_date]', 'posted_data[end_date]'];
	for (i in fields)
		if (document.searchform.elements[fields[i]])
			document.searchform.elements[fields[i]].disabled = status;
}
{/literal}
-->
</script>

{capture name=dialog}

<form name="searchform" action="operators_activity_reports.php" method="post">
<input type="hidden" name="mode" value="" />

<table cellpadding="1" cellspacing="5" width="100%">

<tr>
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_date_period}:</td>
	<td width="10">&nbsp;</td>
	<td>
<table cellpadding="0" cellspacing="0">
<tr>
{*
	<td width="5"><input type="radio" id="date_period_null" name="posted_data[date_period]" value=""{if $search_prefilled eq "" or $search_prefilled.date_period eq ""} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
*}
	<td width="5"><input type="radio" id="date_period_null" name="posted_data[date_period]" value=""{if $search_prefilled.date_period eq ""} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
	<td class="OptionLabel"><label for="date_period_null">{$lng.lbl_all_dates}</label></td>

	<td width="5"><input type="radio" id="date_period_M" name="posted_data[date_period]" value="M"{if $search_prefilled.date_period eq "M" || ($search_prefilled eq "")} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
	<td class="OptionLabel"><label for="date_period_M">{$lng.lbl_this_month}</label></td>

	<td width="5"><input type="radio" id="date_period_W" name="posted_data[date_period]" value="W"{if $search_prefilled.date_period eq "W"} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
	<td class="OptionLabel"><label for="date_period_W">{$lng.lbl_this_week}</label></td>

	<td width="5"><input type="radio" id="date_period_D" name="posted_data[date_period]" value="D"{if $search_prefilled.date_period eq "D"} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
	<td class="OptionLabel"><label for="date_period_D">{$lng.lbl_today}</label></td>
</tr>
<tr>
	<td width="5"><input type="radio" id="date_period_C" name="posted_data[date_period]" value="C"{if $search_prefilled.date_period eq "C"} checked="checked"{/if} onclick="javascript:managedate(false)" /></td>
	<td colspan="7" class="OptionLabel"><label for="date_period_C">{$lng.lbl_specify_period_below}</label></td>
</tr>
</table>
</td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap">Report date from:</td>
	<td width="10">&nbsp;</td>
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

{*
<input id="id_start_date" type="text" size="9" name="posted_data[start_date]" value="{$search_prefilled.start_date}" />
*}
<input id="id_start_date" type="text" size="11" name="posted_data[start_date]" value="" />

{*
	{html_select_date prefix="Start" time=$search_prefilled.start_date start_year=$config.Company.start_year end_year=$config.Company.end_year}
*}

	</td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap">Report date to:</td>
	<td width="10">&nbsp;</td>
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

{*
<input id="id_end_date" type="text" size="9" name="posted_data[end_date]" value="{$search_prefilled.end_date}" />
*}

<input id="id_end_date" type="text" size="11" name="posted_data[end_date]" value="" />

{*
	{html_select_date prefix="End" time=$search_prefilled.end_date start_year=$config.Company.start_year end_year=$config.Company.end_year display_days=yes}
*}
	</td>
</tr>


<tr> 
	<td class="FormButton" nowrap="nowrap">Operators:</td>
	<td width="10">&nbsp;</td>
	<td>
  <select name="posted_data[operators][]" multiple="multiple" size="10">
  {foreach from=$operators item=mnf key=mid}
    <option value="{$mid}">{$mnf[0].firstname}</option>
  {/foreach}
  </select>
		{if $search_prefilled.date_period ne "C"}
			<script type="text/javascript" language="JavaScript 1.2">
				<!--
                managedate(true);
                -->
			</script>
		{/if}
	</td>
</tr>
<tr><td style="text-align:center;" colspan="3">
	<input type="submit" value="Submit" onclick="javascript: document.searchform.mode.value=''; document.searchform.target='_blank'; document.searchform.submit();" />
	</td>
</tr>
</table>

{/capture}
{include file="dialog.tpl" title="Operators activity report" content=$smarty.capture.dialog extra='width="100%"'}

{elseif $mode eq "report"}

	<h2 style="text-align: center;">
	{if $data.date_period ne ''}
		{$lng.lbl_from} {$data.start_date|date_format:"%d-%b-%Y"} {$lng.lbl_to} {$data.end_date|date_format:"%d-%b-%Y"}
	{else}
		{$lng.lbl_all_dates}
	{/if}
	</h2>
	{literal}
		<style>
			.reporttable {
				border-collapse: collapse;
				border-spacing: 0;
				margin-bottom: 10px;
				width: 100%;
				cursor: pointer;
				background-color: white;
				text-align: center;
			}
			.reporttable a {
				color: #140bfc;
			}

			.reporttable th {
				border: 1px solid #8cacbb;
				padding: 0.3em 0.7em;
				background: #eee none repeat scroll 0 0;
				text-transform: uppercase;
			}

			.reporttable tr {
				height: 25px;
			}

			tr.secondlevel th {
				background-color: #f4cccc;
			}

			tr.level3 th {
				background-color: #d9ead3;
			}

			.reporttable td {
				border: 1px solid #8cacbb;
				padding: 0.3em 0.7em;
			}
			.reporttable.level3 td {
				border-top: 0;
				border-right: 0;
				border-left: 0;
				border-bottom: 1px solid #8cacbb;
			}

			.crosscell {
				text-align: center;
				font-weight: bold;
			}

			.hidden {
				display: none;
			}

			.firstcell {
				width: 15px;
			}


		</style>
	{/literal}
	<table id="reporttbl" class="reporttable">
		<tr>
			<th class="firstcell"></th>
			<th>Operator</th>
			<th>Orders</th>
			<th>Actions</th>
		</tr>
	{if $firstlevelGroup}
	{foreach from=$firstlevelGroup item=row}
		<tr>
			<td class="crosscell"><img src="/skin1_kolin/images/plus.gif" /></td>
			<td>{$row.login}</td>
			<td>{$row.orderscount}</td>
			<td>{$row.actioncount}</td>
		</tr>
		<tr class="hidden"><td colspan="4">
				<table class="reporttable">
					<tr class="secondlevel">
						<th class="firstcell"></th>
						<th style="width:50px;">Order number</th>
						<th style="width:100px;">Order date</th>
						<th>Order statuses</th>
						<th>Actions</th>
					</tr>
					{foreach from=$secondlevelGroup[$row.login] item=row2}
					<tr>
						<td class="crosscell"><img src="/skin1_kolin/images/plus.gif" /></td>
						<td><a target="_blank" href="/admin/order.php?orderid={$row2.ordernumber}&tab=main_order_tabs-logs&tab2=order_tabs-1">{$row2.ordernumberwithprefix}</a></td>
						<td>{$row2.orderdate|date_format:'%d-%b-%Y<br />%H:%M:%S'}</td>
						<td><a target="_blank" href="{$row2.otrsticket}">{$row2.orderstatus}</a></td>
						<td>{$row2.actioncount}</td>
					</tr>
						<tr class="hidden"><td style="width:200px; max-width:500px; overflow: hidden;" colspan="5">
								<table class="reporttable level3">
									<tr class="level3">
										<th>Type</th>
										<th>Date</th>
										<th>Name</th>
										<th>Action / Log</th>
									</tr>
									{foreach from=$LevelGroup3[$row.login][$row2.ordernumber] item=row3}
										<tr>
											<td>{$type_names[$row3.action_type]}</td>
											<td>{$row3.action_date|date_format:'%d-%b-%Y<br />%H:%M:%S'}</td>
											<td>{$row.login}</td>
											<td style="text-align: left;">{$row3.log}</td>
										</tr>
									{/foreach}

								</table>
							</td></tr>
					{/foreach}
				</table>
			</td></tr>
	{/foreach}
	{else}
		<tr><td colspan="4">No data found</td></tr>
	{/if}
	</table>
	{literal}
	<script type="text/javascript">
		$( document ).ready(function() {
			$("#reporttbl .crosscell").click(
					function() {
						if ($(this).hasClass("opened")) {
							$(this).removeClass("opened").html('<img src="/skin1_kolin/images/plus.gif" />');
							$(this).parents("tr").next(".hidden", 1).hide();
						} else {
							$(this).addClass("opened").html('<img src="/skin1_kolin/images/minus.gif" />');
							$(this).parents("tr").next(".hidden").show();
						}
					});
		});
	</script>
	{/literal}
{/if}