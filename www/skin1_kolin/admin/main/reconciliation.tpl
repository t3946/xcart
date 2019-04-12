{*<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>*}

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

{if $tab eq "unreconciled"}
<script type="text/javascript">
        <!--
                var lbl_add = '{$lng.lbl_add|escape}';
                var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
                var ImagesDir = '{$ImagesDir}';
        -->

{literal}
function add_order_manually_row(index, r_id) {

        var row_max_index = $('#row_max_index_' + r_id).val();
        row_max_index++;
        $('#row_max_index_' + r_id).val(row_max_index);

        $('#order_manually_row_' + r_id + '_' + index).after(
                '<tr id="order_manually_row_' + r_id + '_' + row_max_index + '"><td>&nbsp;</td>' +
                        '<td align="right">Add order #</td><td align="center">' +
                                '<input type="text" size="9" name="add_order_manually[' + r_id + '][' + row_max_index + '][orderid]" value="" /></td><td>' +
                                '<a href="javascript: void(0);" onclick="javascript: add_order_manually_row(\'' + row_max_index + '\', \'' + r_id + '\');"><img src="' + ImagesDir + '/plus.gif" alt="' + lbl_add + '" /></a>' +
                                '&nbsp;&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_order_manually_row(\'' + row_max_index + '\', \'' + r_id + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +


                        '</td>' +
                '</tr>'
        );
}

function remove_order_manually_row(index, r_id) {
        $('#order_manually_row_' + r_id +'_' + index).remove();
}
{/literal}
</script>
{/if}


<table width="100%">
<tr>
<td width="50" nowrap="nowrap">
{if $tab ne "unreconciled"}<a href="reconciliation.php?tab=unreconciled">{else}<B>{/if}Unreconciled{if $tab ne "unreconciled"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="50" nowrap="nowrap">
{if $tab ne "calculation"}<a href="reconciliation.php?tab=calculation">{else}<B>{/if}Calculation{if $tab ne "calculation"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
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
<td width="30" nowrap="nowrap">
{if $tab ne "rules"}<a href="reconciliation.php?tab=rules">{else}<B>{/if}Rules{if $tab ne "rules"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="50" nowrap="nowrap">
{if $tab ne "import"}<a href="reconciliation.php?tab=import">{else}<B>{/if}Import&nbsp;transactions{if $tab ne "import"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
    <td width="50" nowrap="nowrap">
        {if $tab ne "inventory"}<a href="reconciliation.php?tab=inventory">{else}<B>{/if}Inventory{if $tab ne "inventory"}</a>{else}</B>{/if}
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

{if $tab eq "unreconciled" || $tab eq "reconciled" || $tab eq "dropped" || $tab eq "expense_report" || $tab eq "accounts_payable" || $tab eq "receivables" || $tab eq "calculation"}
<form name="searchform" method="post" action="reconciliation.php">
<input type="hidden" name="mode" value="{if $tab eq "calculation"}find_orders{else}search{/if}" >
<input type="hidden" name="tab" value="{$tab}" >

{if $tab eq "unreconciled" || $tab eq "reconciled" || $tab eq "calculation"}
<table>
<tr>
        <td class="FormButton" nowrap="nowrap" width="330" align="right">
<input type="radio" name="posted_data[select_distributors]" value="from_the_list" {if $search_prefilled.select_distributors eq "from_the_list" || $search_prefilled.select_distributors eq ""}checked="checked"{/if} />Select distributors from the list
	</td>
        <td width="10">&nbsp;</td>
        <td width="320">
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

<tr>
	<td class="FormButton" nowrap="nowrap" align="right">
<input type="radio" name="posted_data[select_distributors]" value="ALL" {if $search_prefilled.select_distributors eq "ALL"}checked="checked"{/if} />Select ALL distributors
	</td>
	<td colspan="2"></td>
</tr>
</table>
<br />
{/if}

<table {if $tab eq "accounts_payable" || $tab eq "receivables"}align="right"{/if}>
<tr>
<td {if $tab eq "unreconciled" || $tab eq "reconciled" || $tab eq "calculation"}class="FormButton" nowrap="nowrap" width="330" align="right"{/if}>
<B>{if $tab eq "accounts_payable" || $tab eq "receivables"}Order dates{else}Transaction dates{/if}</B>

{if $tab eq "unreconciled" || $tab eq "reconciled" || $tab eq "calculation"}
</td>
<td width="10">&nbsp;</td>
<td width="320">
{/if}

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

{if $tab ne "unreconciled" && $tab ne "reconciled" && $tab ne "calculation"}
<INPUT type="submit" value="{if $tab eq "expense_report"}Generate expense report{elseif $tab eq "accounts_payable" || $tab eq "receivables"}Show{else}Show transactions{/if}">
{/if}
</td>
</tr>

{if $tab eq "unreconciled"}
<tr>
  <td class="FormButton" align="right">Show unreconciled invoices and credit memos</td>
  <td width="10">&nbsp;</td>
  <td>
<input type="checkbox" name="posted_data[show_unreconciled_invoices_and_memos]" value="Y" {if $search_prefilled.show_unreconciled_invoices_and_memos eq "Y" || $search_prefilled.show_unreconciled_invoices_and_memos eq ""}checked="checked"{/if} />
{*
	<div style="display: none;">
                <select name="data_orders_selectbox">
                        <option value="1" {if $search_prefilled.data_orders_selectbox eq "1"}selected="selected"{/if}>1</option>
                        <option value="2" {if $search_prefilled.data_orders_selectbox eq "2"}selected="selected"{/if}>2</option>
                        <option value="3" {if $search_prefilled.data_orders_selectbox eq "" || $search_prefilled.data_orders_selectbox eq "3"}selected="selected"{/if}>3</option>
                        <option value="6" {if $search_prefilled.data_orders_selectbox eq "6"}selected="selected"{/if}>6</option>
                        <option value="12" {if $search_prefilled.data_orders_selectbox eq "12"}selected="selected"{/if}>12</option>
                        <option value="24" {if $search_prefilled.data_orders_selectbox eq "24"}selected="selected"{/if}>24</option>
                </select>
	</div>
*}
  </td>
</tr>
{/if}

{if $tab eq "calculation" || $tab eq "unreconciled"}
<tr {if $tab eq "unreconciled"}style="display: none;"{/if}>
  <td class="FormButton" align="right"> Order dates</td>
  <td width="10">&nbsp;</td>
  <td>up to
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
{/if}

{if $tab eq "unreconciled" || $tab eq "reconciled" || $tab eq "calculation"}
<tr>
<td colspan="3" align="center"><br />
<INPUT type="submit" value="{if $tab eq "unreconciled"}Show transactions and orders{elseif $tab eq "calculation"}Find orders{else}Show transactions{/if}">
</td>
</tr>
{/if}

</table>

</form>
<br />
<br />
<br />
{/if}


{if $tab eq "unreconciled" || $tab eq "reconciled"}

<form name="r_form" method="post" action="reconciliation.php">


<div style="display: none;">
	  <input type="radio" name="posted_data[select_distributors]" value="from_the_list" {if $search_prefilled.select_distributors eq "from_the_list" || $search_prefilled.select_distributors eq ""}checked="checked"{/if} />
	  <input type="radio" name="posted_data[select_distributors]" value="ALL" {if $search_prefilled.select_distributors eq "ALL"}checked="checked"{/if} />

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

          {if $tab eq "unreconciled"}
                <input type="checkbox" name="posted_data[show_unreconciled_invoices_and_memos]" value="Y" {if $search_prefilled.show_unreconciled_invoices_and_memos eq "Y" || $search_prefilled.show_unreconciled_invoices_and_memos eq ""}checked="checked"{/if} />

                <select name="data_orders_selectbox">
                        <option value="1" {if $search_prefilled.data_orders_selectbox eq "1"}selected="selected"{/if}>1</option>
                        <option value="2" {if $search_prefilled.data_orders_selectbox eq "2"}selected="selected"{/if}>2</option>
                        <option value="3" {if $search_prefilled.data_orders_selectbox eq "" || $search_prefilled.data_orders_selectbox eq "3"}selected="selected"{/if}>3</option>
                        <option value="6" {if $search_prefilled.data_orders_selectbox eq "6"}selected="selected"{/if}>6</option>
                        <option value="12" {if $search_prefilled.data_orders_selectbox eq "12"}selected="selected"{/if}>12</option>
                        <option value="24" {if $search_prefilled.data_orders_selectbox eq "24"}selected="selected"{/if}>24</option>
                </select>
          {/if}
</div>


	{if $tab eq "reconciled"}
        	<input type="hidden" name="mode" value="unreconcile" >
	{elseif $tab eq "unreconciled"}
		<input type="hidden" name="mode" value="update" >
	{/if}

<input type="hidden" name="tab" value="{$tab}" >

<table cellpadding="3" cellspacing="1" {* width="100%" *}>

{if $reconciliations ne "" || ($tab eq "unreconciled" && $unreconciled_orders ne "")}
<tr class="TableHead">
<td style="background-color: #D9EAD3;" width="90">TR Date</td>
<td nowrap="nowrap" style="background-color: #D9EAD3;" width="200">Transaction Description</td>
<td style="background-color: #D9EAD3;" width="50">Amount</td>
<td class="bg__yelow" width="90">Action</td>
<td style="background-color: #F4CCCC;" width="90">Amount</td>
<td style="background-color: #F4CCCC;" width="90">Distr</td>
<td style="background-color: #F4CCCC;" width="90">Order #</td>
<td style="background-color: #F4CCCC;" width="100">Invoice #</td>
<td style="background-color: #F4CCCC;" width="90">Order Date</td>
{if $tab eq "unreconciled"}
        <td style="background-color: #D9EAD3;" width="20">Untie</td>
{/if}
</tr>
{/if}

{if $reconciliations ne ""}
{foreach from=$reconciliations item=v key=k}
{if $v.row ne "2"}

<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>

<td width="90" valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}" align="center">
  {if $v.two_reconciliations ne ""}
	{foreach from=$v.two_reconciliations item=vv key=kk}
		{$vv.date_csv|date_format:'%d-%b-%Y'}{if $kk eq "0"}<br /><br />{/if}
	{/foreach}
  {else}
	{$v.date_csv|date_format:'%d-%b-%Y'}
  {/if}
</td>

<td width="200" valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}">
  {if $v.two_reconciliations ne ""}
	{foreach from=$v.two_reconciliations item=vv key=kk}
		{$vv.description_csv}{if $vv.transaction_type eq "P"} (PayPal){/if} {if $kk eq "0"}<br /><br />{/if}
	{/foreach}
  {else}
	{$v.description_csv}{if $v.transaction_type eq "P"} (PayPal){/if}
  {/if}
  {if $v.gmail_search_link != ''}
      (<a style="color: blue;" href="https://mail.google.com/mail/u/0/#search/{$v.gmail_search_link}" target="_blank">lookup Gmail</a>)
  {/if}
</td>

<td width="50" valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}" align="center">
  {if $v.two_reconciliations ne ""}
        {foreach from=$v.two_reconciliations item=vv key=kk}
	        {if $vv.amount_csv_abs ne ""}({$vv.amount_csv_abs|price_format}){else}{$vv.amount_csv|price_format}{/if}{if $kk eq "0"}<br /><br />{/if}
        {/foreach}
  {else}
	{if $v.amount_csv_abs ne ""}({$v.amount_csv_abs|price_format}){else}{$v.amount_csv|price_format}{/if}
  {/if}
</td>

<td width="90" valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}" align="center">

  {if
        ($tab eq "reconciled") ||
        ($v.distr_code eq "" && $v.config_search_keyphrase_found eq "Y") ||

        (
          $v.invoices_and_memos ne ""
                && (
                        $v.total_invoices_and_memos_amounts|price_format eq $v.amount_csv_abs|price_format ||
                        $v.total_invoices_and_memos_amounts_abs|price_format eq $v.amount_csv|price_format
                )
        )
  }

    <select name="action[{$v.id}]">
	<option value=""></option>

	{if $tab eq "reconciled"}
		<option value="UR">Unreconcile</option>
	{else}
		{if $v.action ne "D" && $v.config_search_keyphrase_found ne "Y"}
                <option value="R"
{if
($v.action eq "R") ||
($v.total_invoices_and_memos_amounts|price_format eq $v.amount_csv_abs|price_format) ||
($v.total_invoices_and_memos_amounts_abs|price_format eq $v.amount_csv|price_format)
}
        selected="selected"
{/if}
                >Reconcile</option>
		{/if}

		{if $v.distr_code eq "" || $v.config_search_keyphrase_found eq "Y"}
		<option value="D"{if $v.action eq "D"} selected="selected"{/if}>Drop</option>
		{/if}
	{/if}
    </select>

  {elseif $tab eq "unreconciled"}

	<a href="javascript: void(0);" style="color: blue;" onclick="javascript: $('#add_orders_section_{$v.id}').toggle();">I've got a statement</a>

	{if $v.total_invoices_and_memos_amounts__amount_csv_abs_diff_abs != 0}
		<br />
		<br />
		<input type="checkbox" name="action[{$v.id}]" value="R" />Force reconcile
	{/if}

  {/if}

</td>

<td {if $tab eq "unreconciled"}colspan="6"{else}colspan="5"{/if} valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}">

 <table width="100%" cellpadding="0" cellspacing="0">

 {if $v.invoices_and_memos ne ""}

   {foreach from=$v.invoices_and_memos item=vo key=ko}
   <tr>
	<td width="90" align="center" nowrap="nowrap">

	    {if $vo.memo_info ne ""}
		{$vo.memo_info.ref_to_us_total}
            {else}
		({$vo.invoice_info.invoice_total})
	    {/if}
	</td>
	<td width="90" align="center"><a href="manufacturers.php?manufacturerid={$v.manufacturerid}&distributor_section=11" target="_blank">{$v.distr_code}</a></td>
	<td width="90" align="center">
	<a href="order.php?orderid={$vo.orderid}" target="_blank">{$vo.order_prefix}{$vo.orderid}</a><br />
	</td>
	<td nowrap="nowrap" width="100" align="center">
	{if $vo.memo_info ne ""}
		{$vo.order_prefix}{$vo.orderid}_{$v.distr_code}-C-{$vo.memo_info.memo_number}
	{else}
		{$vo.order_prefix}{$vo.orderid}_{$v.distr_code}-I-{$vo.invoice_info.invoice_number}
	{/if}
	<br />
	</td>
	<td width="90" align="center">
	{if $vo.diff_date gt 30}<span style="background-color: #F4CCCC;">{/if}
	{$vo.date|date_format:'%d-%b-%Y'}
	{if $vo.diff_date gt 30}</span>{/if}
	</td>

	{if $tab eq "unreconciled"}
	<td align="center" width="20">

		{if $vo.memo_info ne ""}
		        <input type="checkbox" name="clear_invoices_memos[M_{$v.id}_{$vo.memo_info.memo_number}_{$vo.memo_info.manufacturerid}_{$vo.orderid}]" value="Y" />
		{else}
		        <input type="checkbox" name="clear_invoices_memos[I_{$v.id}_{$vo.invoice_info.invoice_number}_{$vo.invoice_info.manufacturerid}_{$vo.orderid}]" value="Y" />
		{/if}
	</td>
	{/if}

   </tr>
   {/foreach}


   {if $v.total_invoices_and_memos_amounts__amount_csv_abs_diff_abs gt 0 && $v.two_reconciliations eq ""}
   <tr>
        <td align="center">
        <font style="color: red;">{if $v.total_invoices_and_memos_amounts__amount_csv_abs_diff gt 0}({/if}{$v.total_invoices_and_memos_amounts__amount_csv_abs_diff_abs|price_format}{if $v.total_invoices_and_memos_amounts__amount_csv_abs_diff gt 0}){/if}
        </td>
        <td {if $tab eq "unreconciled"}colspan="5"{else}colspan="4"{/if}></td>
   </tr>
   {/if}

 {elseif $v.distr_code ne ""}
	<tr>
	<td width="90"></td>
	<td width="90" align="center">
	    {if !empty($v.aManufacturersEntities)}
	    {foreach from=$v.aManufacturersEntities item=oManufacturer name=radioManufacturer}
	        <a href="{$oManufacturer->getAdminUrl()}&distributor_section=11" target="_blank">{$oManufacturer->getField('code')}</a> <br/>
	    {/foreach}
	    {/if}
	</td>
	<td width="90"></td>
	<td width="100"></td>
	<td width="90"></td>
	{if $tab eq "unreconciled"}
	<td width="20"></td>
	{/if}
	</tr>
 {/if}

 {if $tab eq "unreconciled"}
  <tr id="add_orders_section_{$v.id}" style="display: none;">
	<td colspan="6" align="left">
	<hr />
        <input type="hidden" id="row_max_index_{$v.id}" name="row_max_index_{$v.id}" value="1" />
        <table cellpadding="0" cellspacing="0">
            <tr id="order_manually_row_{$v.id}_1">
            <td width="90">{if !empty($v.aManufacturersEntities)}
                    {foreach from=$v.aManufacturersEntities item=oManufacturer name=radioManufacturer2}
                        <input {if $smarty.foreach.radioManufacturer2.first}checked = "checked"{/if} style="margin:0; cursor:pointer;" type="radio" name="manufacturer_selected[{$v.id}]" value="{$oManufacturer->getField('manufacturerid')}">
                        <a style="position: relative; bottom: 3px;" href="{$oManufacturer->getAdminUrl()}&distributor_section=11" target="_blank">{$oManufacturer->getField('code')}</a> <br/>
                    {/foreach}
	            {/if}
	        </td>
			<td width="70" align="right">Add order #</td>
            <td width="90" align="center"><input type="text" size="9" name="add_order_manually[{$v.id}][1][orderid]" value="" /></td>
			<td width="30"><a href="javascript: void(0);" onclick="javascript: add_order_manually_row(1, '{$v.id}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
            </td>
            </tr>
        </table>

	</td>
  </tr>
 {/if}

 </table>

</td>

</tr>
{/if}
{/foreach}
{/if}

{if $tab eq "unreconciled" && $unreconciled_orders ne ""}
	{if $reconciliations ne ""}
		<tr><td colspan="10"><hr /></td></tr>
	{/if}

 {foreach from=$unreconciled_orders item=v key=k}
  <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
	<td colspan="4"></td>


      {if $v.order_group_invoices ne "" || $v.order_group_memos ne ""}
          <td colspan="5">
              <table width="100%" cellpadding="0" cellspacing="0">

                  {if $v.order_group_invoices ne ""}
                      {foreach from=$v.order_group_invoices item=vo key=ko}
                          <tr>
                              <td width="90" align="center" nowrap="nowrap">
                                  ({$vo.invoice_total})
                              </td>
                              <td width="90" align="center"><a style="position: relative; bottom: 3px; left:22px;"
                                                               href="manufacturers.php?manufacturerid={$v.manufacturerid}&distributor_section=11"
                                                               target="_blank">{$manufacturers[$v.manufacturerid].code}</a>
                              </td>
                              <td width="90" align="center">
                                  <a href="order.php?orderid={$v.orderid}"
                                     target="_blank">{$v.order_prefix}{$v.orderid}</a><br/>
                              </td>
                              <td width="100" align="center">
                                  {$v.order_prefix}{$v.orderid}_{$manufacturers[$v.manufacturerid].code}-I-{$ko}
                                  <br/>
                              </td>
                              <td width="90" align="center">
                                  {$v.date|date_format:'%d-%b-%Y'}
                              </td>
                          </tr>
                      {/foreach}
                  {/if}

                  {if $v.order_group_memos ne ""}
                      {foreach from=$v.order_group_memos item=vo key=ko}
                          <tr>
                              <td width="90" align="center" nowrap="nowrap">
                                  {$vo.ref_to_us_total}
                              </td>
                              <td width="90" align="center"><a style="position: relative; bottom: 3px; left:22px;"
                                                               href="manufacturers.php?manufacturerid={$v.manufacturerid}&distributor_section=11"
                                                               target="_blank">{$manufacturers[$v.manufacturerid].code}</a>
                              </td>
                              <td width="90" align="center">
                                  <a href="order.php?orderid={$v.orderid}"
                                     target="_blank">{$v.order_prefix}{$v.orderid}</a><br/>
                              </td>
                              <td width="100" align="center">
                                  {$v.order_prefix}{$v.orderid}_{$manufacturers[$v.manufacturerid].code}-C-{$ko}
                                  <br/>
                              </td>
                              <td width="90" align="center">
                                  {$v.date|date_format:'%d-%b-%Y'}
                              </td>
                          </tr>
                      {/foreach}
                  {/if}

              </table>
          </td>
      {else}
          <td align="center"><B>N/A</B></td>
          <td align="center">{$manufacturers[$v.manufacturerid].code}</td>
          <td align="center"><a href="order.php?orderid={$v.orderid}"
                                target="_blank">{$v.order_prefix}{$v.orderid}</a><br/></td>
          <td align="center"><B>Not received</B></td>
          <td align="center">{$v.date|date_format:'%d-%b-%Y'}</td>
      {/if}

	<td></td>

  </tr>
 {/foreach}
{/if}

</table>
<br />

{if $reconciliations ne ""}
<table width="100%">
<tr>
<td width="33%">&nbsp;</td>
<td width="*" align="center">
<INPUT type="submit" value="Apply">
</td>
<td width="33%" align="right">
{*
{if $tab eq "unreconciled"}
<INPUT type="button" value="Untie selected transaction-order connections" onclick="document.r_form.mode.value='clear_invoices_memos'; document.r_form.submit();"></TD>
{/if}
*}
</td>
</tr>
</table>
{/if}

</form>

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
<td class="bg__yellow" width="100">Action</td>
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
    {if $aTotalReceivables}
        <table cellpadding="3" cellspacing="1" width="100%">
            <tr class="TableHead">
                <td style="background-color: #D9EAD3;" >Total</td>
                <td style="background-color: #D9EAD3;" >1 month</td>
                <td style="background-color: #D9EAD3;" >3 month</td>
                <td style="background-color: #D9EAD3;" >6 month</td>
                <td style="background-color: #D9EAD3;" >1 year and more</td>
            </tr>
            <tr id="total_receivables_row">
                <td align="center">
                    {if $aTotalReceivables.total > 0}
                        <a href="#" data-period="total" class="order_list_dropdown">
                    {/if}
                        {$aTotalReceivables.total|price_format}
                    {if $aTotalReceivables.total > 0}
                        </a>
                    {/if}
                </td>
                <td align="center">
                    {if $aTotalReceivables.one_month > 0}
                    <a href="#" data-period="one_month" class="order_list_dropdown">
                    {/if}
                        {$aTotalReceivables.one_month|price_format}
                    {if $aTotalReceivables.one_month > 0}
                    </a>
                    {/if}
                </td>
                <td align="center">
                    {if $aTotalReceivables.three_month > 0}
                        <a href="#" data-period="three_month" class="order_list_dropdown">
                    {/if}
                        {$aTotalReceivables.three_month|price_format}
                    {if $aTotalReceivables.three_month > 0}
                        </a>
                    {/if}
                </td>
                <td align="center">
                    {if $aTotalReceivables.six_month > 0}
                        <a href="#" data-period="six_month" class="order_list_dropdown">
                    {/if}
                        {$aTotalReceivables.six_month|price_format}
                    {if $aTotalReceivables.six_month > 0}
                        </a>
                    {/if}
                </td>
                <td align="center">
                    {if $aTotalReceivables.one_year > 0}
                        <a href="#" data-period="one_year" class="order_list_dropdown">
                    {/if}
                        {$aTotalReceivables.one_year|price_format}
                    {if $aTotalReceivables.one_year > 0}
                        </a>
                    {/if}
                </td>

            </tr>
        </table>
        <br/>
        <br/>
        {literal}
        <script type="text/javascript">
            $('.order_list_dropdown').click(function () {
                $(this).closest('tr#total_receivables_row').nextAll().andSelf().css('opacity', 0.4);
                $.post('ajax_admin.php',{
                            period : $(this).data('period'),
                            ajax_action: 'get_receivables_orders'
                        },
                        function (data) {
                            $('#total_receivables_row').next().remove().end().css('opacity', 1).after(data);
                        });
                return false;
            })
        </script>
        {/literal}
    {/if}

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
    {if $aTotalPayable}
        <table cellpadding="3" cellspacing="1" width="100%">
            <tr class="TableHead">
                <td style="background-color: #D9EAD3;" >Total</td>
                <td style="background-color: #D9EAD3;" >1 month</td>
                <td style="background-color: #D9EAD3;" >3 month</td>
                <td style="background-color: #D9EAD3;" >6 month</td>
                <td style="background-color: #D9EAD3;" >1 year and more</td>
            </tr>
            <tr id="total_payable_row">
                <td align="center">
                    {if $aTotalPayable.total > 0}
                    <a href="#" data-period="total" class="order_list_dropdown">
                        {/if}
                        {$aTotalPayable.total|price_format}
                        {if $aTotalPayable.total > 0}
                    </a>
                    {/if}
                </td>
                <td align="center">
                    {if $aTotalPayable.one_month > 0}
                    <a href="#" data-period="one_month" class="order_list_dropdown">
                        {/if}
                        {$aTotalPayable.one_month|price_format}
                        {if $aTotalPayable.one_month > 0}
                    </a>
                    {/if}
                </td>
                <td align="center">
                    {if $aTotalPayable.three_month > 0}
                    <a href="#" data-period="three_month" class="order_list_dropdown">
                        {/if}
                        {$aTotalPayable.three_month|price_format}
                        {if $aTotalPayable.three_month > 0}
                    </a>
                    {/if}
                </td>
                <td align="center">
                    {if $aTotalPayable.six_month > 0}
                    <a href="#" data-period="six_month" class="order_list_dropdown">
                        {/if}
                        {$aTotalPayable.six_month|price_format}
                        {if $aTotalPayable.six_month > 0}
                    </a>
                    {/if}
                </td>
                <td align="center">
                    {if $aTotalPayable.one_year > 0}
                    <a href="#" data-period="one_year" class="order_list_dropdown">
                        {/if}
                        {$aTotalPayable.one_year|price_format}
                        {if $aTotalPayable.one_year > 0}
                    </a>
                    {/if}
                </td>

            </tr>
        </table>
        <br/>
        <br/>
    {literal}
        <script type="text/javascript">
            $('.order_list_dropdown').click(function () {
                $(this).closest('tr#total_payable_row').nextAll().andSelf().css('opacity', 0.4);
                $.post('ajax_admin.php',{
                            period : $(this).data('period'),
                            ajax_action: 'get_payable_orders'
                        },
                        function (data) {
                            $('#total_payable_row').next().remove().end().css('opacity', 1).after(data);
                        });
                return false;
            })
        </script>
    {/literal}
    {/if}

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

{*
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
*}


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


{if $tab eq "inventory"}
    <br>
    {capture name=dialog}
    <table cellpadding="3" cellspacing="1" width="100%">
        <tr class="TableHead">
            <td>Report Date</td>
            <td>Items count</td>
            <td>AVG item cost</td>
            <td>AVG item amount</td>
            <td>Total amount</td>
            <td>total cost</td>
        </tr>
        {foreach from=$cidev_daily_fba_stats item=item}
            <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
                <td nowrap="nowrap">
                    {$item.reportdate|date_format:'%d-%b-%Y'}
                </td>
                <td align="right">{$item.items_count}</td>
                <td align="right">{$config.General.currency_symbol} {$item.avg_item_cost|number_format:2:'.':' '}</td>
                <td align="right">{$item.avg_item_amount|number_format:2:'.':' '}</td>
                <td align="right">{$item.total_amount|number_format:0:'.':' '}</td>
                <td align="right">{$config.General.currency_symbol} {$item.total_cost|number_format:2:'.':' '}</td>
            </tr>
        {/foreach}
    </table>

    {/capture}
    {include file="dialog.tpl" title="Inventory" content=$smarty.capture.dialog extra="width=100%"}
{/if}