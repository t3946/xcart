<br />

{if $shipping_quote_logs ne ""}

{if $mode eq "search"}
{if $total_items gt "0"}
{$lng.txt_N_results_found|substitute:"items":$total_items}<br />
{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
{else}
{$lng.txt_N_results_found|substitute:"items":0}
{/if}
{/if}
<br />
<br />

{capture name=dialog}

{include file="customer/main/navigation.tpl"}

<form name="sqform" action="shipping_quotes_log.php" method="post">

<input type="hidden" name="mode" value="" id="mode" />

<table border="0" width="100%" cellpadding="3" cellspacing="1">
<tr class='TableSubHead'>
<td><B>Quote</B></td>
<td><B>Orders</B></td>
<td><B>Date</B></td>
<td><B>Distributor</B></td>
<td><B>ups server quote</B></td>
<td><B>ups ground quote</B></td>
<td><B>approx ground quote</B></td>
{*<td><B>customer_id</B></td> *}
{* <td><B>session_id</B></td> *}
<td><B>Product cost</B></td>
<td><B>Products(qty)</B></td>
<td><B>Address</B></td>
<td><B>Confirmation</B></td>
<td><B>reviewed by</B></td>
<td><B>reviewed date</B></td>
</tr>

{assign var="tmp_counter" value=0}

{foreach from=$shipping_quote_logs item=v key=k}
 {if $v ne ""}

  {if $tmp_counter eq 2}
	{assign var="tmp_counter" value=0}
  {/if}

  {foreach from=$v item=vv key=kk}

   <tr {if $tmp_counter eq 1}class='TableSubHead'{/if}>

	<td>{$vv.quote_id}</td>
	<td>
		{if $vv.orders ne ""}
			{foreach from=$vv.orders item=order key=k_order}
				<a href="order.php?orderid={$order.orderid}" target="_blank">{$order.order_prefix}{$order.orderid}</a><br />
			{/foreach}
		{/if}
	</td>
	<td>{$vv.datetime|date_format:'%d-%b-%Y'}</td>
	<td><a href="manufacturers.php?manufacturerid={$vv.manufacturerid}" target="_blank">{$vv.manufacturer_code}</a></td>
	<td>{$vv.ups_server_quote}</td>
	<td {if $vv.pink_ground_quote eq "Y"}style="background-color: #F4CCCC;"{/if}>{$vv.ups_ground_quote}</td>
	<td {if $vv.pink_ground_quote eq "Y" || $vv.pink_approx_ground_quote_AND_product_cost eq "Y"}style="background-color: #F4CCCC;"{/if}>{$vv.approx_ground_quote}</td>
{*	<td>{$vv.customer_id}</td> *}
{*	<td>{$vv.session_id}</td> *}
	<td {if $vv.pink_approx_ground_quote_AND_product_cost eq "Y"}style="background-color: #F4CCCC;"{/if}>{$vv.product_cost|price_format}</td>
	<td {* nowrap="nowrap" *}>
	{if $vv.products ne ""}
		{foreach from=$vv.products item=vvv key=kkk}
			<a target="_blank" style="font-size: 9px;" href="{$vvv.url}">{$vvv.product} ({$vvv.qty})</a><br /><br />
		{/foreach}
	{/if}
	</td>
	<td>{$vv.s_address}</td>
	<td>

	{if $vv.reviewed ne "Y"}
		{if $kk eq "0"}
			<input type="checkbox" name="reviewed[{$vv.quote_id}]" value="Y" />
		{/if}
	{/if}

	</td>

	<td>{if $vv.reviewed eq "Y"}{$vv.reviewed_by}{/if}</td>
	<td>{if $vv.reviewed eq "Y"}{$vv.reviewed_date|date_format:'%d-%b-%Y'}{/if}</td>
   </tr>

  {/foreach}

  {math assign="tmp_counter" equation="x+1" x=$tmp_counter}

 {/if}
{/foreach}

</table>

<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />

</form>

{/capture}
{include file="dialog.tpl" title="Shipping quotes log" content=$smarty.capture.dialog extra='width="100%"'}

{else}
<br />Empty
{/if}


