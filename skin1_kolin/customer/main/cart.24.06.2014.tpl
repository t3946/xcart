{* $Id: cart.tpl,v 1.95.2.3 2007/01/08 07:04:56 max Exp $ *}
{assign var="subtotal_shipping_charge" value=0}
{if $active_modules.Product_Options}
{include file="main/include_js.tpl" src="modules/Product_Options/edit_product_options.js"}
{/if}

<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
function cidev_update_product_amount(cartid, manufacturerid){

	var productindex_id = 'productindex_' + cartid;
	var hidden_productindex_id = 'hidden_productindex_' + cartid;
	var amount = $("#" + productindex_id).val();
	var hidden_amount = $("#" + hidden_productindex_id).val();

	amount = amount.replace(/[^0-9]/g, '');
	$('#'+productindex_id).val(amount);

	if (amount > '0'){

		var check_amount = amount;
		check_amount = check_amount.replace(/^0*/g, '');
		if (check_amount != amount){
			amount = check_amount;
			$("#" + productindex_id).val(amount);
		}

		if (hidden_amount != amount && amount > '0'){
			$('#'+hidden_productindex_id).val(amount);
			setTimeout('cidev_update_product_amount_next('+cartid+','+amount+','+manufacturerid+')', 600);
		}
	}
	else if (amount == '' || amount == '0') {
		setTimeout('cidev_empty_amount('+cartid+','+manufacturerid+')', 800);
	}
}

function cidev_empty_amount(cartid, manufacturerid){

	var hidden_productindex_id = 'hidden_productindex_' + cartid;
	var productindex_id = 'productindex_' + cartid;
	var amount = $("#" + productindex_id).val();

	if (amount == "" || amount == '0'){
		$('#'+productindex_id).css('background','#E01B1B');

		new_amount = "1";
		$("#" + productindex_id).val(new_amount);
		$('#'+hidden_productindex_id).val(new_amount);

		setTimeout('cidev_update_product_amount_next('+cartid+','+new_amount+','+manufacturerid+')', 600);
	}
}

function cidev_update_product_amount_next(cartid, amount, manufacturerid){

        var productindex_id = 'productindex_' + cartid;
        var current_amount = $("#" + productindex_id).val();
        current_amount = current_amount.replace(/[^0-9]/g, '');

	if (current_amount == amount){
	
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'action=update&cartid=' + cartid + '&amount=' + amount + '&manufacturerid=' + manufacturerid;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){

                                                        cidev_id$("cidev_cart_subtotal").innerHTML=cidev_xmlHttp.responseText;


							var cidev_hidden_display_price_cid = 'cidev_hidden_display_price_' + cartid;
							if (cidev_id$(cidev_hidden_display_price_cid)){
								var cidev_hidden_display_price_val = $("#" + cidev_hidden_display_price_cid).val();


								var cidev_hidden_set_new_amount_cid = 'cidev_hidden_set_new_amount_' + cartid;
								if (cidev_id$(cidev_hidden_set_new_amount_cid)){

									$('#'+productindex_id).css('background','#E01B1B');

									new_amount = $("#" + cidev_hidden_set_new_amount_cid).val();
									$("#" + productindex_id).val(new_amount);

									var hidden_productindex_id = 'hidden_productindex_' + cartid;
									$('#'+hidden_productindex_id).val(new_amount);

									setTimeout('cidev_update_product_amount_next('+cartid+','+new_amount+','+manufacturerid+')', 600);
									return false;
								}

								$('#'+productindex_id).css('background','#ffffff');
				
								var cidev_display_price_cid = 'cidev_display_price_' + cartid;
								if (cidev_id$(cidev_display_price_cid)){
									cidev_id$(cidev_display_price_cid).innerHTML = cidev_hidden_display_price_val;

									var cidev_hidden_price_on_amount_cid = 'cidev_hidden_price_on_amount_' + cartid;
									var cidev_hidden_price_on_amount_val = $("#" + cidev_hidden_price_on_amount_cid).val();
									var cidev_price_on_amount_cid = 'cidev_price_on_amount_' + cartid;
									cidev_id$(cidev_price_on_amount_cid).innerHTML = cidev_hidden_price_on_amount_val;
								}
							}

						        var cidev_hidden_deliv_subt_mid = 'cidev_hidden_deliv_subt_' + manufacturerid;
							if (cidev_id$(cidev_hidden_deliv_subt_mid)){
        							var cidev_hidden_deliv_subt_mid_amount = $("#" + cidev_hidden_deliv_subt_mid).val();

						        	var cidev_shipping_groups_deliv_subt_mid = 'cidev_shipping_groups_deliv_subt_' + manufacturerid;
								if (cidev_id$(cidev_shipping_groups_deliv_subt_mid)){
									cidev_id$(cidev_shipping_groups_deliv_subt_mid).innerHTML = cidev_hidden_deliv_subt_mid_amount;
								}
							}

                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_cart.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_update_product_amount_next('+cartid+','+amount+','+manufacturerid+')', 600);
                        }
	}
}

{/literal}
//]]>
</script>


{* <h3>{$lng.lbl_your_shopping_cart}</h3> *}
{if $cart ne ''}
{*$lng.txt_cart_header*}
{if $active_modules.Gift_Certificates ne ""}
{$lng.txt_cart_note}
{/if}
{/if}
<p />
{*
{capture name=dialog}
{$lng.txt_cart_header}
*}
{if $active_modules.Special_Offers}
{include file="modules/Special_Offers/customer/cart_offers.tpl"}
{/if}
<p />
{if $products ne ""}
<form action="cart.php" method="post" name="cartform">
<table width="100%" cellpadding="0" cellspacing="0">
{foreach from=$cart.shipping_groups item=v key=k name=shipping_groups_f}
<tr>
<td colspan="3" valign="top" class="DialogTitleBox"><img src="{$ImagesDir}/spacer.gif" width="1" height="1" alt="" >
</td>
</tr>
<tr>
<td class="DialogTitle" colspan="2" valign="top" style="background-color: #FEF6F3;">

<b>{*{$v.manufact_text_displayed}*}{$lng.lbl_items_shipped_from_warehouse} {$v.m_city}, {$v.m_state_code}, {if $v.m_country_code eq "US"}USA{else}{$v.m_country}{/if}</b>
{* <div style="margin-top: -6px;"><hr size="1" noshade="noshade" /></div> *}


{* <table><tr><td class="DialogTitle">{$v.group_name}</td></tr><tr><td>{$v.manufact_text_displayed}</td></tr></table></td> *}
</tr>
<tr><td colspan="2"><br /></td></tr> 
{assign var="deliv_subt" value="0"}
{section name=product loop=$products}
{if ($products[product].manufacturerid eq $k and $products[product].shipping_freight ne '0') or ($k eq $artss_manufacturerid and $products[product].shipping_freight eq '0')}
{math equation="x+y" x=$deliv_subt y=$products[product].display_subtotal assign="deliv_subt"}

{if $products[product].hidden eq ""}
<tr><td class="PListImgBox">
<a href="product.php?productid={$products[product].productid}">
{if $products[product].is_pimage eq 'W' }
	{assign var="imageid" value=$products[product].variantid}
	{include file="product_thumbnail.tpl" productid=$imageid image_x=$config.Appearance.thumbnail_width product=$products[product].product tmbn_url=$products[product].pimage_url type=$products[product].is_pimage}
{else}
	{assign var="imageid" value=$products[product].productid}
	{include file="product_thumbnail.tpl" productid=$products[product].productid image_x=$config.Appearance.thumbnail_width product=$products[product].product tmbn_url=$products[product].tmbn_url}
{/if}
</a>
{if $active_modules.Special_Offers ne "" and $products[product].have_offers}
{include file="modules/Special_Offers/customer/product_offer_thumb.tpl" product=$products[product]}
{/if}
</td>
<td valign="top">
<a href="product.php?productid={$products[product].productid}"><font class="ProductTitle">{$products[product].product}</font></a>
<br>
<font color="#006600" class="DialogTitleT">SKU: {$products[product].productcode}</font>
<br>
<br>
<table cellpadding="0" cellspacing="0" width="100%"><tr><td>
{$products[product].descr}
</td></tr></table>
<br />
{if $products[product].product_options ne ""}
<b>{$lng.lbl_selected_options}:</b><br />
{include file="modules/Product_Options/display_options.tpl" options=$products[product].product_options}
<br />
<br />
{/if}
{assign var="price" value=$products[product].display_price}
{if $active_modules.Product_Configurator ne "" and $products[product].product_type eq "C"}
{include file="modules/Product_Configurator/pconf_customer_cart.tpl" main_product=$products[product]}
{assign var="price" value=$products[product].pconf_display_price}
<br /><br />
{/if}
<div align="left">
{if $active_modules.Subscriptions ne "" and $products[product].sub_plan ne "" and $products[product].product_type ne "C"}
{include file="modules/Subscriptions/subscription_priceincart.tpl" product=$products[product]}
{else}
{if $active_modules.Special_Offers}
{include file="modules/Special_Offers/customer/cart_price_special.tpl"}
{/if}
<font class="ProductPriceConverting">

<span id="cidev_display_price_{$products[product].cartid}">
{include file="currency.tpl" value=$price} 
</span>


x {if $active_modules.Egoods and $products[product].distribution}1<input type="hidden"{else}


<input type="text" {if $main eq "fast_lane_checkout"} autocomplete="off" style="background: #ffffff;" id="productindex_{$products[product].cartid}" onkeyup="cidev_update_product_amount('{$products[product].cartid}', '{$products[product].manufacturerid}')"{/if}  size="3"{/if} name="productindexes[{$products[product].cartid}]" value="{$products[product].amount}" /> = </font>

<input type="hidden" id="hidden_productindex_{$products[product].cartid}" value="{$products[product].amount}" />


<font class="ProductPrice">
{math equation="price*amount" price=$price amount=$products[product].amount format="%.2f" assign=unformatted}

<span id="cidev_price_on_amount_{$products[product].cartid}">
{include file="currency.tpl" value=$unformatted}
</span>

</font>

<font class="MarketPrice"> {include file="customer/main/alter_currency_value.tpl" alter_currency_value=$unformatted}</font>
<br />
{assign var="cartid" value=$products[product].cartid}
{if $mult_amount_warns ne "" && $mult_amount_warns[$cartid] ne ""}
<font class="ProductDetailsTitleWithoutBold">{$lng.txt_warn_increase|substitute:'start':$mult_amount_warns[$cartid].old:'end':$mult_amount_warns[$cartid].new}</font>
<br />
{/if}
{math equation="price*amount" price=$products[product].shipping_freight amount=$products[product].amount assign=charge}

{math equation="subtotal_shipping_charge+charge" charge=$charge subtotal_shipping_charge=$subtotal_shipping_charge assign=subtotal_shipping_charge}

{if $config.Taxes.display_taxed_order_totals eq "Y" and $products[product].taxes}<br />{include file="customer/main/taxed_price.tpl" taxes=$products[product].taxes price_in_cart="Y"}
{/if}
{if $active_modules.Special_Offers}
{include file="modules/Special_Offers/customer/cart_free.tpl"}
{/if}
{/if}
<br />
<table cellspacing="0" cellpadding="0">
{if $shippings[$k] eq "" && $login ne ""}
<tr>
	<td class="ButtonsRow" colspan="2">
	<font color="red">{$lng.lbl_no_shipping_for_location}</font>
	</td>
</tr>
{/if}
<tr>
{*
	<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_update_qty type="input" href="javascript: document.cartform.submit()" js_to_href="Y"}</td>
*}
	<td class="ButtonsRow">{include file="buttons/delete_item.tpl" href="cart.php?mode=delete&amp;productindex=`$products[product].cartid`"}</td>
	<td class="ButtonsRow">

{if $products[product].product_options ne ''}
{if $config.UA.platform eq 'MacPPC' && $config.UA.browser eq 'MSIE'}
{include file="buttons/edit_product_options.tpl" id=$products[product].cartid js_to_href="Y"}
{else}
{include file="buttons/edit_product_options.tpl" id=$products[product].cartid}
{/if}
{/if}
	</td>
</tr>
</table>
{if $gcheckout_display_product_note and $products[product].valid_for_gcheckout eq 'N'}
<br />
{$lng.lbl_gcheckout_product_disabled}
{/if}
</div>
</td></tr>
<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
{/if}
{/if}
{/section}
{if $catalog_checkboxes[$k]}
<tr>
	<td colspan="2">
		<table cellpadding="2" cellspacing="0">
		<tr>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="add_catalog[{$k}]" value="Y" id="cc_{$k}" onclick="javascript: add_catalog({$k});" /></td>
			<td>{$catalog_checkboxes[$k]}</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
{/if}



<tr>
<td colspan="2">
<table cellpadding="3" cellspacing="0" width="30%" align="right" border="0">
<tr>
<td nowrap="nowrap">
<font color="#006600"><b>{*{$v.group_name}*}{$v.m_city}, {$v.m_state_code}, {if $v.m_country_code eq "US"}USA{else}{$v.m_country}{/if}&nbsp;warehouse&nbsp;{$lng.lbl_subtotal_sg}:</b></font>
</td>
<td><img src="{$ImagesDir}/null.gif" width="5" height="1" alt="" /><br /></td>
<td width="60" nowrap="nowrap" align="right"><font class="ProductPriceSmall"><span id="cidev_shipping_groups_deliv_subt_{$k}">{include file="currency.tpl" value=$deliv_subt}</span></font>&nbsp;</td>
{* <td>&nbsp;</td> *}
</tr>
</table>
<br />
<br />
<br />
</td>
</tr>



{/foreach}
<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
</table>
{if $active_modules.Gift_Certificates ne ""}
{include file="modules/Gift_Certificates/gc_cart.tpl" giftcerts_data=$cart.giftcerts}
{/if}
{if $main eq "fast_lane_checkout"}
<div id="cidev_cart_subtotal">
{include file="modules/Fast_Lane_Checkout/cart_subtotal.tpl"}
</div>
{else}
{include file="customer/main/cart_totals.tpl"}
{/if}
{$lng.lbl_your_mer_subtotal}<br /><br />
{if $js_enabled}
{if $active_modules.Fast_Lane_Checkout}
<div align="left" width="100%">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td nowrap="nowrap">{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_continue_shopping style="button" href="home.php`$last_categoryid`"}</td>
		<td nowrap="nowrap">{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_shipping_quote bold="N" style="button" href="javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" js_to_href="Y"}</td>
		<td width="30%">&nbsp;</td>
		<td align="right">
<table>
<tr>
<td align="center">
{if $cart.paymentid ne ""}
{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_checkout style="button" href="cart.php?mode=checkout&review=y&paymentid=`$cart.paymentid`" color="red" arrow="Y"}
{else}
{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_checkout style="button" href="cart.php?mode=checkout" color="red" arrow="Y"}
{/if}
</td>
</tr>
<tr>
<td align="center">
<font style="color: #000000"><I>{$lng.lbl_continue_checkout_0}</I></font>
</td>
</tr>
</table>
		</td>
	</tr>
{*	<tr><td colspan="4" align="right"><font style="color: #000000"><I>{$lng.lbl_continue_checkout_0}</I></font></td></tr> *}
	</table>
</div>
{else}
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
<table cellspacing="0" cellpadding="0">
<tr>
	<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_shipping_quote type="input" href="javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" js_to_href="Y" b="1"}</td>
	<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_update_qties type="input" href="javascript: document.cartform.submit()" js_to_href="Y"}</td>
	<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_clear_cart href="cart.php?mode=clear_cart"}</td>
</tr>
</table>
</td>
{if $active_modules.Special_Offers}
{include file="modules/Special_Offers/customer/cart_checkout_buttons.tpl"}
{/if}
<td align="right">
{if $gcheckout_enabled}
{include file="modules/Google_Checkout/gcheckout_button.tpl"}
{else}
{include file="buttons/button.tpl" button_title=$lng.lbl_checkout style="button"  href="cart.php?mode=checkout" b="1"}
{/if}
</td>
</tr>
</table>
{/if}
{else}
<input type="hidden" name="mode" value="checkout" />
{include file="submit_wo_js.tpl" value=$lng.lbl_checkout}
{/if}
</form>
{if $catalog_checkboxes}
<form name="catalogform" action="cart.php" method="post">
	<input type="hidden" name="mode" value="add_catalog" />
	<input type="hidden" name="cc_manufacturerid" id="cc_manufacturerid" value="" />
</form>

<script type="text/javascript">
<!--
{literal}

	function add_catalog(id) {
		if (document.getElementById('cc_' + id).checked) {
			document.catalogform.cc_manufacturerid.value = id;
			document.catalogform.submit();
		}
	}

{/literal}
-->
</script>
{/if}
{else}
{$lng.txt_your_shopping_cart_is_empty}
{/if}
{*
{/capture}
{include file="dialog.tpl" title=$lng.lbl_items_in_cart content=$smarty.capture.dialog extra='width="100%"'}
*}
