{if $other_customer_orders ne ""}
{capture name=other_orders}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

  $(document).ready(function() {  

        $('#orders_see_more').click(function() {
                $('#div_id_orders_2').toggle('slow', function() {
                // Animation complete.
                });

                $('#div_id_orders_see_more').toggle('slow', function() {
                // Animation complete.
                });
        });

        $('#orders_see_less').click(function() {
                $('#div_id_orders_2').toggle('slow', function() {
                // Animation complete.
                });

                $('#div_id_orders_see_more').toggle('slow', function() {
                // Animation complete.
                });
        });

  });

{/literal}
//]]>
</script>

Completed: {$count_Completed}, In progress: {$count_Open}, Fraud: {$count_Fraud}

<table>
{foreach from=$other_customer_orders item=v_o key=k_o}
{if $k_o lt $show_count_before_see_more}
<tr>
<td>
{assign var="v_o_status_found" value=""}
<a href="order.php?orderid={$v_o.orderid}" target="_blank" style="color: blue;">{$v_o.order_prefix}{$v_o.orderid}</a>{foreach from=$v_o.statuses item=v_o_status key=k_o_status}{if $v_o_status eq "Y"}{if $v_o_status_found eq "Y"}, {else}: {/if}<span style="background: {if $k_o_status eq 'Completed'}#D9EAD3{elseif $k_o_status eq 'Fraud'}Red{elseif $k_o_status eq 'Open'}#F4CCCC{/if};">{if $k_o_status eq "Open"}In progress{else}{$k_o_status}{/if}</span>{assign var="v_o_status_found" value="Y"}{/if}
{/foreach}
</td>
</tr>
{/if}
{/foreach}
</table>

{if $show_see_more eq "Y"}
<div id="div_id_orders_see_more" align="left"><a id="orders_see_more" style="color: blue;" href="javascript: void(0);">see more...</a></div>
{/if}

{if $show_see_more eq "Y"}
<div id="div_id_orders_2" style="display: none;">
<table>
{foreach from=$other_customer_orders item=v_o key=k_o}
{if $k_o gte $show_count_before_see_more}
<tr>
<td>
{assign var="v_o_status_found" value=""}
<a href="order.php?orderid={$v_o.orderid}" target="_blank" style="color: blue;">{$v_o.order_prefix}{$v_o.orderid}</a>{foreach from=$v_o.statuses item=v_o_status key=k_o_status}{if $v_o_status eq "Y"}{if $v_o_status_found eq "Y"}, {else}: {/if}<span style="background: {if $k_o_status eq 'Completed'}#D9EAD3{elseif $k_o_status eq 'Fraud'}Red{elseif $k_o_status eq 'Open'}#F4CCCC{/if};">{if $k_o_status eq "Open"}In progress{else}{$k_o_status}{/if}</span>{assign var="v_o_status_found" value="Y"}{/if}
{/foreach}
</td>
</tr>
{/if}
{/foreach}
</table>

        <div id="div_id_orders_see_less" align="left"><a style="color: blue;" id="orders_see_less" href="javascript: void(0);">see less...</a></div>
</div>
{/if}

{/capture}
{include file="dialog.tpl" title="Other orders from the same customer" content=$smarty.capture.other_orders extra='width="100%"'}
{/if}

