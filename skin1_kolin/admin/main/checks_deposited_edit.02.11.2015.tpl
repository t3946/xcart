{* $Id: checks_deposited_orders.tpl,v 1.15.2.3 2006/11/21 14:12:09 max Exp $ *}

<script type="text/javascript">
<!--
{literal}
function func_calc_total_deposit_amount(){

	var db_total_deposit_amount = {/literal}{$checks_deposited.total_deposit_amount|default:'0.00'}{literal};
	db_total_deposit_amount = parseFloat(db_total_deposit_amount);

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
				db_total_deposit_amount = db_total_deposit_amount + add_amount;
			}
                }
        }

	db_total_deposit_amount = price_format(db_total_deposit_amount);

	$("#Total_deposit_amount_id").text(db_total_deposit_amount);

//alert(db_total_deposit_amount);
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


<table cellpadding="3" cellspacing="1">

<tr>
<td><B>Deposit date:</B><br /><input id="date" type="text" size="9" name="date" value="{$checks_deposited.date|date_format:'%m/%d/%Y'}" /></td>
<td><B>Currency: </B><br />
{if $checks_deposited.currency_locked eq "Y"}
<input type="hidden" name="currency" value="{$checks_deposited.currency}" />
{/if}
<select name="currency" {if $checks_deposited.currency_locked eq "Y"}disabled="disabled"{/if}>
<option value="USD" {if $checks_deposited.currency eq "USD"}selected="selected"{/if}>USD</option>
<option value="CAD" {if $checks_deposited.currency eq "CAD"}selected="selected"{/if}>CAD</option>
</select>
</td>
<td colspan="3">
{* <input type="button" value="Apply" onclick="javascript: submitForm(this, 'update_deposit');" /> *}
</td>
</tr>

<tr><td colspan="5">This deposit contains checks for the following orders:</td></tr>

<tr class="TableHead">
        <td width="100">Order #</td>
        <td width="200"> Customer Check #</td>
        <td width="100">Amount</td>
        <td width="350">Notes</td>
	<td style="background: #ffffff;"></td>
</tr>

{if $checks_deposited_orders}

{foreach from=$checks_deposited_orders item=v key=k}

<tr{cycle name="embed" values=", class='TableSubHead'"}>
<td><a href="order.php?orderid={$v.orderid}" target="_blank">{$v.order_prefix}{$v.orderid}</a></td>
<td>{$v.check_number}</td>
<td>{$v.amount}</td>
<td>{$v.notes}</td>
<td style="background: #ffffff;"></td>
</tr>

{/foreach}

{/if}

{*
<tr>
	<td colspan="4" class="SubmitBox">
	<input type="button" value="Apply" onclick="javascript: submitForm(this, 'update_deposit');" />
	</td>
</tr>
*}

<tr><td colspan="5"><br />{include file="main/subheader.tpl" title="Add customer checks received"}</td></tr>

<tr id="customer_checks_received_tr">
<td align="center" id="customer_checks_received_box_1"><input type="text" size="9" name="add_orderids[0]" value="" style="width: 96%;" /></td>
<td align="center" id="customer_checks_received_box_2"><input type="text" size="9" name="add_check_numbers[0]" value="" style="width: 96%;" /></td>
<td align="center" id="customer_checks_received_box_3"><input onchange="javascript: func_calc_total_deposit_amount();" onkeyup="javascript: func_calc_total_deposit_amount();" onclick="javascript: func_calc_total_deposit_amount();" type="text" size="9" name="add_amounts[0]" value="" style="width: 96%;" /></td>
<td align="center" id="customer_checks_received_box_4"><input type="text" size="9" name="add_notes[0]" value="" style="width: 98%;" /></td>
<td width="30">{include file="buttons/multirow_add.tpl" mark="customer_checks_received"}</td>
</tr>

<tr><td colspan="5">&nbsp;</td></tr>

<tr>
<td colspan="2"><span style="font-weight: bold; font-size: 14px;">Total deposit amount:</span></td><td><span style="font-weight: bold; font-size: 14px;" id="Total_deposit_amount_id">{$checks_deposited.total_deposit_amount|default:'0.00'}</span></td><td colspan="2"></td>
</tr>

<tr><td colspan="5">&nbsp;</td></tr>

<tr>
        <td colspan="5" {*class="SubmitBox" *}>
        <input type="button" value="Apply changes / Add checks" onclick="javascript: submitForm(this, 'add_order');" />
        </td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title="Deposit" content=$smarty.capture.dialog extra='width="100%"'}

