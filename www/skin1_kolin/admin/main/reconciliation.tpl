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
<td width="120" nowrap="nowrap">
{if $tab ne "accounts_payable"}<a href="reconciliation.php?tab=accounts_payable">{else}<B>{/if}AP (Owed to Dx){if $tab ne "accounts_payable"}</a>{else}</B>{/if}&nbsp;&nbsp;&nbsp;
</td>
<td width="120" nowrap="nowrap">
{if $tab ne "receivables"}<a href="reconciliation.php?tab=receivables">{else}<B>{/if}AR (Unpaid: PO){if $tab ne "receivables"}</a>{else}</B>{/if}
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
{if $tab != "accounts_payable"}
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
{/if}
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
{if $tab != "accounts_payable"}
<br />
<br />
<br />
{/if}
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
<td style="background-color: #F4CCCC;" width="90">Invoice Date</td>
{if $tab eq "unreconciled"}
        <td style="background-color: #D9EAD3;" width="20">Untie</td>
{/if}
</tr>
{/if}

{if $reconciliations ne ""}
{foreach from=$reconciliations item=v key=k}
    {assign var=distributor value=$v.model->distributor}
    {assign var=invoices_total value=0}
    {foreach from=$v.model->invoices->order('invoice_date') item=vo key=ko}
        {math equation="x-y" x=$invoices_total y=$vo->invoice_total assign="invoices_total"}
    {/foreach}
    {foreach from=$v.model->memos->order('memo_date') item=vo key=ko}
        {math equation="x+y" x=$invoices_total y=$vo->ref_to_us_total assign="invoices_total"}
    {/foreach}

{if $v.row ne "2"}

<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>

<td width="90" valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}" align="center">
	{$v.model->date_csv|date_format:'%d-%b-%Y'}
</td>

<td width="200" valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}">
  {$v.model->getDescriptionBold()}{if $v.transaction_type eq "P"} (PayPal){/if}<br>({$v.model->account})
  {if $v.model->getLookupLink()}
      (<a style="color: blue;" href="https://mail.google.com/mail/u/0/#search/{$v.model->getLookupLink()}" target="_blank">lookup Gmail</a>)
  {/if}
</td>

<td width="50" valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}" align="center">
   {if $v.model->amount_csv < 0}({/if}{$v.model->amount_csv|abs|price_format}{if $v.model->amount_csv < 0}){/if}
</td>

<td width="90" valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}" align="center">
  {if $tab eq "reconciled" || (round($invoices_total,2) == $v.model->amount_csv && round($invoices_total, 2) != 0) || (!$distributor && $v.model->isExpense()) }
      <select name="action[{$v.model->id}]">
          <option value=""></option>
          {if $tab eq "reconciled"}
              <option value="UR">Unreconcile</option>
          {else}
              {if $v.model->action != "D"}
                  {if (round($invoices_total,2) == $v.model->amount_csv && round($invoices_total,2) !=0) || $v.model->action eq "R"}
                    <option value="R" selected="selected">Reconcile</option>
                  {/if}
              {/if}
              {if !$distributor && $v.model->isExpense()}
                  <option value="D"{if $v.model->action eq "D"} selected="selected"{/if}>Drop</option>
              {/if}
          {/if}
      </select>

  {elseif $tab eq "unreconciled"}

	<a href="javascript: void(0);" style="color: blue;" onclick="javascript: $('#add_orders_section_{$v.id}').toggle();">I've got a statement</a>

	{if round($invoices_total,2) != $v.model->amount_csv && round($invoices_total, 2) !=0}
		<br />
		<br />
		<input type="checkbox" name="action[{$v.id}]" value="R" />Force reconcile
	{/if}

  {/if}

</td>

<td {if $tab eq "unreconciled"}colspan="6"{else}colspan="5"{/if} valign="{if $v.two_reconciliations ne ""}middle{else}top{/if}">

 <table width="100%" cellpadding="0" cellspacing="0">


   {foreach from=$v.model->invoices->order('invoice_date') item=vo key=ko}
       <tr>
           <td width="90" align="center" nowrap="nowrap">
               ({$vo->invoice_total})
           </td>
           <td width="90" align="center">
               <a href="manufacturers.php?manufacturerid={$vo->manufacturerid}&distributor_section=11" target="_blank">{$vo->manufacturer->code}</a></td>
           <td width="90" align="center">
               <a href="order.php?orderid={$vo->orderid}" target="_blank">{$vo->order->getOrderNumber()}</a><br/>
           </td>
           <td nowrap="nowrap" width="100" align="center">
               {$vo}
               <br/>
           </td>
           <td width="90" align="center">
               {assign var=invoice_order value=$vo->order}
               {math equation="(x-y)/(60*60*24)" x=$v.model->date_csv y=$invoice_order->date assign="date_diff"}
               {if $date_diff >= 30}
               <span style="background-color: #F4CCCC;">
               {/if}
                   {$vo->invoice_date|date_format:'%d-%b-%Y'}
               {if $date_diff >= 30}
               </span>
               {/if}
           </td>
           {if $tab eq "unreconciled"}
               <td align="center" width="20">
                   <input type="checkbox"
                          name="clear_invoices_memos[I_{$v.model->id}_{$vo->invoice_number}_{$vo->manufacturerid}_{$vo->orderid}]"
                          value="Y"/>
               </td>
           {/if}
       </tr>
   {/foreach}
     {foreach from=$v.model->memos->order('memo_date') item=vo key=ko}
         <tr>
             <td width="90" align="center" nowrap="nowrap">
                 {$vo->ref_to_us_total}
             </td>
             <td width="90" align="center">
                 <a href="manufacturers.php?manufacturerid={$vo->manufacturerid}&distributor_section=11" target="_blank">{$vo->manufacturer->code}</a></td>
             <td width="90" align="center">
                 <a href="order.php?orderid={$vo->orderid}" target="_blank">{$vo->order->getOrderNumber()}</a><br/>
             </td>
             <td nowrap="nowrap" width="100" align="center">
                 {$vo}
                 <br/>
             </td>
             <td width="90" align="center">
                 <span style="background-color: #F4CCCC;">
                     {$vo->memo_date|date_format:'%d-%b-%Y'}
                 </span>
             </td>

             {if $tab eq "unreconciled"}
                 <td align="center" width="20">
                     <input type="checkbox"
                            name="clear_invoices_memos[M_{$v.model->id}_{$vo->memo_number}_{$vo->manufacturerid}_{$vo->orderid}]"
                            value="Y"/>
                 </td>
             {/if}

         </tr>
     {/foreach}


   {if round($invoices_total,2) != $v.model->amount_csv && round($invoices_total,2) !=0}
       {math equation="x-y" x=$v.model->amount_csv y=$invoices_total assign="invoices_diff"}
   <tr>
        <td align="center">
            <span style="color: red;">{if $invoices_diff < 0}({/if}{$invoices_diff|abs|price_format}{if $invoices_diff < 0}){/if} </span>
        </td>
        <td {if $tab eq "unreconciled"}colspan="5"{else}colspan="4"{/if}></td>
   </tr>
   {/if}

 {if round($invoices_total,2) == 0}
	<tr>
	<td width="90"></td>
	<td width="90" align="center">

        {if $distributor}
	        <a href="{$distributor->getAdminUrl()}&distributor_section=11" target="_blank">{$distributor->code}</a>
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
     {assign var=order_model value=$v->order}
     {assign var=distributor value=$v->manufacturer}
  <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
	<td colspan="4"></td>

      {if $v->invoices->count() || $v->memos->count()}
          <td colspan="6">
              <table width="100%" cellpadding="0" cellspacing="0">
                  {foreach from=$v->invoices item=vo key=ko}
                      {if $item->status === 'U'}
                      <tr>
                          <td width="90" align="center" nowrap="nowrap">
                              ({$vo->invoice_total})
                          </td>
                          <td width="90" align="center">
                              <a style="position: relative; bottom: 3px; left:22px;"
                                 href="manufacturers.php?manufacturerid={$distributor->manufacturerid}&distributor_section=11"
                                 target="_blank">{$distributor->code}
                              </a>
                          </td>
                          <td width="90" align="center">
                              <a href="{$order_model->getAdminUrl()}"
                                 target="_blank">{$order_model->getOrderNumber()}</a><br/>
                          </td>
                          <td width="100" align="center">
                              {$vo}
                              <br/>
                          </td>
                          <td width="90" align="center">
                              {$vo->invoice_date|date_format:'%d-%b-%Y'}
                          </td>
                      </tr>
                      {/if}
                  {/foreach}

                  {foreach from=$v->memos item=vo key=ko}
                      {if $vo->status === 'U'}
                      <tr>
                          <td width="90" align="center" nowrap="nowrap">
                              {$vo->ref_to_us_total}
                          </td>
                          <td width="90" align="center"><a style="position: relative; bottom: 3px; left:22px;"
                                                           href="manufacturers.php?manufacturerid={$distributor->manufacturerid}&distributor_section=11"
                                                           target="_blank">{$distributor->code}</a>
                          </td>
                          <td width="90" align="center">
                              <a href="{$order_model->getAdminUrl()}"
                                 target="_blank">{$order_model->getOrderNumber()}</a><br/>
                          </td>
                          <td width="100" align="center">
                              {$vo}
                              <br/>
                          </td>
                          <td width="90" align="center">
                              {$vo->memo_date|date_format:'%d-%b-%Y'}
                          </td>
                      </tr>
                      {/if}
                  {/foreach}

              </table>
          </td>
      {else}
          <td align="center"><B>N/A</B></td>
          <td align="center">{$distributor->code}</td>
          <td align="center"><a href="{$order_model->getAdminUrl()}"
                                target="_blank">{$order_model->getOrderNumber()}</a><br/></td>
          <td align="center"><B>Not received</B></td>
          <td align="center">{$order_model->date|date_format:'%d-%b-%Y'}</td>
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
                $(this).closest('tr#total_receivables_row').nextAll().addBack().css('opacity', 0.4);
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

        <table cellpadding="3" cellspacing="1" width="100%" class="admin" style="text-align: center">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h1>Balances due on invoices</h1>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td>
                    <select id="net_choises" title="Click to select Aging Period" style="width:400px" class="select2 big" multiple>
                        <option value="0">Current</option>
                        <option value="30">0-30</option>
                        <option value="60">31-60</option>
                        <option value="90">61-90</option>
                        <option value="91">Over 90</option>
                    </select>
                </td>
                <td>
                    <select id="distributor_choises" title="Click to select Dx" style="width:400px" class="select2 big" multiple>

                    </select>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td colspan="5" class="SubHeaderLine">
                    <img src="/skin1_kolin/images/spacer.gif" class="Spc" alt="">
                </td>
            </tr>
        </table>
        <div style="margin-top:20px;" class="distibutor_payable"></div>
        <br/>
        <br/>
    {literal}
        <script type="text/javascript">
            $('#net_choises').select2({
                allowClear: true,
                closeOnSelect: false,
                placeholder: $('#net_choises').attr('title')
            }).on('change.select2', function () {
                var distributor_data = [];
                $('option:selected', $('#distributor_choises')).each(function(){
                    distributor_data.push($(this).val());
                });
                var data = [];
                $('option:selected', $(this)).each(function(){
                    data.push($(this).val());
                });
                $('#distributor_choises').empty().prop("disabled", true);
                $.post('/admin/order/api/payable_manufacturers',{
                        period : data
                    },
                    function (data) {
                        var option = '';
                        var i = 0;
                        $('#distributor_choises').empty();
                        for (; i < data.length; i++) {
                            option = $('<option/>').attr('value', data[i].manufacturerid).text(data[i].manufacturer);
                            if (distributor_data.length > 0 && distributor_data.indexOf(data[i].manufacturerid) >= 0){
                                option.prop('selected', true);
                            }
                            $('#distributor_choises').append(option).prop("disabled", false);
                        }
                        $('#distributor_choises').change();
                    });
            });

            $('#distributor_choises').select2({
                allowClear: true,
                closeOnSelect: false,
                placeholder: $('#distributor_choises').attr('title')
            }).on('change.select2', function(){
                var distributor_data = [];
                $('option:selected', $(this)).each(function(){
                    distributor_data.push($(this).val());
                });
                var period_data = [];
                $('option:selected', $('#net_choises')).each(function(){
                    period_data.push($(this).val());
                });

                $.post('/admin/order/api/payable_orders',{
                        period : period_data,
                        distributor : distributor_data
                    },
                    function (data) {
                        $('.distibutor_payable').empty().css('opacity', 1).html(data);
                    });

            });
        </script>
    {/literal}


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