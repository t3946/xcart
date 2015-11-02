{*
$Id: order_report_html.tpl, v 1.0.0 2010/04/14 11:55:42 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>{$lng.txt_site_title}</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/skin1_admin.css" />
</head>
<body class="OrderReport">
<table cellpadding="3" cellspacing="1" class="OrderSheet">
<tr>
  <td class="OrderSheetCell" colspan="2" style="text-align: right;">{$lng.lbl_manufacturers}:</td>
  <td class="OrderSheetCell" colspan="10" style="text-align: left; font-weight: bold;">
  {foreach from=$manufacturers item=mnf name=mnf_loop}{if !$smarty.foreach.mnf_loop.first}, {/if}{$mnf}{/foreach}
  </td>
</tr>
<tr>
  <td class="OrderSheetCell" colspan="2" style="text-align: right;">{$lng.lbl_report_period}:</td>
  <td class="OrderSheetCell" colspan="10" style="text-align: left; font-weight: bold;">
  {if $data.date_period ne ''}
  {$lng.lbl_from} {$data.start_date|date_format:"%d-%b-%Y"} {$lng.lbl_to} {$data.end_date|date_format:"%d-%b-%Y"}
  {else}
  {$lng.lbl_all_dates}
  {/if}
  </td>
</tr>
<tr>
  <td class="OrderSheetCell" colspan="12">&nbsp; </td>
</tr>
{assign var="cycle_state" value="first"}
{foreach from=$orders item=order}
{include file="main/order_accounting_table.tpl" order=$order static='R' cycle_state=$cycle_state}
{assign var="cycle_state" value="continue"}
{/foreach}
</table>
</body>
</html>
