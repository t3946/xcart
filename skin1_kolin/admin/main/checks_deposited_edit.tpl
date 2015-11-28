{* $Id: checks_deposited_orders.tpl,v 1.15.2.3 2006/11/21 14:12:09 max Exp $ *}

<script type="text/javascript">
<!--
{literal}
function func_calc_total_deposit_amount(){

	var total_deposit_amount = 0;
	total_deposit_amount = parseFloat(total_deposit_amount);

	var add_amount = 0;
	add_amount = parseFloat(add_amount);

	var prefix = "add_amounts";

        if (prefix)
                var reg = new RegExp("^"+prefix, "");
        for (var i = 0; i < document.checks_deposited_ordersform.elements.length; i++) {
                if (document.checks_deposited_ordersform.elements[i].type == "text" && (!prefix || document.checks_deposited_ordersform.elements[i].name.search(reg) == 0)){
			add_amount = document.checks_deposited_ordersform.elements[i].value;

			if (add_amount != ""){
				add_amount = parseFloat(add_amount);
				total_deposit_amount = total_deposit_amount + add_amount;
			}
                }
        }


	// second part
        var prefix = "current_amount_";

        if (prefix)
                var reg = new RegExp("^"+prefix, "");
        for (var i = 0; i < document.checks_deposited_ordersform.elements.length; i++) {
                if (document.checks_deposited_ordersform.elements[i].type == "text" && (!prefix || document.checks_deposited_ordersform.elements[i].id.search(reg) == 0)){
                        add_amount = document.checks_deposited_ordersform.elements[i].value;

                        if (add_amount != ""){
                                add_amount = parseFloat(add_amount);
                                total_deposit_amount = total_deposit_amount + add_amount;
                        }
                }
        }


        // third part
        var prefix = "delete_";

        if (prefix)
                var reg = new RegExp("^"+prefix, "");
        for (var i = 0; i < document.checks_deposited_ordersform.elements.length; i++) {
                if (document.checks_deposited_ordersform.elements[i].type == "checkbox" && (!prefix || document.checks_deposited_ordersform.elements[i].id.search(reg) == 0)){

                        add_amount = document.checks_deposited_ordersform.elements[i].value;

                        if (add_amount != "" && document.checks_deposited_ordersform.elements[i].checked == true){

				var del_id_arr = document.checks_deposited_ordersform.elements[i].id.split(prefix);
				var current_amount_id = "current_amount_" + del_id_arr[1];
                                add_amount = parseFloat($('#'+current_amount_id).val());
                                total_deposit_amount = total_deposit_amount - add_amount;
                        }
                }
        }


        total_deposit_amount = price_format(total_deposit_amount);

	$("#Total_deposit_amount_id").text(total_deposit_amount);
}
{/literal}
-->
</script>


<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
{include file="main/multirow.tpl"}

{include file="page_title.tpl" title="Deposit"}

{capture name=dialog}

<form action="checks_deposited.php" method="post" name="checks_deposited_ordersform">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="checks_deposited_id" value="{$checks_deposited_id}" />

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#date").datepicker();
  });
{/literal}
-->
</script>

<script type="text/javascript">
        <!--
                var lbl_add = '{$lng.lbl_add|escape}';
                var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
                var ImagesDir = '{$ImagesDir}';
        -->
</script>

<script type="text/javascript">
<!--
multirowInputSets['customer_checks_received'] = [];
multirowInputSets['customer_checks_received'].noCloneContent = 1;
-->
</script>


<table cellpadding="3" cellspacing="3" width="100%">

<tr>
<td valign="top" nowrap="nowrap"><B>Deposit date:</B><br />

{if $checks_deposited.status eq "D"}
<input type="hidden" name="date" value="{$checks_deposited.date|date_format:'%m/%d/%Y'}" />
{/if}
<input id="date" {if $checks_deposited.status eq "D"}disabled="disabled"{/if} type="text" size="9" name="date" value="{$checks_deposited.date|date_format:'%m/%d/%Y'}" />

</td>
<td>&nbsp;&nbsp;</td>
<td valign="top"><B>Currency: </B><br />
{if $checks_deposited.currency_locked eq "Y" || $checks_deposited.status eq "D"}
<input type="hidden" name="currency" value="{$checks_deposited.currency}" />
{/if}
<select name="currency" {if $checks_deposited.currency_locked eq "Y" || $checks_deposited.status eq "D"}disabled="disabled"{/if}>
<option value="USD" {if $checks_deposited.currency eq "USD"}selected="selected"{/if}>USD</option>
<option value="CAD" {if $checks_deposited.currency eq "CAD"}selected="selected"{/if}>CAD</option>
</select>
</td>
<td>&nbsp;&nbsp;</td>
<td valign="top" nowrap="nowrap">
<B>Deposit status:</B><br />
<div style="padding-top: 3px;">{if $checks_deposited.status eq "P"}<I>{/if}{$deposite_statuses[$checks_deposited.status]|default:'Not yet entered'}{if $checks_deposited.status eq "P"}</I>{/if}</div>
{*
<select name="status" disabled="disabled">
{foreach from=$deposite_statuses item=v key=k}
<option value="{$k}" {if $checks_deposited.status eq $k}selected="selected"{/if}>{$v}</option>
{/foreach}
</select>
*}
</td>

<td align="right" valign="top" width="90%">
<a href="checks_deposited.php" style="color: blue;">Back to List of deposits</a>
</td>
</tr>
</table>



<table cellpadding="3" cellspacing="1" >

<tr><td colspan="5">This deposit contains checks for the following orders:</td></tr>

<tr class="TableHead">
        <td width="75">Order #</td>
        <td width="150"> Customer Check #</td>
        <td width="70">Amount</td>
        <td width="500">Internal Notes</td>
	<td {if $checks_deposited.status ne "P"}style="background: #ffffff;"{/if}>{if $checks_deposited.status eq "P"}Del{/if}</td>
</tr>

{if $checks_deposited_orders}

{foreach from=$checks_deposited_orders item=v key=k}

<tr{cycle name="embed" values=", class='TableSubHead'"}>
 <td align="center">
{*
{if $checks_deposited.status eq "D"}
*}
	<a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a> 
{*
{else}
	<input type="text" name="posted_data[{$v.id}][orderid]" value="{$v.orderid}" size="6" />
{/if}
*}
 </td>
 <td {if $checks_deposited.status eq "P"}align="center"{/if}>
{if $checks_deposited.status eq "D"}
	{$v.check_number}
{else}
	<input type="text" name="posted_data[{$v.id}][check_number]" value="{$v.check_number}" style="width: 90%;" />
{/if}
 </td>
 <td align="center">
{if $checks_deposited.status eq "D"}
	{$v.amount} 
{else}
	<input id="current_amount_{$v.id}"  type="text" name="posted_data[{$v.id}][amount]" value="{$v.amount}" size="7" onchange="javascript: func_calc_total_deposit_amount();" onkeyup="javascript: func_calc_total_deposit_amount();" onclick="javascript: func_calc_total_deposit_amount();" />
{/if}
 </td>
 <td>
{if $checks_deposited.status eq "D"}
	{$v.notes}
{else}
	<input type="text" name="posted_data[{$v.id}][notes]" value="{$v.notes}" style="width: 98%;" />
{/if}
 </td>
<td {if $checks_deposited.status ne "P"}style="background: #ffffff;"{/if} align="center">
{if $checks_deposited.status eq "P"}

<input type="checkbox" id="delete_{$v.id}" name="posted_data[{$v.id}][del]" value="Y" onclick="javascript: func_calc_total_deposit_amount();" />

{/if}
</td>
</tr>

{/foreach}

{/if}

{if $checks_deposited.status ne "D"}
<tr><td colspan="5"><br />{include file="main/subheader.tpl" title="Add customer checks received"}</td></tr>

<tr id="customer_checks_received_tr">
<td align="center" id="customer_checks_received_box_1"><input type="text" size="8" name="add_orderids[0]" value="" /></td>
<td align="center" id="customer_checks_received_box_2"><input type="text" size="9" name="add_check_numbers[0]" value="" style="width: 90%;" /></td>
<td align="center" id="customer_checks_received_box_3"><input onchange="javascript: func_calc_total_deposit_amount();" onkeyup="javascript: func_calc_total_deposit_amount();" onclick="javascript: func_calc_total_deposit_amount();" type="text" size="7" name="add_amounts[0]" value="" /></td>
<td align="center" id="customer_checks_received_box_4"><input type="text" size="9" name="add_notes[0]" value="" style="width: 98%;" /></td>
<td width="30">{include file="buttons/multirow_add.tpl" mark="customer_checks_received"}</td>
</tr>
{/if}

<tr><td colspan="5">&nbsp;</td></tr>

<tr>
<td colspan="2"><span style="font-weight: bold; font-size: 14px;">Total deposit amount:</span></td><td><span style="font-weight: bold; font-size: 14px;" id="Total_deposit_amount_id">{$checks_deposited.total_deposit_amount|default:'0.00'}</span></td><td colspan="2"></td>
</tr>

<tr><td colspan="5">&nbsp;</td></tr>

{if $checks_deposited.status ne "D"}
<tr>
        <td colspan="5" {*class="SubmitBox" *}>
	<table width="100%">
	<tr>
	<td width="40%" valign="top">
{*        <input type="button" value="Apply changes" onclick="javascript: submitForm(this, 'add_order');" /> *}
        <input type="button" value="Apply changes" onclick="javascript: submitForm(this, 'update_deposit');" />
	</td>
	<td align="left" width="*" valign="top" nowrap="nowrap">
	<input type="button" value="Checks are now deposited with the bank" onclick="javascript: submitForm(this, 'checks_are_now_deposited_with_the_bank');" />
<br />
<I>Before clicking this button please make sure that<br />the amount deposited with the bank is the same as<br />the <B>Total deposit amount</B> shown above.</I>

	</td>
	<td align="right" width="40%" valign="top">
	</td>
	</tr>
	</table>
        </td>
</tr>
{/if}

</table>
</form>

{/capture}
{include file="dialog.tpl" title="Deposit" content=$smarty.capture.dialog extra='width="100%"'}

