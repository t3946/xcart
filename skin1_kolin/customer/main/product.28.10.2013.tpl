{* $Id: product.tpl,v 1.147.2.11 2006/11/29 06:21:14 max Exp $ *}
{if $use_schema_org eq "Y"}
{if $current_storefront eq "0"}
<meta itemprop="url" content="http://www.artistsupplysource.com/product.php?productid={$product.productid}">
{else}
<meta itemprop="url" content="http://{$cidev_store_domain}/product.php?productid={$product.productid}">
{/if}
{/if}
<br>
{if $product.map_price gt $product.taxed_price}
{assign var="current_price" value=$product.map_price}
{else}
{assign var="current_price" value=$product.taxed_price}
{/if}
{include file="main/include_js.tpl" src="main/popup_image.js"}
{if $active_modules.Special_Offers}
{include file="modules/Special_Offers/customer/product_offers_short_list.tpl" product=$product}
{/if}
{include file="form_validation_js.tpl"}
{if $product.product_type eq "C" && $active_modules.Product_Configurator}
{include file="modules/Product_Configurator/pconf_customer_product.tpl"}
{else}
{capture name=dialog}
<form name="orderform" method="post" action="cart.php?mode=add" onsubmit="javascript: return FormValidation();">
<table width="100%" border="0">
<tr>
	<td {* class="PImgBox" *} {* rowspan="2" *} height="300" width="300" style="border: 1px dashed #cccccc; text-align: center; vertical-align: middle;">
{if $active_modules.Detailed_Product_Images ne "" && $config.Detailed_Product_Images.det_image_popup eq 'Y' && $images ne '' && $js_enabled eq 'Y'}
{include file="modules/Detailed_Product_Images/popup_image.tpl"}
{else}
{if $active_modules.Detailed_Product_Images ne "" && $images ne ''}
<a style="font-size: 0px;" href="http://{if $cidev_store_domain ne ""}{$cidev_store_domain|lower}{else}www.artistsupplysource.com{/if}/{$canonical_url}#dp_images">{/if}{include file="product_thumbnail.tpl" productid=$product.productid image_x=$product.image_x image_y=$product.image_y product=$product.product tmbn_url=$product.tmbn_url id="product_thumbnail" type="P"}{if $active_modules.Detailed_Product_Images ne "" && $images ne ''}</a>{/if}
{/if}
{if $active_modules.Magnifier ne "" && $config.Magnifier.magnifier_image_popup eq 'Y' && $zoomer_images ne '' && $js_enabled eq 'Y'}
{include file="modules/Magnifier/popup_magnifier.tpl"}
{/if}
	</td>
	<td valign="top" width="140" style="padding-left: 20px;">


{if $product.map_price lt $product.taxed_price}
{include file="customer/main/product_prices.tpl"}
{/if}


	</td>
	<td valign="top" width="*" style="padding-left: 16px;">

<table width="100%" cellpadding="0" cellspacing="0">

{if $current_price gt 0 and $product.list_price gt 0 and $product.list_price gt $current_price}
<tr>
<td nowrap="nowrap" class="BlackT" width="30%" valign="top">{$lng.lbl_list_price}:</td>
<td><font style="{* FONT-FAMILY: strickeout; *} font-size: 12px; color: #848C84;"><strike>{include file="currency.tpl" value=$product.list_price plain_text_message=true}</strike></font></td>
</tr>
{/if}

{if $active_modules.Feature_Comparison ne ""}
{include file="modules/Feature_Comparison/product.tpl"}
{/if}
{if $active_modules.Subscriptions ne "" and $subscription}
{include file="modules/Subscriptions/subscription_info.tpl"}
{else}
<tr>
<td width="30%" class="ProductPriceConverting" valign="top">{$lng.lbl_price}:</td>
<td width="70%" valign="top">
{if $current_price ne 0 || $variant_price_no_empty}
<font class="ProductPriceConverting"><span id="product_price" style="white-space: nowrap;">{include file="currency.tpl" value=$current_price plain_text_message=true}</span></font>
<font class="MarketPrice"> <span id="product_alt_price" style="white-space: nowrap;">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$current_price plain_text_message=true}</span></font>
{if $product.map_price gt $product.taxed_price}
<br />
<span class="map_price_help">{$config.Product_Page.map_bridge_text}</span>
{/if}
{else}
<input type="text" size="7" name="price" />
{/if}
</td>
</tr>

{if $product.taxes}
{* <tr><td colspan="2" nowrap="nowrap"> *}
{include file="customer/main/taxed_price.tpl" taxes=$product.taxes product_page_tax="Y"}
{* </td></tr> *}
{/if}


{/if}
{* {if $active_modules.Product_Options ne ""} *}
{ include file="modules/Product_Options/customer_options.tpl"}
{* {/if} *}


{if $current_price gt 0 and $product.list_price gt 0 and $product.list_price gt $current_price}
{math equation="100-(price/lprice)*100" price=$current_price lprice=$product.list_price format="%3.5f" assign=discount}
{if $discount gte 1}
<TR id="save_percent_box"{if $product.taxed_price >= $product.list_price} style="display: none;"{/if}>
<TD nowrap="nowrap">
<font style="font-size: 12px; color: #CC3333;">You save:</font>
</TD>
<TD nowrap="nowrap" style="font-size: 12px; font-weight: normal; color: #CC3333;">
<SPAN id="save_percent">{$discount|string_format:"%3.0f"}</SPAN>
</TD>
</TR>
{/if}
{/if}



<tr><td colspan="2" height="20"></td></tr>

{if $config.Appearance.show_in_stock eq "Y" and $config.General.unlimited_products ne "Y" and $product.distribution eq "" && $product.avail <= $config.Appearance.quantity_threshold && $product.avail gt 0}
<tr>
        <td width="10%" class="BlackT">{$lng.lbl_in_stock}:</td>
        <td nowrap="nowrap" id="product_avail_txt" class="BlackT">
{if $product.avail gt 0}{$lng.txt_items_available|substitute:"items":$product.avail}{else}{$lng.lbl_no_items_available}{/if}
        </td>
</tr>
{/if}

<tr><td height="25" width="30%" class="BlackT_new">{$lng.lbl_quantity}:</td>
<td style="text-align:left;width:70% !important; font-size: 16px;" width="70%">
{if $config.General.unlimited_products eq "N" and ($product.avail le 0 or $product.avail lt $product.min_amount) and $variants eq ''}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var min_avail = 1;
var avail = 0;
var product_avail = 0;
-->
</script>
<b>{$lng.txt_out_of_stock}</b>
{else}
{if $config.General.unlimited_products eq "Y"}
{assign var="mq" value=$config.Appearance.max_select_quantity}
{else}
{math equation="x/y" x=$config.Appearance.max_select_quantity y=$product.min_amount assign="tmp"}
{*if $tmp<2*}
{assign var="minamount" value=$product.min_amount}
{*else*}
{*assign var="minamount" value=1*}
{*/if*}
{assign var="step" value="1"}
{if $product.mult_order_quantity eq "Y"}
{assign var="step" value=$product.min_amount}
{else}
{/if}
{math equation="min(maxquantity*step+minamount, productquantity+1)" assign="mq" maxquantity=$config.Appearance.max_select_quantity minamount=$minamount productquantity=$product.avail step=$step}
{/if}
{if $product.distribution eq "" and !($active_modules.Subscriptions ne "" and $subscription)}
{if $product.min_amount le 1}
{assign var="start_quantity" value=1}
{else}
{assign var="start_quantity" value=$product.min_amount}
{/if}
{if $config.General.unlimited_products eq "Y"}
{math equation="x+y" assign="mq" x=$mq y=$start_quantity}
{/if}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var min_avail = {$start_quantity|default:1};
var avail = {$mq|default:1}-1;
var product_avail = {$product.avail|default:"0"};
-->
</script>




{* <select id="product_avail" name="amount"{if $active_modules.Product_Options ne '' && $product_options ne ''} onchange="check_wholesale(this.value);"{/if}> *}
<select id="product_avail" name="amount" onchange="check_wholesale(this.value);" style="font-size: 16px;">
{section name=quantity loop=$mq start=$start_quantity step=$step}
<option value="{%quantity.index%}" {if $smarty.get.quantity eq %quantity.index%}selected{/if}>{%quantity.index%}</option>
{/section}
</select>
{else}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var min_avail = 1;
var avail = 1;
var product_avail = 1;
-->
</script>
<font class="ProductDetailsTitle">1</font><input type="hidden" name="amount" value="1" /> {if $product.distribution ne ""}{$lng.txt_product_downloadable}{/if}
{/if}
{/if}
</td></tr>


{if $product.eta_date_in_future eq "Y"}
<tr>
<td>Expected availability:</td>
<td>{$product.eta_date_dd_month_yyyy}</td>
</tr>
<tr><td colspan="2">Sorry we don't take pre-orders.</td></tr>
{/if}


{if $product.min_amount gt 1}
<tr><td colspan="2">
<font class="ProductDetailsTitleWithoutBold">{if $product.mult_order_quantity eq "Y"}{$lng.txt_need_min_amount_mult|substitute:"items":$product.min_amount}{else}{$lng.txt_need_min_amount|substitute:"items":$product.min_amount}{/if}</font>
</td></tr>
</table>





 
<table width="100%" cellspacing="0" cellpadding="0">
{/if}
<tr><td colspan="2">
<input type="hidden" name="mode" value="add" />

{if $config.General.unlimited_products eq "Y" or ($product.avail gt 0 and $product.avail ge $product.min_amount)}
{if $js_enabled}
{include file="main/include_js.tpl" src="ajax_add_to_cart.js"}
<script type="text/javascript">
var lbl_added = "{$lng.lbl_added}";
var lbl_error = "{$lng.lbl_error}";
</script>


{if $product.min_amount gt 1}
<br />
{math equation="price*quantity" price=$current_price quantity=$product.min_amount format="%3.5f" assign=product_subtotal_value}
<div style="font-size: 16px; color: #000000; font-weight: bold;" id="product_subtotal_value">Subtotal: {include file="currency.tpl" value=$product_subtotal_value plain_text_message=true}</div>
{/if}


<br />
{if $product.forsale ne "B"}
<table cellspacing="0" cellpadding="0" border="0" {* height="42" width="203"*}>
<tr>
	{*
	<td>{include file="buttons/add_to_cart.tpl" style="button" href="javascript: if(FormValidation()) document.orderform.submit();"*}
	<td id="add2cart_{$product.productid}" nowrap="nowrap">


{include file="buttons/buy_now.tpl" style="button" href="javascript: if ('`$config.General.opt_ajax_cart`' == 'Y') ajax_add_to_cart(`$product.productid`, `$product.add_date`, 'product'); else document.orderform.submit();" b=1 class="ajax_button" add_to_cart_btn="big"}

{*
{include file="buttons/buy_now.tpl" style="button" href="javascript: if ('`$config.General.opt_ajax_cart`' == 'Y') ajax_add_to_cart(`$product.productid`, `$product.add_date`, 'product'); else document.orderform.submit();" b=1 class="ajax_button" new_add_to_cart_btn="Y"}
*}

{*
<a id="btn-add-to-cart" class="btn_atcart_b" rel="nofollow" href="http://cart.dx.com/shoppingcart.dx/add.54370">
<span class="t">Add To Cart</span>
</a>
*}

	{*
	<a href="javascript: if(FormValidation()) document.orderform.submit();" class="VertMenuItems"><font color=#0000FF><b>Add to cart</b></font></a>
	*}
	</td>
{*
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
{if ($login ne "" || $config.Wishlist.add2wl_unlogged_user eq 'Y') && $active_modules.Wishlist ne ""}
{include file="customer/add2wl.tpl"}
{/if}
	</td>
*}
</tr>
</table>
{else}
{$lng.txt_pconf_product_is_bundled}
{/if}
{if $smarty.get.pconf ne "" && $active_modules.Product_Configurator}
<br /><br />
<input type="hidden" name="slot" value="{$smarty.get.slot}" />
<input type="hidden" name="addproductid" value="{$product.productid}" />
{include file="buttons/button.tpl" button_title=$lng.lbl_pconf_add_to_configuration style="button" href="javascript:if (FormValidation()) `$ldelim`document.orderform.productid.value='`$smarty.get.pconf`';document.orderform.action='pconf.php';document.orderform.submit()`$rdelim`"}
{if $config.General.unlimited_products ne "Y" && $product.pconf_avail le 0}
<br />
<font class="Message"><b>{$lng.lbl_note}:</b> {$lng.lbl_pconf_slot_out_of_stock_note}</font><br />
{/if}
<br />
{$lng.txt_add_to_configuration_note}
<br />
{/if}
{else}
{include file="submit_wo_js.tpl" value=$lng.lbl_add_to_cart}
{/if}
{/if}
{if $active_modules.Feature_Comparison ne ""}
{include file="modules/Feature_Comparison/product_buttons.tpl"}
{/if}

{if $config.Security.ssl_seal ne ""}
<br />{$config.Security.ssl_seal}
{/if}

</td>
</tr></table>
</td>
</tr>
</table>
<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="cat" value="{$smarty.get.cat|escape:"html"}" />
<input type="hidden" name="page" value="{$smarty.get.page|escape:"html"}" />
</form>
{/capture}
{include file="dialog.tpl" title=$product.producttitle content=$smarty.capture.dialog extra='width="100%"' product=$product save_label="true" product_sku=$product.productcode product_free_ship=$product.free_ship_text use_h1="Y"}
{/if}


        {if $config.Appearance.code_below_thumb}
                <table width="300">
                <tr>
                        <td align="right">
<div style="margin-top: -56px; margin-left: -39px;">
                                <table cellpadding="0" cellspacing="0">
                                <tr>
                                        <td>{$config.Appearance.code_below_thumb|substitute:"prn":"`$product.product`"|substitute:"url":"`$current_location`/product.php?productid=`$product.productid`"}</td>
                                </tr>
                                </table>
                        </td>
                </tr>
                </table>
</div>
        {/if}


<br />
{capture name=dialog}
<div style="padding-left: 8px;">
<span style="font-size: 13px; color: #000000;" class="SPItems-description">{if $use_schema_org eq "Y"}<span itemprop="description">{/if}{if $product.fulldescr ne ""}{$product.fulldescr}{else}{$product.descr}{/if}{if $use_schema_org eq "Y"}</span>{/if}</span>

{if $product.weight ne "0.00" || $variants ne '' || $show_dimensions || $product.upc_ean_isbn}
{* <br /> *}
<br />
{/if}

{if $use_schema_org eq "Y"}
{* <div itemprop="name" itemscope itemtype="http://schema.org/Product"> *}
{/if}

<table width="100%" cellpadding="0" cellspacing="0">

{if $product.weight ne "0.00" || $variants ne ''}
<tr id="product_weight_box">
        <td width="22%">{$lng.lbl_weight}:</td>
        <td nowrap="nowrap"><span id="product_weight">{$product.weight|formatprice}</span> {$config.General.weight_symbol}</td>
</tr>
{if $use_schema_org eq "Y"}
<meta itemprop="weight" content="{$product.weight|formatprice} {$config.General.weight_symbol}">
{/if}
{/if}
{if $show_dimensions}
<tr>
        <td width="22%" nowrap="nowrap">{$lng.lbl_shipping_dimensions}:</td>
        <td nowrap="nowrap"><span id="product_weight">{$product.dim_x}" x {$product.dim_y}" x {$product.dim_z}"</span></td>
</tr>
{/if}
{if $product.upc_ean_isbn}
<tr>
        <td width="22%" class="BlackT">{$product.upc_ean_isbn.type}:</td>
        <td nowrap="nowrap">{if $use_schema_org eq "Y"}<span itemprop="gtin13">{/if}{$product.upc_ean_isbn.value}{if $use_schema_org eq "Y"}</span>{/if}</td>
</tr>
{/if}
{if $active_modules.Extra_Fields ne ""}
{include file="modules/Extra_Fields/product.tpl"}
{/if}
</table>

{if $use_schema_org eq "Y"}
{if $current_storefront eq "0"}
<meta itemprop="logo" content="http://www.artistsupplysource.com/image.php?type=P&id={$product.productid}">
{else}
<meta itemprop="logo" content="http://{$cidev_store_domain}/image.php?type=P&id={$product.productid}">
{/if}

<meta itemprop="brand" content="{$product.cidev_brand_name}">
<meta itemprop="manufacturer" content="{$product.manufacturer}">
<meta itemprop="sku" content="{$product.productcode}">
{if $cidev_mpn ne ""}
<meta itemprop="mpn" content="{$cidev_mpn}">
{/if}

<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

{if $cat_name_for_itemprop ne ""}
<meta itemprop="category" content="{$cat_name_for_itemprop}" />
{/if}

{if $product.avail gt "0"}
<link itemprop="availability" href="http://schema.org/InStock"/>
{/if}

<link itemprop="itemCondition" href="http://schema.org/NewCondition"/>
{*
<div itemprop="itemCondition" itemscope itemtype="http://schema.org/NewCondition">
<meta itemprop="itemCondition" content="NewCondition">
</div>
<div itemprop="availability" itemscope itemtype="http://schema.org/InStock">
<meta itemprop="availability" content="{if $product.avail gt "0"}In stock{else}Out of stock{/if}">
</div>
*}
<meta itemprop="businessFunction" content="sell">
<meta itemprop="deliveryLeadTime" content="3">
<meta itemprop="price" content="{$current_price}">
<meta itemprop="priceCurrency" content="USD">
<meta itemprop="seller" content="S3 Stores Inc.">
</div>

{* </div> *} {* end http://schema.org/Product  *}
{/if}

</div>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_product_description content=$smarty.capture.dialog extra='width="100%"' }

{if $product.cart_manufact_text_displayed ne ""}
<br />
{include file="customer/main/ui_tabs.tpl" prefix="product-tabs-" mode="inline" tabs=$product_tabs}
<br />
{/if}

{if $active_modules.Magnifier ne "" && ($config.Magnifier.magnifier_image_popup ne 'Y' || $js_enabled ne 'Y')}
<p />
{include file="modules/Magnifier/product_magnifier.tpl" productid=$product.productid}
{/if}
{if $config.Appearance.send_to_friend_enabled eq 'Y'}
<p />
{include file="customer/main/send_to_friend.tpl" }
{/if}
{if $active_modules.Detailed_Product_Images ne "" && ($config.Detailed_Product_Images.det_image_popup ne 'Y' || $js_enabled ne 'Y')}
<p />
<a name="dp_images"></a>
{include file="modules/Detailed_Product_Images/product_images.tpl" }
{/if}
{if $active_modules.Upselling_Products ne ""}
{if $product_links}
<p />
{/if}
{include file="modules/Upselling_Products/related_products.tpl" }
{/if}

{if $similar_products ne ""}
<br /> 
<p />
{include file="customer/main/similar_products.tpl"}
{/if}
{if $active_modules.Recommended_Products ne ""}
{if $recommends}
<br />
<p />
{/if}
{include file="modules/Recommended_Products/recommends.tpl" }
{/if}
{if $active_modules.Customer_Reviews ne ""}
<p />
{include file="modules/Customer_Reviews/vote_reviews.tpl" }
{/if}
{* {if $active_modules.Product_Options ne '' && $product_options ne ''} *}
{if $active_modules.Product_Options ne ''}
<script type="text/javascript" language="JavaScript 1.2">
<!--
check_options();
-->
</script>
{/if}
