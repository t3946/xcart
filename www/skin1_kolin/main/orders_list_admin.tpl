{*
$Id: orders_list_admin.tpl, v 1.0.0 2010/04/12 17:22:59 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}

{assign var="total" value=0.00}
{assign var="total_paid" value=0.00}

{if $orders ne ""}

{capture name=dialog}

{*
{if $current_membership_flag ne 'FS'}
<table width="100%">
<tr>
<td>
<table cellspacing="1" class="DataSheet" style="width: auto;">
<tr>
<td>{$cur_time|date_format:"%d-%b-%Y"}</td>
<td>{$lng.lbl_inventory_sales}</td>
<td>{include file="currency.tpl" value=$today_totals.ARTS|default:0}</td>
</tr>
<tr>
<td>{$cur_time|date_format:"%d-%b-%Y"}</td>
<td>{$lng.lbl_direct_ship_sales}</td>
<td>{include file="currency.tpl" value=$today_totals.other|default:0}</td>
</tr>
</table>
</td>
<td align="right" valign="top">
<div align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_search_again href="orders.php"}</div>
</td>
</tr>
</table>
<br />
{/if}
*}

{include file="main/subheader.tpl" title=$lng.lbl_search_results}

{include file="customer/main/navigation.tpl"}


{include file="main/check_all_row.tpl" form="processorderform" prefix="orderids"}


<form action="process_order.php" method="post" name="processorderform">
<input type="hidden" name="mode" value="" />
{*
<table cellpadding="8" cellspacing="0">
<tr>
	<td nowrap="nowrap" style="padding-left: 0;">{$lng.lbl_sort_by}:</td>
	<td nowrap="nowrap">{if $search_prefilled.sort_field eq "orderid"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="orders.php?mode=search&amp;sort=orderid">{$lng.lbl_order_id}</a></td>
	<td nowrap="nowrap">{if $search_prefilled.sort_field eq "customer"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="orders.php?mode=search&amp;sort=customer">{$lng.lbl_customer}</a></td>
	<td nowrap="nowrap">{if $search_prefilled.sort_field eq "date"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="orders.php?mode=search&amp;sort=date">{$lng.lbl_date}</a></td>
	<td nowrap="nowrap">{if $search_prefilled.sort_field eq "total"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="orders.php?mode=search&amp;sort=total">{$lng.lbl_total}</a></td>
</tr>
</table>
*}
<br />
<table cellpadding="3" cellspacing="1" class="OrderSheet">
{assign var="cycle_state" value="first"}
{if $usertype eq 'A' && $current_membership_flag ne 'FS'}
  {assign var="static" value="O"}
{else}
  {assign var="static" value="Y"}
{/if}
{assign var="tmp_rows_counter" value=0}
{section name=oid loop=$orders}

{if $tmp_rows_counter gt 1}
{assign var="tmp_rows_counter" value=0}
{/if}

{include file="main/order_accounting_table_list.tpl" order=$orders[oid] static=$static cycle_state=$cycle_state tmp_rows_counter=$tmp_rows_counter}

{math equation="x+1" x=$tmp_rows_counter assign="tmp_rows_counter"}
{assign var="cycle_state" value="continue"}

{/section}
</table>
<br />

{include file="customer/main/navigation.tpl"}

<br /><br />

{*
{if $usertype eq 'A' && $current_membership_flag ne 'FS'}
<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'accounting_apply');" />
&nbsp;&nbsp;&nbsp;&nbsp;
{/if}
*}
<input type="button" value="{$lng.lbl_invoices_for_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) {ldelim} document.processorderform.target='invoices'; submitForm(this, 'invoice'); document.processorderform.target=''; {rdelim}" />
&nbsp;&nbsp;&nbsp;&nbsp;
{if $usertype ne "C"}
<input type="button" value="{$lng.lbl_labels_for_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) {ldelim} document.processorderform.target='labels'; submitForm(this, 'label'); document.processorderform.target=''; {rdelim}" />
&nbsp;&nbsp;&nbsp;&nbsp;
{/if}
{*
{if ($usertype eq "A" && $current_membership_flag ne 'FS') or ($usertype eq "P" and $active_modules.Simple_Mode)}
<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) if (confirm('{$lng.txt_delete_selected_orders_warning|strip_tags}')) submitForm(this, 'delete');" />
&nbsp;&nbsp;&nbsp;&nbsp;
{/if}
*}
{if $active_modules.Shipping_Label_Generator ne '' && ($usertype eq 'A' || $usertype eq 'P')}
<br />
<br />
<br />
{$lng.txt_shipping_labels_note}
<br />
<br />
<input type="button" value="{$lng.lbl_get_shipping_labels|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) {ldelim} document.processorderform.action='generator.php'; submitForm(this, ''); {rdelim}" />
{/if}

{if $usertype ne "C"}
<br />
<br />
<br />
{include file="main/subheader.tpl" title=$lng.lbl_export_orders}
{$lng.txt_export_all_found_orders_text}
<br /><br />
{$lng.lbl_export_file_format}:<br />
<select id="export_fmt" name="export_fmt">
	<option value="std">{$lng.lbl_standart}</option>
	<option value="csv_tab">{$lng.lbl_40x_compatible}: CSV {$lng.lbl_with_tab_delimiter}</option>
	<option value="csv_semi">{$lng.lbl_40x_compatible}: CSV {$lng.lbl_with_semicolon_delimiter}</option>
	<option value="csv_comma">{$lng.lbl_40x_compatible}: CSV {$lng.lbl_with_comma_delimiter}</option>
{if $active_modules.QuickBooks eq "Y"}
{include file="modules/QuickBooks/orders.tpl"}
{/if}
</select>
<br />
<br />
<input type="button" value="{$lng.lbl_export|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) submitForm(this, 'export');" />&nbsp;&nbsp;&nbsp;
<input type="button" value="{$lng.lbl_export_all_found|strip_tags:false|escape}" onclick="javascript: self.location='orders.php?mode=search&amp;export=export_found&amp;export_fmt='+document.getElementById('export_fmt').value;" />
{/if}

</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_search_results content=$smarty.capture.dialog extra='width="100%"'}
{/if}
