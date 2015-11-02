
<br />
<a name="order_logs"></a>
<script type="text/javascript">
//<![CDATA[
$(function() {ldelim}
  $('#order_tabs-container').tabs();
{rdelim});
//]]>
</script>

{capture name=dialog}
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
		    {if $item.type eq "C" || $item.type eq "S"}
                        {if $key gt "0"}
                        {math assign="previous_key" equation="x-1" x=$key}
                        {/if}

                        {if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}
                                <tr><td colspan="4"><hr /></td></tr>
                        {/if}

                        <tr>
                                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}{$type_names[$item.type]}{/if}</td>
                                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}{$item.date|date_format:'%d-%b-%Y<br />%H:%M:%S'}{/if}</td>
                                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}{if $item.firstname ne ""}{$item.firstname}<br />{/if}{if $item.login ne ""}({$item.login}){/if}{/if}</td>
                                <td valign="top">{$item.log}</td>
                        </tr>
		    {/if}
                {/foreach}

		{if $order.cloned_from gt 0}
		 <tr><td colspan="3"></td><td>
			This order is cloned from <a style="color: #1411FF;" href="order.php?orderid={$order.cloned_from}" target="_blank">{$order.order_prefix}{$order.cloned_from}</a> ({if $order.cloned_by ne ""}{$order.cloned_by}: {/if}{$order.date|date_format:'%d-%b-%Y %H:%M:%S'})
		</td></tr>
		{/if}

                {if $cidev_order_details_TransID ne ""}
		<tr><td colspan="3"></td><td><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id={$cidev_order_details_TransID}" style="color: #1411FF;">Link to PayPal transaction</a></td></tr>
                {/if}

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


<form action="order.php" method="post" name="ordernotesformnew">
<input type="hidden" name="mode" value="submit_message" />
<input type="hidden" name="send_email" value="N" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
{$cidev_firstname} ({$login}) notes:<br />
<textarea id="notes" name="notes" cols="70" style="width: 100%;" rows="6"></textarea><br />

{* <input type="submit" value="Post message" id="post_message" /> *}

<div style="float: left;">
<input type="button" value="Post message" id="post_message1" onclick="javascript: document.ordernotesformnew.submit();" />
</div>

<div id="div_post_message2" style="display: none;">
&nbsp; <input type="button" value="Post to OTRS only" id="post_message2" onclick="javascript: document.ordernotesformnew.submit();" />
</div>

</form>
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
                	        <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login && ($item.type eq "C" || $item.type eq "S"))}{if $item.firstname ne ""}{$item.firstname}<br />{/if}{if $item.login ne ""}({$item.login}){/if}{/if}</td>
                        	<td valign="top">{$item.log}</td>
	                </tr>
		{/foreach}

			<tr>
				<td colspan="3">&nbsp;</td>
				<td valign="top">Order source: <a href="{$customer.referer}" target="_blank">Referral link</a></td>
			</tr>
		</table>
{* ------- END: All logs and messages ------- *}

        {/if}
      </div>
  {/foreach}
</div>
{/capture}
{include file="dialog.tpl" title="Logs and customer service communications" content=$smarty.capture.dialog extra='width="100%"'}
