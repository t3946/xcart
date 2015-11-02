<div class="survey_form">

    {if $admin_area_uses eq "Y"}
     <form action="order.php" method="post" name="7_mnfnotifyform_{$m}">
	<input type="hidden" name="orderid" value="{$o}" />
	<input type="hidden" name="mnf_id" value="{$m}" />
	<input type="hidden" name="mode" id="mode_info_request_survey" value="mode_info_request_survey" />
    {else}
     <script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
     <link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.admin.css" />

     <p class="survey_form_header">Information Request</p>
     <form action="stock_availability.php" method="POST" name="mnf_notifyform">
	<input type="hidden"  name="m" value="{$m}" />
	<input type="hidden"  name="s" value="{$s}" />
	<input type="hidden"  name="o" value="{$o}" />
	<input type="hidden"  name="mode" value="sent" />
    {/if}

	{if $order.shipping_groups.$m.all_distributor_info.d_sec14_show_header eq "Y" && $admin_area_uses ne "Y"}
		{include file="customer/main/stock_availability_header.tpl"}
	{/if}

	{if $order.shipping_groups.$m.all_distributor_info.d_sec14_show_items_stock eq "Y" || $admin_area_uses eq "Y"}
		{include file="customer/main/stock_availability_items_stock.tpl"}
	{/if}

	{if $order.shipping_groups.$m.all_distributor_info.d_sec14_show_shipto eq "Y" || $admin_area_uses eq "Y"}
		{include file="customer/main/stock_availability_shipto.tpl"}
	{/if}

	{if $order.shipping_groups.$m.all_distributor_info.d_sec14_show_items_cost eq "Y" || $admin_area_uses eq "Y"}
		{include file="customer/main/stock_availability_items_cost.tpl"}
	{/if}

	{if $order.shipping_groups.$m.all_distributor_info.d_sec14_show_footer eq "Y" && $admin_area_uses ne "Y"}
		{include file="customer/main/stock_availability_footer.tpl"}
	{/if}

	<br />

{if $admin_area_uses eq "Y"}
	<input type="submit" value="Update the order" name="Update the order">
{else}
{*
        <div class="survey_button">
            <button id="submit" type="submit"></button>
        </div>
*}
	<span onclick="javascript: document.mnf_notifyform.submit();" style="cursor: pointer;" class="btn_send_info"></span>
{/if}

    </form>
</div>
