{*
$Id: order_reports.tpl, v 1.0.0 2010/04/12 18:25:21 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
{include file="page_title.tpl" title=$lng.lbl_order_reports}

<br /><br />

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
	var fields = ['StartDay','StartMonth','StartYear','EndDay','EndMonth','EndYear'];
	for (i in fields)
		if (document.searchform.elements[fields[i]])
			document.searchform.elements[fields[i]].disabled = status;
}
{/literal}
-->
</script>

{capture name=dialog}

<form name="searchform" action="order_reports.php" method="post">
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
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_order_date_from}:</td>
	<td width="10">&nbsp;</td>
	<td> 
	{html_select_date prefix="Start" time=$search_prefilled.start_date start_year=$config.Company.start_year end_year=$config.Company.end_year}
	</td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_order_date_through}:</td>
	<td width="10">&nbsp;</td>
	<td> 
	{html_select_date prefix="End" time=$search_prefilled.end_date start_year=$config.Company.start_year end_year=$config.Company.end_year display_days=yes}
	</td>
</tr>

<tr> 
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_manufacturers}:</td>
	<td width="10">&nbsp;</td>
	<td>
  <select name="posted_data[manufacturers][]" multiple="multiple" size="10">
  {foreach from=$manufacturers item=mnf key=mid}
    <option value="{$mid}"{if $mnf.selected} selected="selected"{/if}>{$mnf.manufacturer}</option>
  {/foreach}
  </select>
	</td>
</tr>

{*
<tr> 
	<td class="FormButton">{$lng.lbl_include_orders_profit_margin}:</td>
	<td width="10">&nbsp;</td>
	<td>
  <input type="checkbox" name="posted_data[include_margin_100]" value="Y"{if $search_prefilled.include_margin_100 eq 'Y' || !$search_prefilled} checked="checked"{/if} />
	</td>
</tr>
*}

{* --- *}
<tr>
  <td class="FormButton">Profit margin range:</td>
  <td width="10">&nbsp;</td>
  <td>

    <table>
    <tr><td><input type="radio" name="posted_data[profit_margin_range]" value="margin_100"{if $search_prefilled.profit_margin_range eq "margin_100" || $search_prefilled.profit_margin_range eq ""} checked="checked"{/if} /></td><td>Show all orders (look at sales volume)</td></tr>

    <tr><td><input type="radio" name="posted_data[profit_margin_range]" value="margin_less_100"{if $search_prefilled.profit_margin_range eq "margin_less_100"} checked="checked"{/if} /></td><td>Show orders with profit margin &lt; 100 % (look at profit margin)</td></tr>

    <tr><td><input type="radio" name="posted_data[profit_margin_range]" value="margin_less_1"{if $search_prefilled.profit_margin_range eq "margin_less_1"} checked="checked"{/if} /></td><td>Show orders with profit margin ≤  <input type="text" name="posted_data[profit_margin_range_less_1]" value="{$search_prefilled.profit_margin_range_less_1|default:15}" size="3" />%</td></tr>

    <tr><td><input type="radio" name="posted_data[profit_margin_range]" value="margin_1_2"{if $search_prefilled.profit_margin_range eq "margin_1_2"} checked="checked"{/if} /></td><td>Show orders with <input type="text" name="posted_data[profit_margin_range_1]" value="30" size="3" />% ≤ profit margin &lt; <input type="text" name="posted_data[profit_margin_range_2]" value="100" size="3" />%</td></tr>
    </table>

  </td>
</tr>

<tr>
  <td class="FormButton">Include Fully refunded orders:</td>
  <td width="10">&nbsp;</td>
  <td><input type="checkbox" name="posted_data[cb_status]" value="R" checked="checked" /></td>
</tr>
{* --- *}

<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="3" class="SubmitBox">
	<input type="submit" value="{$lng.lbl_generate_html_report|strip_tags:false|escape}" onclick="javascript: document.searchform.mode.value=''; document.searchform.target='_blank'; document.searchform.submit();" />
	<input type="button" value="{$lng.lbl_generate_csv_report|strip_tags:false|escape}" onclick="javascript: document.searchform.mode.value='csv'; document.searchform.target=''; document.searchform.submit();" />

	<input type="button" value="Generate 'Time to dispatch' distribution" onclick="javascript: document.searchform.mode.value='generate_time_to_dispatch'; document.searchform.target='_blank'; document.searchform.submit();" />


{if $search_prefilled.date_period ne "C"}
<script type="text/javascript" language="JavaScript 1.2">
<!--
managedate(true);
-->
</script>
{/if}
	</td>
</tr>

</table>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_order_reports content=$smarty.capture.dialog extra='width="100%"'}
