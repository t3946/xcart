{* $Id: products.tpl,v 3.72.2.3 2006/11/27 11:40:25 max Exp $ *}

        {* --- *}
	{if $e_search_data.substring ne ""}
		{$e_search_data.total} {if $e_search_data.total eq "1"}product{else}products{/if} found for "{if $e_search_data.orig_substring ne ""}{$e_search_data.orig_substring}{else}{$e_search_data.substring}{/if}"
	{/if}

        {if $suggests_arr ne ""}
		<br />
		<br />
                <span style="font-size: 14px; font-weight: bold;">{$lng.lbl_elasticsearch_correct_suggestions_label}</span>

                {foreach from=$suggests_arr item=v_s key=k_s}
                        <br /><a href="/keyword/{$v_s.clean_suggest}/">{$v_s.twotabsearchtextbox}</a>
                {/foreach}

		<br />
		<br />
        {/if}
        {* --- *}


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

{section name=product loop=$products}


 {if $N_key eq ""}
	{if $first_item ne "" && $first_item gt 0}
		 {math assign="N_key" equation="x-1" x=$first_item}
	{else}
		{assign var="N_key" value="0"}
	{/if}
 {/if}
 {math assign="N_key" equation="x+1" x=$N_key}


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
<table width="100%">
<tr>
<td class="PListImgBox">
<div class="PListImgBox">
<a {include file="on_product_click.tpl"} href="{if $search_all_website eq 'Y'}{if $products[product].clean_url ne ""}{$products[product].clean_url}{else}http://{$products[product].domain}/product.php?productid={$products[product].productid}{/if}{else}/product.php?productid={$products[product].productid}{/if}"  {if $search_all_website eq 'Y'}target="_blank"{/if}>{include file="product_thumbnail.tpl" productid=$products[product].productid image_x=$config.Appearance.thumbnail_width product=$products[product].product tmbn_url=$products[product].tmbn_url}</a>
{if $active_modules.Special_Offers ne "" and $products[product].have_offers}
{include file="modules/Special_Offers/customer/product_offer_thumb.tpl" product=$products[product]}
{/if}
</div>
<a {include file="on_product_click.tpl"} href="{if $search_all_website eq 'Y'}{if $products[product].clean_url ne ""}{$products[product].clean_url}{else}http://{$products[product].domain}/product.php?productid={$products[product].productid}{/if}{else}/product.php?productid={$products[product].productid}{/if}" class="SeeDetails" {if $search_all_website eq 'Y'}target="_blank"{/if}>{$lng.lbl_see_details}</a>
{if $active_modules.Feature_Comparison ne '' && $products[product].fclassid > 0 && $printable ne 'Y'}
<br />
<br />
<div align="center">
{include file="modules/Feature_Comparison/compare_checkbox.tpl" id=$products[product].productid}
</div>
{/if}
</td>
<td valign="top">
<a {include file="on_product_click.tpl"} href="{if $search_all_website eq 'Y'}{if $products[product].clean_url ne ""}{$products[product].clean_url}{else}http://{$products[product].domain}/product.php?productid={$products[product].productid}{/if}{else}/product.php?productid={$products[product].productid}{/if}" {if $search_all_website eq 'Y'}target="_blank"{/if}><font class="ProductTitle">{$products[product].product}</font></a>
{if $config.Appearance.display_productcode_in_list eq "Y" and $products[product].productcode ne ""}
<br />
<font color="#006600" size=2>{$lng.lbl_sku}: {$products[product].productcode}</font>
{/if}
<font size="2">
<br />
<br />
<div style="max-height: 44px; overflow: hidden; line-height: 14px">
	<span class="SPItems-description">{$products[product].descr|default:$products[product].fulldescr|truncate:225:"...":true}</span>
</div>
</font>
{*<hr class="PListLine" size="1" />*}
<br>
{if $products[product].product_type eq "C"}
{include file="buttons/details.tpl" href="/product.php?productid=`$products[product].productid`"}
{else}
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
<s>{include file="currency.tpl" value=$products[product].list_price}</s>
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

{if $products[product].eta_date_in_future eq "Y"}
<br />
<br />
<font color="#000000" size=2>
Expected availability: {$products[product].eta_date_mm_dd_yyyy|date_format:'%d-%b-%Y'}
{if $products[product].allow_pre_orders ne "Y"}
<br />
Sorry we don't take pre-orders.
{/if}
</font>
{/if}

{if $usertype eq "C" and $config.Appearance.buynow_button_enabled eq "Y"}

	{assign var="tmp_productid" value=$products[product].productid}

	{if $products[product].new_notify_in_stock_price ne "" && $notify_when_in_stock[$tmp_productid] ne "Y"}
<br />
<span class="BuyNowQuantity">{$lng.lbl_quantity}:</span> <b>{$lng.txt_out_of_stock}</b><br />

<div id="notify_tr1_{$products[product].productid}">
<I><a href="javascript: void(0);" onclick="javascript: $('#notify_tr1_{$products[product].productid}').hide(); $('#notify_tr2_{$products[product].productid}').show();" >Notify me when it's in stock</a></I>
</div>
<div id="notify_tr2_{$products[product].productid}" style="display: none;">

<form name="notifyform_{$products[product].productid}" method="post" 
action='{if $main eq "catalog"}{if $action_notify_url ne ""}{$action_notify_url}{else}home.php{/if}{elseif $main eq "brand_products"}brands.php{/if}'
>
<input type="hidden" name="productid" value="{$products[product].productid}" />
<input type="hidden" name="mode" value="notify" />
<B>Your email address:</B> <input type="text" name="notify_email" value="" />

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
		{include file="customer/main/buy_now.tpl" product=$products[product]}
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
<br />
{/if}
{if !%product.last%}
<hr style="border-bottom: 1px dashed #CCCCCC; border-top: 0px; border-left: 0px; border-right: 0px;" />
{/if}
{/section}
{if $active_modules.Feature_Comparison ne '' && $products && $printable ne 'Y' && $products_has_fclasses}
{include file="modules/Feature_Comparison/compare_selected_button.tpl" no_form=true}
{/if}


{* --- *}
{include file="customer/main/infinite_products.tpl" show_next_products="N"}
{* --- *}


{else}

{if $e_search_data.substring ne "" && $e_search_data.total eq 0}
{$lng.lbl_nothing_found_home_page}
{else}
{$lng.txt_no_products_found}
{/if}

{/if}
{/if}
