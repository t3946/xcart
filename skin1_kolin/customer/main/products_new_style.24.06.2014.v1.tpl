{* $Id: products.tpl,v 3.72.2.3 2006/11/27 11:40:25 max Exp $ *}


{include file="check_email_script.tpl"}


{if $active_modules.Feature_Comparison ne '' && $products && $printable ne 'Y' && $products_has_fclasses}
{include file="modules/Feature_Comparison/compare_selected_button.tpl"}
{include file="modules/Feature_Comparison/products_check_js.tpl"}
{/if}
{if $usertype eq "C" and $config.Appearance.products_per_row ne "" and $config.Appearance.products_per_row gt 0 and $config.Appearance.products_per_row lt 4 and ($featured eq "Y" or $config.Appearance.featured_only_multicolumn eq "N")}

        {if $featured eq "Y"}
                {include file="customer/main/products_t_new.tpl" products=$products}
        {else}
                {include file="customer/main/products_t.tpl" products=$products}
        {/if}

{else}
{if $products}

{* -------------------- 
<ul class="catalog">
{assign var="tmp_count_cell_in_row" value=0}
{section name=product loop=$products}
{math equation="x+1" x=$tmp_count_cell_in_row assign="tmp_count_cell_in_row"}
<li data-id="{$products[product].productid}" class="jsProductPopup view-item">
{$tmp_count_cell_in_row}
{if $tmp_count_cell_in_row eq "3"}{assign var="tmp_count_cell_in_row" value=0}{/if}
</li>
{/section}
</ul>
<br />
 -------------------- *}

<script type="text/javascript" src="{$SkinDir}/js/highslide.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
{literal}
		hs.graphicsDir = 'http://stat.gid43.ru/images/highslide/';

		$(document).ready(function(){
			$("A.highslide.himage").click(function(){
				return hs.expand(this, {
					objectWidth: 500,
					width: 500
				});
			});

			jQuery('.sendmail-captcha, .securImage').hide();
		});
{/literal}
-->
</script>


{assign var='CountProducts' value=$products|@count}
{if $CountProducts eq "1"}
{assign var="cell_width" value="33"}
{elseif $CountProducts eq "2"}
{assign var="cell_width" value="66"}
{else}
{assign var="cell_width" value="100"}
{/if}

<table width="{$cell_width}%" cellpadding="0" cellspacing="0" style="border-top: 1px dashed #cccccc; border-left: 1px dashed #cccccc;">
{math equation="floor(100/x)" x=3 assign="width"}

{assign var="tmp_count_cell_in_row" value=0}

{section name=product loop=$products}

  {if $tmp_count_cell_in_row eq "0"}
	<tr>
  {/if}


{math equation="x+1" x=$tmp_count_cell_in_row assign="tmp_count_cell_in_row"}

<td width="{$width}%" class="PListCell" style="border-bottom: 1px dashed #cccccc; border-right: 1px dashed #cccccc; padding: 0px;">
<ul class="catalog">
<li data-id="{$products[product].productid}" class="jsProductPopup view-item">

{if $tmp_count_cell_in_row eq "3"}{assign var="tmp_count_cell_in_row" value=0}{/if}

{if $products[product].new_notify_in_stock_price ne ""}
	{assign var="current_price" value=$products[product].new_notify_in_stock_price}
{else}
	{if $products[product].map_price gt $products[product].taxed_price}
		{assign var="current_price" value=$products[product].map_price}
	{else}
		{assign var="current_price" value=$products[product].taxed_price}
	{/if}
{/if}

{assign var="discount" value=0}
<table align="center" width="98%" height="100%">
<tr>
<td {* class="PListImgBox" *} align="center">
<div {* class="PListImgBox" *} align="center">
	<a href="{if $search_all_website eq 'Y'}http://{$products[product].domain}/{/if}product.php?productid={$products[product].productid}"  {if $search_all_website eq 'Y'}target="_blank"{/if}>{include file="product_thumbnail.tpl" productid=$products[product].productid image_x=$config.Appearance.thumbnail_width product=$products[product].product tmbn_url=$products[product].tmbn_url}</a>

	{if $active_modules.Special_Offers ne "" and $products[product].have_offers}
	{include file="modules/Special_Offers/customer/product_offer_thumb.tpl" product=$products[product]}
	{/if}

	<div class="magnifying-glass-plus">
<a target="_blank" title="" href="image.php?type=P&id={$products[product].productid}" onclick="javascript: {literal} return hs.expand(this, { maxWidth: 700 }); {/literal}"><img src="{$ImagesDir}/magnifying-glass-plus.png" alt="" /></a>
	</div>
</div>
{*
<a href="{if $search_all_website eq 'Y'}http://{$products[product].domain}/{/if}product.php?productid={$products[product].productid}" class="SeeDetails" {if $search_all_website eq 'Y'}target="_blank"{/if}>{$lng.lbl_see_details}</a>
{if $active_modules.Feature_Comparison ne '' && $products[product].fclassid > 0 && $printable ne 'Y'}
<br />
<br />
<div align="center">
{include file="modules/Feature_Comparison/compare_checkbox.tpl" id=$products[product].productid}
</div>
{/if}
*}
</td>
</tr>

<tr>
<td valign="top" align="center" height="30">
<a href="{if $search_all_website eq 'Y'}http://{$products[product].domain}/{/if}product.php?productid={$products[product].productid}" {if $search_all_website eq 'Y'}target="_blank"{/if}><font class="ProductTitle">{$products[product].product}</font></a>
</td>
</tr>

<tr>
<td valign="top" align="center" height="30">

{*
{if $config.Appearance.display_productcode_in_list eq "Y" and $products[product].productcode ne ""}
<br />
<font color="#006600" size=2>{$lng.lbl_sku}: {$products[product].productcode}</font>
{/if}
<font size="2">
<br />
<br />
<div style="max-height: 44px; overflow: hidden; line-height: 14px">
	<span class="SPItems-description">{$products[product].descr|truncate:225:"...":true}</span>
</div>
</font>
*}
{*<hr class="PListLine" size="1" />*}

{if $products[product].product_type eq "C"}
	{include file="buttons/details.tpl" href="product.php?productid=`$products[product].productid`"}
{else}


{*
	{if $active_modules.Subscriptions ne "" and ($products[product].catalogprice gt 0 or $products[product].sub_priceplan gt 0)}
		{include file="modules/Subscriptions/subscription_info_inlist.tpl"}
	{else}
		{if $config.General.unlimited_products ne "Y" && ($products[product].avail le 0 or $products[product].avail lt $products[product].min_amount) && $products[product].variantid}
			&nbsp;
		{elseif $current_price ne 0}
			{if $products[product].list_price gt 0 and $current_price lt $products[product].list_price}
				{math equation="100-(price/lprice)*100" price=$current_price lprice=$products[product].list_price format="%3.0f" assign=discount}
				{if $discount gt 0}
					<font class="MarketPrice">{$lng.lbl_market_price}:
					{include file="currency.tpl" value=$products[product].list_price}
					</font><br />
				{/if}
			{/if}
			{if $active_modules.Special_Offers ne "" and $products[product].use_special_price ne ""}
				<s>
			{/if}
			<font class="ProductPrice">{$lng.lbl_our_price}: {include file="currency.tpl" value=$current_price}</font><font class="MarketPrice">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$current_price}</font>{if $discount gt 0}{if $config.General.alter_currency_symbol ne ""},{/if}<font class="ProductPrice">, {$lng.lbl_save_price} {$discount}%</font>{/if}
			{if $products[product].map_price gt $products[product].taxed_price}
				<br />
				<span class="map_price_help">{$config.Product_Page.map_bridge_text}</span>
			{/if}
			{if $active_modules.Special_Offers ne "" and $products[product].use_special_price ne ""}
				</s>
			{/if}


			{if $products[product].taxes}
				<br />
				<div class="PListTaxBox">{include file="customer/main/taxed_price.tpl" taxes=$products[product].taxes}</div>
			{/if}
			{if $active_modules.Special_Offers ne "" and $products[product].use_special_price ne ""}
				{include file="modules/Special_Offers/customer/product_special_price.tpl" product=$products[product]}
			{/if}
		{else}
			<font class="ProductPrice">{$lng.lbl_enter_your_price}</font>
		{/if}
	{/if}

*}

	{if $current_price ne 0}
	        {if $products[product].list_price gt 0 and $current_price lt $products[product].list_price}
        	        {math equation="100-(price/lprice)*100" price=$current_price lprice=$products[product].list_price format="%3.0f" assign=discount}
                        {if $discount gt 0}
                                        <span class="btn btn-price btn-price_big btn-price_not-available">
                                        <del>{include file="currency.tpl" value=$products[product].list_price}</del>
                                        </span>
                        {/if}
                {/if}

                 <span class="btn btn-price btn-price_big btn-price_stock-in">
                 {include file="currency.tpl" value=$current_price}
                 </span>
	{/if}

</td>
</tr>

<tr>
<td valign="top" align="center">

{if $products[product].eta_date_in_future eq "Y"}
<font color="#000000" size=2>
Expected availability: {$products[product].eta_date_dd_month_yyyy}
<br />
Sorry we don't take pre-orders.
</font>
{/if}

    {if $usertype eq "C" and $config.Appearance.buynow_button_enabled eq "Y"}

	{assign var="tmp_productid" value=$products[product].productid}
	{if $products[product].new_notify_in_stock_price ne "" && $notify_when_in_stock[$tmp_productid] ne "Y"}
		<div id="notify_tr1_{$products[product].productid}">
		<I><a href="javascript: void(0);" onclick="javascript: $('#notify_tr1_{$products[product].productid}').hide(); $('#notify_tr2_{$products[product].productid}').show();" >Notify me when it's in stock</a></I>
		</div>

		<div id="notify_tr2_{$products[product].productid}" style="display: none;">
		<form name="notifyform_{$products[product].productid}" method="post" action='{if $main eq "catalog"}{if $action_notify_url ne ""}{$action_notify_url}{else}home.php{/if}{elseif $main eq "brand_products"}brands.php{/if}'>
		<input type="hidden" name="productid" value="{$products[product].productid}" />
		<input type="hidden" name="mode" value="notify" />
		<input type="text" name="notify_email" value="" />

		{if $main eq "catalog"}
			<input type="hidden" name="cat" value="{$cat}" />

			{if $action_notify_url ne ""}
				<input type="hidden" name="redirect_to_notify_url" value="{$action_notify_url}" />
			{/if}
		{elseif $main eq "brand_products"}
			<input type="hidden" name="brandid" value="{$brandid}" />
		{/if}

		{if $smarty.get.page ne ""}
			<input type="hidden" name="page" value="{$smarty.get.page}" />
		{/if}

		{include file="buttons/button.tpl" button_title="Notify me" style="button" href="javascript:if (checkEmailAddress(document.notifyform_`$products[product].productid`.notify_email, 'Y')) `$ldelim`document.notifyform_`$products[product].productid`.submit()`$rdelim`"}
		</form>
		</div>
		<br />
	{else}
		<table align="center" width="96%">
		<tr><td>{include file="customer/main/buy_now.tpl" product=$products[product] new_three_columns_template="Y"}</td></tr>
		</table>
	{/if}
    {/if}
{/if}

	</td>
</tr>
</table>
{*
<br />
<br />
*}
{if $search_all_website ne 'Y'}
{* <br /> *}
{/if}
{if !%product.last%}
{*
<hr style="border-bottom: 1px dashed #CCCCCC; border-top: 0px; border-left: 0px; border-right: 0px;" />
*}
{/if}


</li>
</ul>
</td>

  {if $tmp_count_cell_in_row eq "3"}
        </tr>
  {/if}

{/section}



</table>


{if $active_modules.Feature_Comparison ne '' && $products && $printable ne 'Y' && $products_has_fclasses}
{include file="modules/Feature_Comparison/compare_selected_button.tpl" no_form=true}
{/if}
{else}
{$lng.txt_no_products_found}
{/if}
{/if}
