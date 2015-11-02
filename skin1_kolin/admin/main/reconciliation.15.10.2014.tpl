<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>

<script type="text/javascript">
<!--
{literal}
function managedate(type, status) {
        var fields = ['StartDay','StartMonth','StartYear','EndDay','EndMonth','EndYear'];
        
        for (i in fields)
                if (document.searchform.elements[fields[i]])
                        document.searchform.elements[fields[i]].disabled = status;
}
{/literal}
-->
</script>

<br />

{capture name=dialog}

{if $tab eq ""}
{assign var="tab" value="unreconciled"}
{/if}

<table width="100%">
<tr>
<td width="50" nowrap="nowrap">
{if $tab ne "unreconciled"}<a href="reconciliation.php?tab=unreconciled">{else}<B>{/if}Unreconciled{if $tab ne "unreconciled"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="50" nowrap="nowrap">
{if $tab ne "reconciled"}<a href="reconciliation.php?tab=reconciled">{else}<B>{/if}Reconciled{if $tab ne "reconciled"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="40" nowrap="nowrap">
{if $tab ne "dropped"}<a href="reconciliation.php?tab=dropped">{else}<B>{/if}Dropped{if $tab ne "dropped"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="50" nowrap="nowrap">
{if $tab ne "expense_report"}<a href="reconciliation.php?tab=expense_report">{else}<B>{/if}Expense&nbsp;report{if $tab ne "expense_report"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="50" nowrap="nowrap">
{if $tab ne "import"}<a href="reconciliation.php?tab=import">{else}<B>{/if}Import&nbsp;transactions{if $tab ne "import"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="30" nowrap="nowrap">
{if $tab ne "rules"}<a href="reconciliation.php?tab=rules">{else}<B>{/if}Rules{if $tab ne "rules"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="50" nowrap="nowrap">
{if $tab ne "calculation"}<a href="reconciliation.php?tab=calculation">{else}<B>{/if}Calculation{if $tab ne "calculation"}</a>{else}</B>{/if}
</td>
<td width="*">&nbsp;</td>
<td width="50" nowrap="nowrap">
{if $tab ne "accounts_payable"}<a href="reconciliation.php?tab=accounts_payable">{else}<B>{/if}Payables{if $tab ne "accounts_payable"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="50" nowrap="nowrap">
{if $tab ne "receivables"}<a href="reconciliation.php?tab=receivables">{else}<B>{/if}Receivables{if $tab ne "receivables"}</a>{else}</B>{/if}
</td>
</tr>
</table>

</br>
</br>
</br>

{if $tab eq "unreconciled" || $tab eq "reconciled" || $tab eq "dropped" || $tab eq "expense_report" || $tab eq "accounts_payable" || $tab eq "receivables"}
<form name="searchform" method="post" action="reconciliation.php">
<input type="hidden" name="mode" value="search" >
<input type="hidden" name="tab" value="{$tab}" >

{if $tab eq "unreconciled" || $tab eq "reconciled"}
<table>
<tr>
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_manufacturers}:</td>
        <td width="10">&nbsp;</td>
        <td>
          <select name="posted_data[manufacturers][]" multiple="multiple" size="10">
          {foreach from=$manufacturers item=mnf key=mid}
                <option value="{$mid}"
		{if $search_prefilled.manufacturers ne ""}
		  {foreach from=$search_prefilled.manufacturers item=v key=k}
			{if $mid eq $v} selected="selected"{/if}
		  {/foreach}
		{/if}
		>{$mnf.manufacturer}</option>
          {/foreach}
          </select>
        </td>
</tr>
</table>
{/if}

<table {if $tab eq "accounts_payable" || $tab eq "receivables"}align="right"{/if}>
<tr>
<td>
<B>{if $tab eq "accounts_payable" || $tab eq "receivables"}Order dates{else}Transaction dates{/if}</B>
from


{if $tab eq "accounts_payable" || $tab eq "receivables"}
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_date_Start").datepicker();
  });
{/literal}
-->
</script>

<input id="id_date_Start" type="text" size="11" name="date_Start" value="{$search_prefilled.date.start_date_str}" />
{else}
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_date_csv_Start").datepicker();
  });
{/literal}
-->
</script>

<input id="id_date_csv_Start" type="text" size="11" name="date_csv_Start" value="{$search_prefilled.date_csv.start_date_str}" />
{/if}

to

{if $tab eq "accounts_payable" || $tab eq "receivables"}
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_date_End").datepicker();
  });
{/literal}
-->
</script>

<input id="id_date_End" type="text" size="11" name="date_End" value="{$search_prefilled.date.end_date_str}" />
{else}
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_date_csv_End").datepicker();
  });
{/literal}
-->
</script>

<input id="id_date_csv_End" type="text" size="11" name="date_csv_End" value="{$search_prefilled.date_csv.end_date_str}" />
{/if}

<INPUT type="submit" value="{if $tab eq "expense_report"}Generate expense report{elseif $tab eq "accounts_payable" || $tab eq "receivables"}Show{elseif $tab eq "unreconciled"}Show transactions and orders{else}Show transactions{/if}">

</td>
</tr>
</table>

</form>
<br />
<br />
<br />
{/if}


{if $tab eq "unreconciled" || $tab eq "reconciled"}

{if $reconciliations ne ""}
<form name="r_form" method="post" action="reconciliation.php">

	{if $tab eq "reconciled"}
        	<input type="hidden" name="mode" value="unreconcile" >
	{elseif $tab eq "unreconciled"}
		<input type="hidden" name="mode" value="update" >
	{/if}

<input type="hidden" name="tab" value="{$tab}" >

<table cellpadding="3" cellspacing="1" {* width="100%" *}>
<tr class="TableHead">
<td style="background-color: #D9EAD3;" width="90">Date</td>
<td style="background-color: #D9EAD3;" width="200">Description</td>
<td style="background-color: #D9EAD3;" width="50">Amount</td>
<td style="background-color: #FFD44C;" width="90">Action</td>
<td style="background-color: #F4CCCC;" width="90">Amount</td>
<td style="background-color: #F4CCCC;" width="40">Distr</td>
<td style="background-color: #F4CCCC;" width="90">Order #</td>
<td style="background-color: #F4CCCC;" width="100">B2D status</td>
<td style="background-color: #F4CCCC;" width="90">Date</td>
{if $tab eq "unreconciled"}
        <td style="background-color: #D9EAD3;" width="20">Untie</td>
{/if}
</tr>
{foreach from=$reconciliations item=v key=k}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>

<td width="90" valign="top" align="center">{$v.date_csv|date_format:'%d-%b-%Y'}</td>
<td width="200" valign="top">{$v.description_csv}{if $v.transaction_type eq "P"} (PayPal){/if}</td>
<td width="50" valign="top" align="center">
{if $v.amount_csv_abs ne ""}({$v.amount_csv_abs|price_format}){else}{$v.amount_csv|price_format}{/if}
</td>
<td width="90" valign="top" align="center">

{* Removed from last condition || $v.amount_csv_abs|price_format eq $v.orders.0.accounting.2.gross|price_format *}

  {if ($tab eq "reconciled") || ($v.distr_code eq "" && $v.config_search_keyphrase_found eq "Y") ||

  ($v.orders ne "" &&
    (

	($v.total_order_amounts|price_format eq $v.amount_csv_abs|price_format)
	||
	(
	$v.d_bulk_or_individual_order_payments eq "distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping" && 
		($v.amount_csv_abs|price_format eq $v.orders.0.accounting.1.gross|price_format)
	)
    )
  )
  }

    <select name="action[{$v.id}]">
	<option value=""></option>

	{if $tab eq "reconciled"}
		<option value="UR">Unreconcile</option>
	{else}
		{if $v.action ne "D" && $v.config_search_keyphrase_found ne "Y"}
		<option value="R"{if ($v.action eq "R") || ($v.total_order_amounts|price_format eq $v.amount_csv_abs|price_format) || ($v.amount_csv_abs|price_format eq $v.orders.0.accounting.1.gross|price_format)} selected="selected"{/if}>Reconcile</option>
		{/if}

		{if $v.distr_code eq "" || $v.config_search_keyphrase_found eq "Y"}
		<option value="D"{if $v.action eq "D"} selected="selected"{/if}>Drop</option>
		{/if}
	{/if}
    </select>

  {/if}

</td>

<td {if $tab eq "unreconciled"}colspan="6"{else}colspan="5"{/if} valign="top">

 {if $v.orders ne ""}
  <table width="100%" cellpadding="0" cellspacing="0">

   {foreach from=$v.orders item=vo key=ko}
   <tr>
	<td width="90" align="center" nowrap="nowrap">

	    {if $vo.ref_to_us eq "Y"}
                REF TO US: <br />
                {$v.amount_csv|price_format}
            {else}
		({$vo.accounting.1.gross|price_format}){if $vo.accounting.2.gross gt 0}<br />+({$vo.accounting.2.gross|price_format}){/if}

		{assign var="ref_to_us" value=$vo.accounting.4.gross|price_format}
		{if $ref_to_us ne "0.00"}
		<br />
		ref to us: {$ref_to_us}
		{/if}
	    {/if}
	</td>
	<td width="40" align="center"><a href="manufacturers.php?manufacturerid={$v.manufacturerid}&distributor_section=11" target="_blank">{$v.distr_code}</a></td>
	<td width="90" align="center">
	<a href="order.php?orderid={$vo.orderid}" target="_blank">{$vo.order_prefix}{$vo.orderid}</a><br />
	</td>
	<td width="100" align="center">
	{if $vo.ref_to_us eq "Y"}
		REF status:<br />
		{include file="main/order_status.tpl" status=$vo.ru_status mode="static" status_type="RU"}
	{else}
		{include file="main/order_status.tpl" status=$vo.bd_status mode="static" status_type="BD"}
	{/if}
	<br />
	</td>
	<td width="90" align="center">
	{$vo.date|date_format:'%d-%b-%Y'}
	</td>

	{if $tab eq "unreconciled"}
	<td align="center" width="20">
        <input type="checkbox" name="clear_orders[{$v.id}][{$vo.orderid}]" value="Y" />
	</td>
	{/if}

   </tr>
   {/foreach}

{*
   {if $v.total_order_amounts gt 0 && $v.total_order_amounts ne $v.amount_csv_abs && $v.amount_csv_abs gt 0}
	{math assign="diff_amount" equation="y-x" y=$v.amount_csv_abs x=$v.total_order_amounts}
   <tr>
	<td align="center">
	<font style="color: red;">{if $diff_amount gt 0}({/if}{$diff_amount|price_format}{if $diff_amount gt 0}){/if}
	</td>
	<td {if $tab eq "unreconciled"}colspan="5"{else}colspan="4"{/if}></td>
   </tr>
   {/if}
*}

   {if $v.total_order_amounts_amount_csv_abs_diff_abs gt 0}
   <tr>
        <td align="center">
        <font style="color: red;">{if $v.total_order_amounts_amount_csv_abs_diff gt 0}({/if}{$v.total_order_amounts_amount_csv_abs_diff_abs|price_format}{if $v.total_order_amounts_amount_csv_abs_diff gt 0}){/if}
        </td>
        <td {if $tab eq "unreconciled"}colspan="5"{else}colspan="4"{/if}></td>
   </tr>
   {/if}

  </table>

 {elseif $v.distr_code ne ""}
  <table width="100%">
	<tr>
	<td width="100"></td>
	<td width="100" align="center"><a href="manufacturers.php?manufacturerid={$v.manufacturerid}&distributor_section=11" target="_blank">{$v.distr_code}</a></td>
	<td width="100"></td>
	<td width="100"></td>
	<td width="100"></td>
{if $tab eq "unreconciled"}
	<td width="100"></td>
{/if}
	</tr>
  </table>
 {/if}
</td>

</tr>
{/foreach}
</table>
<br />

<table width="100%">
<tr>
<td width="33%">&nbsp;</td>
<td width="*" align="center">
<INPUT type="submit" value="Apply">
</td>
<td width="33%" align="right">
{if $tab eq "unreconciled"}
<INPUT type="button" value="Untie selected transaction-order connections" onclick="document.r_form.mode.value='clear_orders'; document.r_form.submit();"></TD>
{/if}
</td>
</tr>
</table>

</form>
{else}
Empty
{/if}

{elseif $tab eq "dropped"}

{if $reconciliations ne ""}
<form name="rr_form" method="post" action="reconciliation.php">

<input type="hidden" name="mode" value="undrop" >
<input type="hidden" name="tab" value="{$tab}" >

<table cellpadding="3" cellspacing="1" width="100%">
<tr class="TableHead">
<td style="background-color: #D9EAD3;" width="100">Date</td>
<td style="background-color: #D9EAD3;" width="*">Description</td>
<td style="background-color: #D9EAD3;" width="100">Amount</td>
<td style="background-color: #FFD44C;" width="100">Action</td>
</tr>
{foreach from=$reconciliations item=v key=k}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
<td width="100" valign="top" align="center">{$v.date_csv|date_format:'%d-%b-%Y'}</td>
<td width="*" valign="top">{$v.description_csv}</td>
<td width="100" valign="top" align="center">{if $v.amount_csv_abs ne ""}({$v.amount_csv_abs|price_format}){else}{$v.amount_csv|price_format}{/if}</td>
<td width="100" valign="top" align="center">

    <select name="action[{$v.id}]">
        <option value=""></option>
        {if $tab eq "reconciled"}
        <option value="UR">Unreconcile</option>
	{elseif $tab eq "dropped"}
        <option value="UD">Undrop</option>
        {/if}
    </select>

</td>
</tr>
{/foreach}
</table>
<br />
<div align="right">
<INPUT type="submit" value="Apply">
</div>
</form>
{else}
Empty
{/if}

{elseif $tab eq "receivables"}

{if $orders ne ""}
<table cellpadding="3" cellspacing="1" width="100%">
<tr class="TableHead">
<td style="background-color: #D9EAD3;" width="90">Date</td>
<td style="background-color: #D9EAD3;" width="90">Order #</td>
<td style="background-color: #D9EAD3;" width="100">PO #</td>
<td style="background-color: #D9EAD3;" width="*">COMPANY NAME</td>
<td style="background-color: #D9EAD3;" width="200">BUYER'S NAME</td>
<td style="background-color: #D9EAD3;" width="90">AMOUNT</td>
</tr>
{foreach from=$orders item=v key=k}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
<td width="90" valign="top" align="center">{$v.date|date_format:'%d-%b-%Y'}</td>
<td width="90" valign="top" align="center"><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
<td width="100" valign="top">{$v.po_details.po_number}</td>
<td width="*" valign="top">{$v.po_details.company_name}</td>
<td width="200" valign="top">{$v.po_details.name_of_purchaser}</td>
<td width="90" valign="top" align="center">{$v.current_total_gross|price_format}</td>
</tr>
{/foreach}
<tr>
<td colspan="5">&nbsp;</td>
<td width="100" valign="top" align="center"><B>{$total_gross|price_format}</B></td>
</tr>
</table>
{else}
Empty
{/if}

{elseif $tab eq "expense_report"}

  {if $config_reconciliation_search_keyphrases ne ""}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

function func_show_full_info(id){
        $('#reconciliations_'+id).toggle();
}

{/literal}
//]]>
</script>


        <table cellpadding="3" cellspacing="1" width="100%">
        <tr class="TableHead">
        <td style="background-color: #F4CCCC;" width="100">Expense account code</td>
        <td style="background-color: #F4CCCC;" width="*">Expense account name</td>
        <td style="background-color: #F4CCCC;" width="100">Total Amount</td>
        </tr>

    {foreach from=$config_reconciliation_search_keyphrases item=item key=key}
     {if $item.found_records ne "" }

        <tr {cycle values=", class='TableSubHead'" name="cycle_all_totals"}>
        <td width="100" valign="top" align="center">{$item.code}</td>
        <td width="*" valign="top">{$item.search_keyphrase}</td>
        <td width="100" valign="top" align="center">
		<a href="javascript: void(0);" onclick="javascript: func_show_full_info('{$item.id}');">
		{if $item.total_amount_with_abs gt 0}
		({$item.total_amount_with_abs|price_format})
		{else}
		{$item.total_amount|price_format}
		{/if}
		</a>
	</td>
        </tr>

	<tr id="reconciliations_{$item.id}" style="display: none;">
	<td colspan="3">
	<hr />
	<table cellpadding="3" cellspacing="1" width="100%" >
	<tr class="TableHead">
	<td style="background-color: #D9EAD3;" width="100">Date</td>
	<td style="background-color: #D9EAD3;" width="*">Description</td>
	<td style="background-color: #D9EAD3;" width="100">Amount</td>
	</tr>
	{foreach from=$item.found_records item=v key=k}
	<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
	<td width="100" valign="top" align="center">{$v.date_csv|date_format:'%d-%b-%Y'}</td>
	<td width="*" valign="top">{$v.description_csv}</td>
	<td width="100" valign="top" align="center">{if $v.amount_csv_abs ne ""}({$v.amount_csv_abs|price_format}){else}{$v.amount_csv|price_format}{/if}</td>
	</tr>
	{/foreach}
	</table>
	<hr />
	</td>
	</tr>

      {/if}
    {/foreach}

        <tr><td colspan="2"></td><td align="center">
	<B>({$expense_report_sum_total_amount_with_abs|price_format})</B>
{*
<B>{if $expense_report_sum_total_amount_with_abs gt 0}({$expense_report_sum_total_amount_with_abs|price_format}){else}{$expense_report_sum_total_amount|price_format}{/if}</B>
*}
	</td></tr>

	</table>
  {/if}

{elseif $tab eq "accounts_payable"}

  {if $all_manufacturers_orders ne ""}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

function func_show_full_orders_info(id){
        $('#orders_list_'+id).toggle();
}

{/literal}
//]]>
</script>


        <table cellpadding="3" cellspacing="1" width="100%">
        <tr class="TableHead">
        <td style="background-color: #F4CCCC;" width="400">DISTRIBUTOR</td>
        <td style="background-color: #F4CCCC;" width="*">DISTR CODE</td>
        <td style="background-color: #F4CCCC;" width="100">TOTAL AMOUNT OWED</td>
        </tr>

    {foreach from=$all_manufacturers_orders item=item key=key}
     {if $item.orders ne "" }

        <tr {cycle values=", class='TableSubHead'" name="cycle_all_totals"}>
        <td width="400" valign="top" align="left">{$item.manufacturer}</td>
        <td width="*" valign="top" align="center">{$item.distr_code}</td>
        <td width="100" valign="top" align="center">
                <a href="javascript: void(0);" onclick="javascript: func_show_full_orders_info('{$item.manufacturerid}');">{$item.total_gross_accounting_1_2|price_format}</a>
        </td>
        </tr>

        <tr id="orders_list_{$item.manufacturerid}" style="display: none;">
        <td colspan="3">
        <hr />
        <table cellpadding="3" cellspacing="1" width="100%" >
        <tr class="TableHead">
        <td style="background-color: #D9EAD3;" width="100">Date</td>
        <td style="background-color: #D9EAD3;" width="100">Order #</td>
        <td style="background-color: #D9EAD3;" width="*">DISTR CODE</td>
        <td style="background-color: #D9EAD3;" width="100">AMOUNT</td>
        </tr>
        {foreach from=$item.orders item=v key=k}
        <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
        <td width="100" valign="top" align="center">{$v.date|date_format:'%d-%b-%Y'}</td>
        <td width="*" valign="top" align="center"><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
        <td width="*" valign="top" align="center">{$item.distr_code}</td>
        <td width="100" valign="top" align="center">{$v.current_total_gross_accounting_1_2|price_format}</td>
        </tr>
        {/foreach}
        </table>
        <hr />
        </td>
        </tr>

      {/if}
    {/foreach}
	
	<tr><td colspan="2"></td><td align="center"><B>{$sum_total_gross_accounting_1_2|price_format}</B></td></tr>
        </table>
  {/if}

{/if}


{if $tab eq "calculation"}
<br />
{capture name=dialog}
This calculation will match orders to transactions.
<br />
<br />

<form name="searchform1" method="post" action="reconciliation.php">
<input type="hidden" name="mode" value="find_orders" >
<input type="hidden" name="tab" value="{$tab}" >

<table width="100%">
<tr>
<td><B>Transaction dates</B>
from
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_date_csv_Start1").datepicker();
  });
{/literal}
-->
</script>

<input id="id_date_csv_Start1" type="text" size="11" name="date_csv_Start" value="{$search_prefilled.date_csv.start_date_str}" />

to
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_date_csv_End1").datepicker();
  });
{/literal}
-->
</script>

<input id="id_date_csv_End1" type="text" size="11" name="date_csv_End" value="{$search_prefilled.date_csv.end_date_str}" />

        </td>

{*
        <td align="right">
<table align="right">
<tr>
<td align="right"><B>Order dates</B>
from
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#id_date_Start1").datepicker();
  });
{/literal}
-->
</script>

<input id="id_date_Start1" type="text" size="11" name="date_Start" value="{$search_prefilled.date.start_date_str}" />

to
<script type="text/javascript" language="JavaScript 1.2">
<!--    
{literal}
  $(function() {
    $("#id_date_End1").datepicker();
  });
{/literal}
-->
</script>

<input id="id_date_End1" type="text" size="11" name="date_End" value="{$search_prefilled.date.end_date_str}" />

        </td>
</tr>

</table>
        </td>
*}

	<td>

	<B>Orders</B>
    <select name="data_orders_selectbox">
        <option value="1" {if $search_prefilled.data_orders_selectbox eq "1"}selected="selected"{/if}>1</option>
        <option value="2" {if $search_prefilled.data_orders_selectbox eq "2"}selected="selected"{/if}>2</option>
        <option value="3" {if $search_prefilled.data_orders_selectbox eq "" || $search_prefilled.data_orders_selectbox eq "3"}selected="selected"{/if}>3</option>
        <option value="6" {if $search_prefilled.data_orders_selectbox eq "6"}selected="selected"{/if}>6</option>
        <option value="12" {if $search_prefilled.data_orders_selectbox eq "12"}selected="selected"{/if}>12</option>
        <option value="24" {if $search_prefilled.data_orders_selectbox eq "24"}selected="selected"{/if}>24</option>
    </select>
	months back

	</td>

</tr>
</table>
<br />
<INPUT type="submit" value="Find orders">
</form>
{/capture}
{include file="dialog.tpl" title="Find orders for transactions" content=$smarty.capture.dialog extra="width=100%"}
{/if}



{if $tab eq "rules"}

 <table cellpadding="3" cellspacing="1" width="100%">
  <tr class="TableHead">
        <td style="background-color: #F4CCCC;">Reconciliation keyphrase</td>
	<td style="background-color: #F4CCCC;">Transaction type</td>
	<td style="background-color: #F4CCCC;">Account code</td>
	<td style="background-color: #F4CCCC;">Account  name</td>
  </tr>

  {if $search_keyphrase_list ne ""}
	{foreach from=$search_keyphrase_list item=v key=k}

	<tr>
	<td>{$v.search_keyphrase}</td>
	<td>{if $v.manufacturerid ne ""}Payment to distributor{else}Expense{/if}</td>
	<td>{if $v.code ne ""}{$v.code}{/if}</td>
	<td>{if $v.manufacturer ne ""}<a href="manufacturers.php?manufacturerid={$v.manufacturerid}&distributor_section=11" target="_blank">{$v.manufacturer}</a>{/if}</td>
	</tr>

	{/foreach}
  {/if}
 </table>

{/if}


{/capture}
{include file="dialog.tpl" title="Reconciliations" content=$smarty.capture.dialog extra="width=100%"}


{if $tab eq "import"}
<br />
{capture name=dialog}
<form name="importdata_form" enctype="multipart/form-data" method="post" action="reconciliation.php">
<input type="hidden" name="tab" value="import" >
<input type="hidden" name="mode" value="import" >
{$lng.lbl_csv_delimiter}:{include file="provider/main/ie_delimiter.tpl"}
<br />
{$lng.lbl_csv_file_for_upload}:<INPUT type="file" size="32" name="userfile">
{if $upload_max_filesize}
<br /><FONT class="Star">{$lng.lbl_warning}!</FONT> {$lng.txt_max_file_size_that_can_be_uploaded}: {$upload_max_filesize}b.
{/if}
<br />
<INPUT type="submit" value="Import">
</form>
{/capture}
{include file="dialog.tpl" title="Import" content=$smarty.capture.dialog extra="width=100%"}
{/if}

{if $tab eq "import"}
<br />
{capture name=dialog}
{if $reconciliation_upload_info ne ""}
<table cellpadding="3" cellspacing="1" width="100%">
<tr class="TableHead"><td>File Name</td><td>Date</td><td>Importer name</td><td>Start date</td><td>End date</td><td>Line count</td><td>Lines added</td><td>Checksum</td></tr>
{foreach from=$reconciliation_upload_info item=v key=k}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
<td nowrap="nowrap">
<a href="getfile.php?file=%2Freconciliation_feeds%2F{$v.date}.csv">{$v.orig_file_name}</a>
<br /><span style="font-size: 8px;">({$v.local_file})</span>
</td>
<td align="center">{$v.date|date_format:'%d-%b-%Y<br />%H:%M:%S'}</td>
<td>{$v.firstname} ({$v.login})</td>
<td align="center">{$v.min_date_in_file|date_format:'%d-%b-%Y'}</td>
<td align="center">{$v.max_date_in_file|date_format:'%d-%b-%Y'}</td>
<td align="right">{$v.count_lines}</td>
<td align="right">{$v.count_added_rows}</td>
<td align="right">{$v.checksum}</td>
</tr>
{/foreach}
</table>
{else}
Empty
{/if}
{/capture}
{include file="dialog.tpl" title="Uploaded files" content=$smarty.capture.dialog extra="width=100%"}
{/if}
