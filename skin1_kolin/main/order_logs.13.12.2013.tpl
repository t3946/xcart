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
                        <td><B>Type</B></td>
                        <td><B>Date</B></td>
                        <td><B>Name</B></td>
                        <td><B>Log</B></td>
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
                                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}{$item.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'}{/if}</td>
                                <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login)}{if $item.firstname ne ""}{$item.firstname} {/if}{if $item.login ne ""}({$item.login}){/if}{/if}</td>
                                <td valign="top">{$item.log}</td>
                        </tr>
		    {/if}
                {/foreach}

		<tr><td colspan="4"><br /><hr /><br /></td></tr>
		<tr>
			<td colspan="3"></td>
			<td>
<a name="post_message"></a>

<form action="order.php" method="post" name="ordernotesformnew">
<input type="hidden" name="mode" value="submit_message" />
<input type="hidden" name="send_email" value="N" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
{$cidev_firstname} ({$login}) notes:<br />
<textarea name="notes" cols="70" style="width: 100%;" rows="6"></textarea><br />

<input type="submit" value="Post message" />
</form>
			</td>
		</tr>
                </table>

{* ------- END: Important messages ------- *}

        {elseif $tab.section eq "all_logs_and_messages"}

{* ------- START: All logs and messages ------- *}
		<table width="100%">
		<tr>
			<td><B>Type</B></td>
			<td><B>Date</B></td>
			<td><B>Name</B></td>
			<td><B>Log</B></td>
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
	                        <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login && ($item.type eq "C" || $item.type eq "S"))}{$item.date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'}{/if}</td>
                	        <td valign="top">{if !($previous_key gte "0" && $order_logs[$previous_key].type eq $item.type && $order_logs[$previous_key].date eq $item.date && $order_logs[$previous_key].login eq $item.login && ($item.type eq "C" || $item.type eq "S"))}{if $item.firstname ne ""}{$item.firstname} {/if}{if $item.login ne ""}({$item.login}){/if}{/if}</td>
                        	<td valign="top">{$item.log}</td>
	                </tr>
		{/foreach}

		</table>
{* ------- END: All logs and messages ------- *}

        {/if}
      </div>
  {/foreach}
</div>
{/capture}
{include file="dialog.tpl" title="Logs and customer service communications" content=$smarty.capture.dialog extra='width="100%"'}
