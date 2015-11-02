<br />

{if $fba_report ne ""}

{*
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
*}

{capture name=dialog}

{*
{include file="customer/main/navigation.tpl"}
*}

<table border="0" width="100%" cellpadding="3" cellspacing="1">
<tr class='TableSubHead'>
<td><B>SKU</B></td>
<td><B>Product name</B></td>
<td><B>Supplier</B></td>
<td><B>Our FBA quantity</B></td>
<td><B>FBA buybox price</B></td>
<td><B>OUR minimum price</B></td>
<td><B>Stocking Days</B></td>
<td><B>Restocking period</B></td>
<td><B>N average</B></td>
<td><B>Sigma</B></td>
<td><B>Re-stock quantity</B></td>
<td><B>Cost to us</B></td>
<td><B>Extended</B></td>
</tr>

{foreach from=$fba_report item=p key=k}

   <tr {cycle values=", class='TableSubHead'"}>
	<td nowrap="nowrap">{$p.productcode}</td>
	<td>{$p.product}</td>
	<td>{$p.Supplier}</td>
	<td>{$p.amazon_fba_avail}</td>
	<td>{$p.fba_min_price}</td>
	<td>{$p.our_min_price}</td>
	<td>{$p.StockingDays}</td>
	<td>{$search_data.Amazon_FBA_Restocking_period_days}</td>
	<td>{$p.N_avg}</td>
	<td>{$p.sigma}</td>
	<td>{$p.Re_stock_quantity}</td>
	<td>{$p.cost_to_us}</td>
	<td>{$p.Extended}</td>
   </tr>

{/foreach}

<tr>
<td colspan="12" align="right">Total cost to us</td>
<td>{$total_Extended}</td>
</tr>

<tr>
<td colspan="13" align="right">
<a target="_blank" style="color: blue;" href="amazon_fba_restocking_report.php?mode=search&download=1">Download Amazon FBA re-stock analysis</a>
</td>
</tr>

</table>

{/capture}
{include file="dialog.tpl" title="Amazon FBA re-stock analysis:" content=$smarty.capture.dialog extra='width="100%"'}

<br />
{capture name=dialog}
{if $report2 ne ""}
<table border="0" width="100%" cellpadding="3" cellspacing="1">

{assign var="tmp_counter" value=0}
{foreach from=$report2 item=p key=k}

  {if $tmp_counter eq "0"}
  <tr class='TableSubHead'>
	{foreach from=$p key=key item=item}
		<td>{$key}</td>
	{/foreach}
  </tr>
  {/if}

  <tr>
	{foreach from=$p key=key item=item}
		<td>{$item}</td>
	{/foreach}
  </tr>
  {math assign="tmp_counter" equation="x+1" x=$tmp_counter}
{/foreach}

<tr>
<td colspan="10" align="right">
<a target="_blank" style="color: blue;" href="amazon_fba_restocking_report.php?mode=search&download=2">Download MFN sales to Amazon Fulfillment analysis</a>
</td>
</tr>

</table>
{/if}
{/capture}
{include file="dialog.tpl" title="MFN sales to Amazon Fulfillment analysis:" content=$smarty.capture.dialog extra='width="100%"'}


<br />
{capture name=dialog}
{if $report3 ne ""}
<table border="0" width="100%" cellpadding="3" cellspacing="1">

{assign var="tmp_counter" value=0}
{foreach from=$report3 item=p key=k}

  {if $tmp_counter eq "0"}
  <tr class='TableSubHead'>
        {foreach from=$p key=key item=item}
                <td>{$key}</td>
        {/foreach}
  </tr>
  {/if}

  <tr>
        {foreach from=$p key=key item=item}
                <td>{$item}</td>
        {/foreach}
  </tr>
  {math assign="tmp_counter" equation="x+1" x=$tmp_counter}
{/foreach}

<tr>
<td colspan="11" align="right">
<a target="_blank" style="color: blue;" href="amazon_fba_restocking_report.php?mode=search&download=3">Download X-Cart sales to Amazon Fulfillment analysis</a>
</td>
</tr>

</table>
{/if}
{/capture}
{include file="dialog.tpl" title="X-Cart sales to Amazon Fulfillment analysis:" content=$smarty.capture.dialog extra='width="100%"'}


{else}

{capture name=dialog}
<form name="sqform" action="amazon_fba_restocking_report.php" method="post">
<input type="hidden" name="mode" value="search" id="mode" />


Restocking period (in days): <input type="text" name="Amazon_FBA_Restocking_period_days" value="{$config.Amazon_FBA_options.Amazon_FBA_Restocking_period_days}" />
<br />
Report depth (in months): <input type="text" name="Amazon_FBA_Report_depth_months" value="{$config.Amazon_FBA_options.Amazon_FBA_Report_depth_months}" />
<br />
Tau: <input type="text" name="Amazon_FBA_Report_Tau" value="{$config.Amazon_FBA_options.Amazon_FBA_Report_Tau}" />

<br />
<br />
<input type="submit" name="submit" value="Search" />
</form>
{/capture}
{include file="dialog.tpl" title="Amazon FBA restocking report" content=$smarty.capture.dialog extra='width="100%"'}

{/if}


