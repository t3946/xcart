{include file="main/subheader.tpl" title="Logs"}

<a name="order_logs"></a>
<script type="text/javascript">
//<![CDATA[
$(function() {ldelim}
    {if $smarty.get.tab2 ne ""}
        var indexTab2 = $('li a[href="#{$smarty.get.tab2}"]').parent().index();
    {else}
        var indexTab2 = 0;
    {/if}
  $('#order_tabs-container').tabs({ldelim} selected:indexTab2 {rdelim});
{rdelim});
//]]>
</script>

{*
{capture name=dialog}
*}
<div id="order_tabs-container">
  <ul>
  {foreach from=$order_tabs item=tab key=ind}
    <li><a href="#order_tabs-{$tab.anchor}">{$tab.title}</a></li>
  {/foreach}
  </ul>

  {foreach from=$order_tabs item=tab key=ind}
      <div id="order_tabs-{$tab.anchor}">
        {if $tab.section eq "important_messages"}

{* ------- START: Important messages ------- *}
                <table width="100%">
                <tr>
                        <td width="12%"><B>Type</B></td>
                        <td width="10%"><B>Date</B></td>
                        <td width="15%"><B>Name</B></td>
                        <td width="*%"><B>Log</B></td>
                </tr>

                {foreach from=$order_logs item=item key=key}
		    {if $item.type eq "C" || $item.type eq "S" || $item.type eq "P" || $item.type eq "PP"}
                        {if $key gt "0"}
                        {math assign="previous_key" equation="x-1" x=$key}
                        {/if}

                        {if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}
                                <tr><td colspan="4"><hr /></td></tr>
                        {/if}

                        <tr>
                                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}{$type_names[$item.type]}{/if}</td>
                                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}{$item.date|date_format:'%d-%b-%Y<br />%H:%M:%S'}{/if}</td>
                                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}{if $item.firstname ne ""}{$item.firstname}<br />{/if}{if $item.login ne ""}{if $item.firstname ne ""}({/if}{$item.login}{if $item.firstname ne ""}){/if}{/if}{/if}</td>
                                <td valign="top">

{if $item.log eq "checks_deposited_orders" && $checks_deposited_order ne ""}
                <table cellspacing="0" cellpadding="0" border="0">
                {foreach from=$checks_deposited_order item=vc key=kc}
                <tr>    
                        <td>{$vc.date|date_format:'%d-%b-%Y'}</td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td>Check# {$vc.check_number}</td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td>{$vc.currency}&nbsp;{$vc.amount}</td>
                </tr>
                {/foreach}
                <tr>
                        <td colspan="4"><B>Total deposited amount:</B>&nbsp;</td>
                        <td><a href="checks_deposited.php?checks_deposited_id={$vc.checks_deposited_id}"><B>{$vc.currency}&nbsp;{$vc.total_deposit_amount}</B></a></td>
                </table>
{else}
	{$item.log}

        {* --- *}
        {if $item.unserialized_transaction_log ne ""}
		<br />
                Transaction:
                {if $item.unserialized_transaction_log.FIELD_transaction_id ne ""}

                        {if $item.unserialized_transaction_log.FIELD_transaction_id_link ne ""}<a target="_blank" style="color: #1411FF;" href="{$item.unserialized_transaction_log.FIELD_transaction_id_link|substitute:"trans-id":$item.unserialized_transaction_log.FIELD_transaction_id}">{/if}
                        {if $item.unserialized_transaction_log.FIELD_transaction_link_anchor ne ""}{$item.unserialized_transaction_log.FIELD_transaction_link_anchor}{else}{$item.unserialized_transaction_log.FIELD_transaction_id}{/if}{if $item.unserialized_transaction_log.FIELD_transaction_id_link ne ""}</a>{/if}
                        {if $item.unserialized_transaction_log.FIELD_transaction_link_anchor ne ""}({$item.unserialized_transaction_log.FIELD_transaction_id}){/if}

{if $item.unserialized_transaction_log.FIELD_manual_transaction eq "Y"}
 (Manually added)
{/if}

                {else}
                        NONE
                {/if}

                <br />
                transaction_status: <B>{$item.unserialized_transaction_log.FIELD_transaction_status}</B><br />
                transaction_currency: {$item.unserialized_transaction_log.FIELD_transaction_currency}<br />
                transaction_total: {$item.unserialized_transaction_log.FIELD_transaction_total}
                                
                {if $item.issue ne ""}
                        <br />
                        <B>issue:</B> {$item.issue}
		{elseif $item.unserialized_transaction_log.message ne ""}
			<br />
			<B>message:</B> {$item.unserialized_transaction_log.message}
                {/if}

{if $item.transaction_log ne ""}
<script>
//<![CDATA[
{literal}
$(document).ready(function(){
    $('#log_show_hide_a_link_{/literal}{$key}{literal}').click(
       function() {
          $(this).text(function(i,text) { return (text == 'Show details') ? 'Hide details' : 'Show details'; });
          $('#div_a_transaction_log_{/literal}{$key}{literal}').toggle('slow');
          return false;
       }
    );
});
{/literal}
//]]>
</script>

<br />
<div id="div_a_transaction_log_{$key}" style="display: none;"><B>Full log:</B><br />{$item.transaction_log}</div>
<a href="javascript: void(0);" style="color: #1411FF;" onclick="javascript: func_show_hide_log('{$key}');" id="log_show_hide_a_link_{$key}">Show details</a>

{/if}

        {/if}
        {* --- *}

{/if}
				</td>
                        </tr>
		    {/if}
                {/foreach}

{*
		{if $order.cloned_from gt 0}
		 <tr><td colspan="3"></td><td>
			This order is cloned from <a style="color: #1411FF;" href="order.php?orderid={$order.cloned_from}" target="_blank">{$order.order_prefix}{$order.cloned_from}</a> ({if $order.cloned_by ne ""}{$order.cloned_by}: {/if}{$order.date|date_format:'%d-%b-%Y %H:%M:%S'})
		</td></tr>
		{/if}
*}


{* --- First transaction from customer --- 
                {if $transaction_logs.0.usertype eq "C"} 
		<tr><td colspan="3"></td><td>

{if $transaction_logs.0.transaction_id_link ne ""}<a target="_blank" href="{$transaction_logs.0.transaction_id_link|substitute:'trans-id':$transaction_logs.0.transaction_id}" style="color: #1411FF;">{/if}{if $transaction_logs.0.transaction_link_anchor ne ""}{$transaction_logs.0.transaction_link_anchor}{else}{$transaction_logs.0.transaction_id}{/if}{if $transaction_logs.0.transaction_id_link ne ""}</a>{/if} {if $transaction_logs.0.transaction_link_anchor ne ""}({$transaction_logs.0.transaction_id}){/if}

		</td></tr>
                {/if}
 --- End --- *}

{* --- First transaction --- *}
	        {if $transaction_logs ne ""}
		<tr><td colspan="3"></td><td>
                {assign var="first_transaction_found" value=""}

                {foreach from=$transaction_logs item=transaction_log key=k_transaction_logs}
                        {if $transaction_log.transaction_status ne "voided" && $transaction_log.transaction_id ne "" && $first_transaction_found eq ""}
                                {if $transaction_log.transaction_id_link ne ""}<a target="_blank" href="{$transaction_log.transaction_id_link|substitute:'trans-id':$transaction_log.transaction_id}" style="color: #1411FF;">{/if}{if $transaction_log.transaction_link_anchor ne ""}{$transaction_log.transaction_link_anchor}{else}{$transaction_log.transaction_id}{/if} {if $transaction_log.transaction_link_anchor ne ""}({$transaction_log.transaction_id}){/if}{if $transaction_log.transaction_id_link ne ""}</a>{/if}
                                {assign var="first_transaction_found" value="Y"}
                                <br />
                        {/if}
                {/foreach}
		</td></tr>
	        {/if}   
{* --- End --- *}


		<tr><td colspan="4"><br /><hr /><br /></td></tr>

		<tr>
			<td colspan="3"></td>
			<td>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}

function func_check_notes_field(){

	var notes = $('#notes').val();
	notes = notes.trim();
	var notes_length = notes.length;


	if (notes_length > 260){
//		$('#div_post_message2').show();
		document.getElementById('div_post_message2').style.display = "";
//		$('#post_message').attr("value", "Post to Gmail only");
		$('#post_message1').attr("disabled", "disabled");
//alert(notes_length);
	} else {
//		$('#div_post_message2').hide();
		document.getElementById('div_post_message2').style.display = "none";
//		$('#post_message').attr("value", "Post message");
		$('#post_message1').removeAttr("disabled");
	}
}

$(document).ready(function() {
	$('#notes').focusout(function() {
		func_check_notes_field();
	});

	$('#notes').keyup(function() {
        	func_check_notes_field();
	});
});

{/literal}
-->
</script>

<div id="send_note_form">
    <form action="order.php" method="post" name="ordernotesformnew">
        <input type="hidden" name="mode" value="submit_message"/>
        <input type="hidden" name="send_email" value="N"/>
        <input type="hidden" name="orderid" value="{$order.orderid}"/>
        {$cidev_firstname} ({$login}) notes:<br/>
        <div>
            <p><b>Subject line:</b></p>
            <input style="width: 100%;" type="text" name="subject_line"/>
        </div>
        <p><b>Message body:</b></p>
        <textarea id="notes" name="notes" cols="70" style="width: 100%;" rows="6"></textarea><br/>
        <br/>
        {* <input type="submit" value="Post message" id="post_message" /> *}

        <div style="float: left;">
            <input type="button" value="Post message" id="post_message1"
                   onclick="javascript: document.ordernotesformnew.submit();"/>
        </div>

        <div id="div_post_message2" style="display: none;">
            &nbsp; <input type="button" value="Post to OTRS only" id="post_message2"
                          onclick="javascript: document.ordernotesformnew.submit();"/>
        </div>

    </form>
</div>
			</td>
		</tr>
                </table>

{* ------- END: Important messages ------- *}

        {elseif $tab.section eq "all_logs_and_messages"}

{* ------- START: All logs and messages ------- *}
		<table width="100%">
		<tr>
			<td width="12%"><B>Type</B></td>
			<td width="10%"><B>Date</B></td>
			<td width="15%"><B>Name</B></td>
			<td width="*"><B>Log</B></td>
		</tr>

		{foreach from=$order_logs item=item key=key}
			{if $key gt "0"}
			{math assign="previous_key" equation="x-1" x=$key}
			{/if}

                        {if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login && ($item.type eq "C" || $item.type eq "S"))}
                                <tr><td colspan="4"><hr /></td></tr>
                        {/if}

	                <tr>
        	                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login && ($item.type eq "C" || $item.type eq "S"))}{$type_names[$item.type]}{/if}</td>
	                        <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login && ($item.type eq "C" || $item.type eq "S"))}{$item.date|date_format:'%d-%b-%Y<br />%H:%M:%S'}{/if}</td>
                	        <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login && ($item.type eq "C" || $item.type eq "S"))}{if $item.firstname ne ""}{$item.firstname}<br />{/if}{if $item.login ne ""}{if $item.firstname ne ""}({/if}{$item.login}{if $item.firstname ne ""}){/if}{/if}{/if}</td>
                        	<td valign="top">

{if $item.log eq "checks_deposited_orders" && $checks_deposited_order ne ""}
                <table cellspacing="0" cellpadding="0" border="0">
                {foreach from=$checks_deposited_order item=vc key=kc}
                <tr>
                        <td>{$vc.date|date_format:'%d-%b-%Y'}</td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td>Check# {$vc.check_number}</td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td>{$vc.currency}&nbsp;{$vc.amount}</td>
                </tr>
                {/foreach}
                <tr>
                        <td colspan="4"><B>Total deposited amount:</B>&nbsp;</td>
                        <td><a href="checks_deposited.php?checks_deposited_id={$vc.checks_deposited_id}"><B>{$vc.currency}&nbsp;{$vc.total_deposit_amount}</B></a></td>
                </table>
{else}
	{$item.log}

	{* --- *}
	{if $item.unserialized_transaction_log ne ""}
		<br />
	        Transaction:
	        {if $item.unserialized_transaction_log.FIELD_transaction_id ne ""}

			{if $item.unserialized_transaction_log.FIELD_transaction_id_link ne ""}<a target="_blank" style="color: #1411FF;" href="{$item.unserialized_transaction_log.FIELD_transaction_id_link|substitute:"trans-id":$item.unserialized_transaction_log.FIELD_transaction_id}">{/if}
			{if $item.unserialized_transaction_log.FIELD_transaction_link_anchor ne ""}{$item.unserialized_transaction_log.FIELD_transaction_link_anchor}{else}{$item.unserialized_transaction_log.FIELD_transaction_id}{/if}{if $item.unserialized_transaction_log.FIELD_transaction_id_link ne ""}</a>{/if}
			{if $item.unserialized_transaction_log.FIELD_transaction_link_anchor ne ""}({$item.unserialized_transaction_log.FIELD_transaction_id}){/if}

{if $item.unserialized_transaction_log.FIELD_manual_transaction eq "Y"}
 (Manually added)
{/if}

	        {else}
        	        NONE
	        {/if}

		<br />
	        transaction_status: <B>{$item.unserialized_transaction_log.FIELD_transaction_status}</B><br />
        	transaction_currency: {$item.unserialized_transaction_log.FIELD_transaction_currency}<br />
	        transaction_total: {$item.unserialized_transaction_log.FIELD_transaction_total}

                {if $item.issue ne ""}
                        <br />
                        <B>issue:</B> {$item.issue}
                {elseif $item.unserialized_transaction_log.message ne ""}
                        <br />
                        <B>message:</B> {$item.unserialized_transaction_log.message}
                {/if}

{if $item.transaction_log ne ""}
<script>
//<![CDATA[
{literal}
$(document).ready(function(){
    $('#log_show_hide_link_{/literal}{$key}{literal}').click(
       function() {
          $(this).text(function(i,text) { return (text == 'Show details') ? 'Hide details' : 'Show details'; });
          $('#div_transaction_log_{/literal}{$key}{literal}').toggle('slow');
          return false;
       }
    );
});
{/literal}
//]]>
</script>

<br />
<div id="div_transaction_log_{$key}" style="display: none;"><B>Full log:</B><br />{$item.transaction_log}</div>
<a href="javascript: void(0);" style="color: #1411FF;" onclick="javascript: func_show_hide_log('{$key}');" id="log_show_hide_link_{$key}">Show details</a>

{/if}


	{/if}
	{* --- *}

{/if}
				</td>
	                </tr>
		{/foreach}
            {if $oOrder}
                {assign var="sSurfPathLastReferer" value=$oOrder->getLastRefererUrl()}
            {/if}
			<tr>
				<td colspan="3">&nbsp;</td>
				<td valign="top">Order source: <a href="{$sSurfPathLastReferer|default:$customer.referer}" target="_blank">Referral link</a></td>
			</tr>
		</table>
{* ------- END: All logs and messages ------- *}

        {/if}
      </div>
  {/foreach}
</div>
{*
{/capture}
{include file="dialog.tpl" title="Logs and customer service communications" content=$smarty.capture.dialog extra='width="100%"'}
*}
