<br>
{capture name=dialog}

<br />

{if $rma_request_form_data ne "" && $rma_request_form_data.top_message_content ne ""}
<div style="border: solid 1px red;">
<br />
<table width="86%" align="center">
<tr>
<td align="center">

	{assign var="additional_vars" value=""}
	{if $rma_request_form_data.zipcode ne ""}
		{assign var="additional_vars" value="`$additional_vars`&rma_zipcode=`$rma_request_form_data.zipcode`"}
	{/if}
        {if $rma_request_form_data.email ne ""}
                {assign var="additional_vars" value="`$additional_vars`&rma_email=`$rma_request_form_data.email`"}
        {/if}
        {if $rma_request_form_data.orderid ne ""}
                {assign var="additional_vars" value="`$additional_vars`&rma_orderid=`$rma_request_form_data.orderid`"}
        {/if}

	{if $rma_request_form_data.top_message_content eq "error1"}
Sorry we can't retrieve your order. Please try again or <a href="help.php?section=contactus&mode=update{$additional_vars}">contact our customer care team.</a>
	{elseif $rma_request_form_data.top_message_content eq "error2"}
Sorry we can't find your order. Please try again or <a href="help.php?section=contactus&mode=update{$additional_vars}">contact our customer care team.</a>
        {elseif $rma_request_form_data.top_message_content eq "error4"}
Sorry but we can't find your order. Please try again or <a href="help.php?section=contactus&mode=update{$additional_vars}">contact our customer care team.</a>
	{elseif $rma_request_form_data.top_message_content eq "error3"}
Please select "Return QTY".
	{/if}
</td>
</tr>
</table>
<br />
</div>
<br />
</div>
<br />
<br />
{/if}


{if $step eq "1"}
<form action="rma_request.php" method="post" name="rma_request_form1">
<input type="hidden" name="mode" id="mode" value="" />

<table align="center" cellpadding="3" cellspacing="3">

<tr><td colspan="2">To submit your return/replacement request we need to retrieve your order first. Please let us know</td></tr>

<tr>
<td align="right">Your order #</td>
<td>
<input type="text" name="orderid" id="orderid" size="10" value="{$rma_request_form_data.orderid|default:''}" />
<a href="retrieve_orders.php" target="_blank">I don't remember my order #</a>
</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2">In addition, please provide us with at least one of the following pieces of information:</td></tr>

<tr>
<td align="right">Zip code of your shipping address:</td>
<td>
<input type="text" name="zipcode" id="zipcode" size="10" value="{$rma_request_form_data.zipcode|default:''}" />
</td>
</tr>

<tr>
<td align="right">Email address used when the order was placed:</td>
<td>
<input type="text" name="email" id="email" size="30" value="{$rma_request_form_data.email|default:''}" />
</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2" align="center">
<input type="button" value="Retrieve my order" onclick="javascript: submitForm(this, 'retrieve_my_order');" />
</td></tr>

</table>
</form>

{assign var="dialog_title" value="Product return/replacement request"}

{/if}


{if $step eq "2"}

<table width="86%" align="center">
<tr>
<td>
	{if $smarty.get.prefilled eq "Y" && $empty_form ne "Y"}
		{$lng.lbl_pre_filled_rma_form}
	{else}
		{$lng.lbl_blank_rma_form}
	{/if}
</td>
</tr>
</table>

	{include file="customer/main/rma_products.tpl"}

{/if}


{if $step eq "3"}
<table width="86%" align="center">
<tr>
<td>
	{$lng.lbl_rma_thank_you_page}
</td>
</tr>
</table>
{/if}

{if $step eq "2" || $step eq "3"}
	{assign var="dialog_title" value="Product return/replacement request # `$order.order_prefix``$order.orderid`_R-`$rma_info.rma_number`"}
{/if}

{/capture}
{include file="dialog.tpl" title=$dialog_title content=$smarty.capture.dialog extra='width="100%"' use_h1="Y"}
