{if $use_schema_org eq "Y"}
    {if $current_storefront eq "0"}
        {if $product.clean_url ne ""}
            <meta id="so_url" itemprop="url" content="//www.artistsupplysource.com/{$product.clean_url}/"/>
        {else}
            <meta id="so_url" itemprop="url"
                  content="//www.artistsupplysource.com/product.php?productid={$product.productid}"/>
        {/if}
    {else}
        {if $product.clean_url ne ""}
            <meta id="so_url" itemprop="url" content="//{$site_domain}/{$product.clean_url}/"/>
        {else}
            <meta id="so_url" itemprop="url" content="//{$site_domain}/product.php?productid={$product.productid}"/>
        {/if}
    {/if}
{/if}
<br>

    {assign var="producttitle" value=$oProduct->getTitle()}

{if $product.new_notify_in_stock_price ne ""}
    {assign var="current_price" value=$product.new_notify_in_stock_price}
{else}
    {if $product.map_price gt $product.taxed_price}
        {assign var="current_price" value=$product.map_price}
    {else}
        {assign var="current_price" value=$product.taxed_price}
    {/if}
{/if}

{assign var="is_group" value=$oProduct->isGroupRoot()}

{if $active_modules.Special_Offers}
    {include file="modules/Special_Offers/customer/product_offers_short_list.tpl" product=$product}
{/if}
{include file="form_validation_js.tpl"}
{if $product.product_type eq "C" && $active_modules.Product_Configurator}
    {include file="modules/Product_Configurator/pconf_customer_product.tpl"}
{else}
    {capture name=dialog}

        {if $config.General.unlimited_products eq "N" and ($product.avail le 0 or $product.avail lt $product.min_amount) and $variants eq '' && $product_feed_enabled eq "Y"}
            <form name="notifyform" method="post" action="/product.php">
                <input type="hidden" name="productid" value="{$product.productid}"/>
                <input type="hidden" id="notify_mode" name="mode" value=""/>
                <input type="hidden" id="notify_email" name="notify_email"
                       value="{if $notify_email ne ""}{$notify_email}{/if}"/>
                <input type="hidden" name="storefrontid" value="{$current_storefront}"/>
            </form>
        {/if}
        <form name="orderform" method="post" action="/cart.php?mode=add" onsubmit="return FormValidation();">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td rowspan="3" height="300" width="300"
                        style="border: 1px dashed #cccccc; text-align: center; vertical-align: middle;">
                        {if $active_modules.Detailed_Product_Images ne "" && $config.Detailed_Product_Images.det_image_popup eq 'Y' && $images ne '' && $js_enabled eq 'Y'}
                            {include file="modules/Detailed_Product_Images/popup_image.tpl"}
                        {else}
                            {if $active_modules.Detailed_Product_Images ne "" && $images ne ''}
                                <a style="font-size: 0px;" href="#dp_images" class="ga_click" data-label="More Images">
                            {/if}
                            {if $oProduct && $oProduct->isGroupRoot()}
                                {include file="group_thumbnail.tpl" product=$oProduct}
                            {else}
                                {include file="product_thumbnail.tpl" productid=$product.productid image_x=$product.image_x image_y=$product.image_y product=$producttitle tmbn_url=$product.tmbn_url id="product_thumbnail" type="P" splash=$product.oSplash}
                            {/if}
                            {if $active_modules.Detailed_Product_Images ne "" && $images ne ''}
                                </a>
                            {/if}
                        {/if}
                        {if $active_modules.Magnifier ne "" && $config.Magnifier.magnifier_image_popup eq 'Y' && $zoomer_images ne '' && $js_enabled eq 'Y'}
                            {include file="modules/Magnifier/popup_magnifier.tpl"}
                        {/if}
                        {if $config.Appearance.code_below_thumb}
                            <table width="300">
                                <tr>
                                    <td align="right">
                                        <div style="margin-top: -35px;">
                                            <table cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>{$config.Appearance.code_below_thumb|substitute:"prn":"`$product.product`"|substitute:"url":"`$current_location`/product.php?productid=`$product.productid`"}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        {/if}
                    </td>
                    {if !$is_group}
                        <td valign="top" width="140" style="padding-left: 20px;" rowspan="3">
                            {if $product.map_price lt $product.taxed_price}
                                {include file="customer/main/product_prices.tpl"}
                            {/if}
                        </td>
                        <td valign="top" width="*" style="padding-left: 16px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                {if $product.lbl_minimum_order_amount_message_product eq "Y" && $product.d_minimum_order_amount_in_us ne ""}
                                    <tr height="20" id="minimum_order_amount_wrap"
                                        data-minimum-amount="{$product.d_minimum_order_amount_in_us}">
                                        <td>&nbsp</td>
                                    </tr>
                                {/if}
                                {if $current_price gt 0 and $product.list_price gt 0 and $product.list_price gt $current_price}
                                    <tr>
                                        <td nowrap="nowrap" class="BlackT" valign="top"
                                            style="padding-right: 3px;">{$lng.lbl_list_price}:
                                        </td>
                                        <td>
                                            <font style="font-size: 12px; color: #7b7b7b;"><strike>{include file="currency.tpl" value=$product.list_price plain_text_message=true price_type="list_price"}</strike></font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="5"></td>
                                    </tr>
                                {/if}

                                {if $active_modules.Feature_Comparison ne ""}
                                    {include file="modules/Feature_Comparison/product.tpl"}
                                {/if}
                                {if $active_modules.Subscriptions ne "" and $subscription}
                                    {include file="modules/Subscriptions/subscription_info.tpl"}
                                {else}
                                    <tr>
                                        <td width="93" class="BlackT" valign="middle">{$lng.lbl_price}:</td>
                                        <td width="*" valign="middle">
                                            {if $current_price ne 0 || $variant_price_no_empty}

                                                {if $product.new_notify_in_stock_price ne "" && $current_price eq $product.new_notify_in_stock_price}
                                                    <input type="hidden" name="new_notify_in_stock_price"
                                                           id="new_notify_in_stock_price"/>
                                                {/if}
                                                <span class="ProductPriceConverting2"><span id="product_price"
                                                                                            style="white-space: nowrap;">{include file="currency.tpl" value=$current_price plain_text_message=true price_type="product_price"}</span></span>
                                                <font class="MarketPrice"> <span id="product_alt_price"
                                                                                 style="white-space: nowrap;">{include file="customer/main/alter_currency_value.tpl" alter_currency_value=$current_price plain_text_message=true}</span></font>
                                                {if $product.map_price gt $product.taxed_price}
                                                    <br/>
                                                    <span class="map_price_help">{$config.Product_Page.map_bridge_text}</span>
                                                {/if}
                                            {else}
                                                <input type="text" size="7" name="price"/>
                                            {/if}
                                        </td>
                                    </tr>
                                    {if $product.taxes}
                                        {include file="customer/main/taxed_price.tpl" taxes=$product.taxes product_page_tax="Y"}
                                    {/if}
                                {/if}

                                {if $current_price gt 0 and $product.list_price gt 0 and $product.list_price gt $current_price}
                                    {math equation="100-(price/lprice)*100" price=$current_price lprice=$product.list_price format="%3.5f" assign=discount}
                                    {if $discount gte 1}
                                        <TR id="save_percent_box"{if $product.taxed_price >= $product.list_price} style="display: none;"{/if}>
                                            <TD nowrap="nowrap" colspan="2">

                                                <br/>
                                                <table cellpadding="1" cellspacing="1" class="discount_class2">
                                                    <tr>
                                                        <td><img src="{$ImagesDir}/new/product/dollar.png" alt=""/></td>
                                                        <td class="discount_class1">Discount:</td>
                                                        <td class="discount_class">
                                                            <SPAN id="save_percent">{$discount|string_format:"%3.0f"}</SPAN>&nbsp;OFF&nbsp;
                                                        </td>
                                                    </tr>
                                                </table>
                                            </TD>
                                        </TR>
                                    {/if}
                                {/if}

                                <tr>
                                    <td colspan="2" height="20"></td>
                                </tr>

                                {if $config.Appearance.show_in_stock eq "Y" and $config.General.unlimited_products ne "Y" and $product.distribution eq "" && $product.avail <= $config.Appearance.quantity_threshold && $product.avail gt 0}
                                    <tr id="so_o_stock" itemprop="availability"
                                        content="{if $product.product_availability eq "in stock"}InStock{else}OutOfStock{/if}">
                                        <td width="10%" class="BlackT">{$lng.lbl_in_stock}:</td>
                                        <td nowrap="nowrap" id="product_avail_txt" class="BlackT">
                                            {if $product.avail gt 0}{$lng.txt_items_available|substitute:"items":$product.avail}{else}{$lng.lbl_no_items_available}{/if}
                                        </td>
                                    </tr>
                                {/if}

                                <tr id="so_o_stock" itemprop="availability"
                                    content="{if $product.product_availability eq "in stock"}InStock{else}OutOfStock{/if}">
                                    <td height="25" class="BlackT">{$lng.lbl_quantity}:</td>
                                    <td style="text-align:left;font-size: 16px;" width="*">
                                        {if $config.General.unlimited_products eq "N" and ($product.avail le 0 or $product.avail lt $product.min_amount || $product.product_availability == 'out of stock') and $variants eq '' }
                                            <script type="text/javascript">
                                                var min_avail = 1;
                                                var avail = 0;
                                                var product_avail = 0;
                                            </script>
                                            <b>{$lng.txt_out_of_stock}</b>
                                        {else}
                                            {if $config.General.unlimited_products eq "Y"}
                                                {assign var="mq" value=$config.Appearance.max_select_quantity}
                                            {else}
                                                {math equation="x/y" x=$config.Appearance.max_select_quantity y=$product.min_amount assign="tmp"}
                                                {assign var="minamount" value=$product.min_amount}
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

                                            <script type="text/javascript">
                                                var min_avail = {$start_quantity|default:1};
                                                var avail = {$mq|default:1}-1;
                                                var product_avail = {$product.avail|default:"0"};
                                                {literal}

                                                function func_dec_inc_qty(type_of_action, qty_step) {

                                                    var qty_val = $("#product_avail").val();

                                                    if (qty_val == "" || qty_val == 0) {
                                                        qty_val = min_avail;
                                                    }

                                                    qty_val = parseInt(qty_val);
                                                    qty_step = parseInt(qty_step);

                                                    if (type_of_action == "inc") {
                                                        qty_val += qty_step;
                                                    }

                                                    if (type_of_action == "dec") {
                                                        qty_val = qty_val - qty_step;
                                                    }

                                                    if (qty_val > product_avail) {
                                                        qty_val = avail;
                                                    }

                                                    if (qty_val < min_avail) {
                                                        qty_val = min_avail;
                                                    }

                                                    $("#product_avail").val(qty_val);

                                                    if (qty_val == 1) {
                                                        $("#qty-dec").addClass("disabled");
                                                    } else {
                                                        $("#qty-dec").removeClass("disabled");
                                                    }

                                                    check_wholesale(qty_val);
                                                }

                                                function check_min_amount_step(mult_order_quantity, min_amount) {
                                                    if (mult_order_quantity == 'Y' && min_amount > 1) {

                                                        var m_order_quantity = mult_order_quantity;
                                                        var m_amount = min_amount;

                                                        var ceil_amount;
                                                        var new_qty_val;
                                                        var qty_val;

                                                        qty_val = $("#product_avail").val();
                                                        ceil_amount = qty_val / m_amount;
                                                        ceil_amount = Math.ceil(ceil_amount);
                                                        new_qty_val = ceil_amount * m_amount;

                                                        if (qty_val != new_qty_val) {

                                                            setTimeout(function () {

                                                                qty_val = $("#product_avail").val();
                                                                ceil_amount = qty_val / m_amount;
                                                                ceil_amount = Math.ceil(ceil_amount);
                                                                new_qty_val = ceil_amount * min_amount;
                                                                if (qty_val != new_qty_val) {
                                                                    $("#product_avail").val(new_qty_val);
                                                                    check_wholesale(new_qty_val);
                                                                }
                                                            }, 2000);
                                                        }
                                                    }
                                                }
                                                {/literal}
                                            </script>

                                            <div class="product_attr quantity clearfix">
                                                <a rel="nofollow"
                                                   class="oper reduce{if $start_quantity|default:1 eq "1"} disabled{/if}"
                                                   href="javascript:void(0);" id="qty-dec"
                                                   onclick="func_dec_inc_qty('dec', '{$step}');"></a>
                                                <input type="text" value="{$start_quantity|default:1}" class="quantity"
                                                       id="product_avail" name="amount"
                                                       onkeyup="check_wholesale(this.value); check_min_amount_step('{$product.mult_order_quantity}', '{$product.min_amount}');">
                                                <a rel="nofollow" class="oper add" href="javascript:void(0);"
                                                   id="qty-inc"
                                                   onclick="func_dec_inc_qty('inc', '{$step}');"></a>
                                            </div>
                                        {else}
                                            <script type="text/javascript">
                                                var min_avail = 1;
                                                var avail = 1;
                                                var product_avail = 1;
                                            </script>
                                            <font class="ProductDetailsTitle">1</font>
                                        <input type="hidden" name="amount"
                                               value="1"/> {if $product.distribution ne ""}{$lng.txt_product_downloadable}{/if}
                                        {/if}
                                        {/if}
                                    </td>
                                </tr>

                                {include file="modules/Product_Options/customer_options.tpl"}

                                {if $product.eta_date_in_future eq "Y"}
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>Expected availability:</td>
                                        <td>{$product.eta_date_mm_dd_yyyy|date_format:'%d-%b-%Y'}</td>
                                    </tr>
                                    {if $product.allow_pre_orders ne "Y"}
                                        <tr>
                                            <td colspan="2">Sorry we don't take pre-orders.</td>
                                        </tr>
                                    {/if}
                                {/if}

                                {if $config.General.unlimited_products eq "N" and ($product.avail le 0 or $product.avail lt $product.min_amount) and $variants eq '' && $product_feed_enabled eq "Y" && $notify_when_in_stock[$product.productid] ne "Y"}
                                    {if $product.eta_date_in_future ne "Y"}
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                    {/if}
                                    <tr id="notify_tr1">
                                        <td colspan="2">
                                            <I><a href="javascript: void(0);"
                                                  onclick="javascript: $('#notify_tr1').hide(); $('#notify_tr2').show();">Notify
                                                    me when it's in stock</a></I>
                                        </td>
                                    </tr>
                                    <tr id="notify_tr2" style="display: none;">
                                        <td>Your email address:</td>
                                        <td>
                                            <input type="text" name="notify_email"
                                                   value="{if $notify_email ne ""}{$notify_email}{/if}"/>
                                            {include file="buttons/button.tpl" button_title="Notify me" style="button" href="javascript:if (checkEmailAddress(document.orderform.notify_email, 'Y')) `$ldelim`document.notifyform.mode.value='notify';document.notifyform.notify_email.value=document.orderform.notify_email.value;submit_product_notify_form(this);`$rdelim`"}
                                    <tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                {/if}

                                {if $product.min_amount gt 1}
                                    <tr>
                                        <td colspan="2">
                                            <font class="ProductDetailsTitleWithoutBold">{if $product.mult_order_quantity eq "Y"}{$lng.txt_need_min_amount_mult|substitute:"items":$product.min_amount}{else}{$lng.txt_need_min_amount|substitute:"items":$product.min_amount}{/if}</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                {/if}
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            {if $current_price gt 0 and $product.list_price gt 0 and $product.list_price gt $current_price}
                                {math equation="100-(price/lprice)*100" price=$current_price lprice=$product.list_price format="%3.5f" assign=discount}
                                {if $discount gte 1}
                                    <div id="save_percent_box2"{if $product.taxed_price gte $product.list_price} style="display: none;"{/if}
                                         class="discount_class3">
                                        <div class="discount_class4">
                                        </div>

                                        <div class="discount_class8">
                                            <table cellspacing="0" cellpadding="0" border="0" width="140">
                                                <tr>
                                                    <td class="discount_class5">SAVE</td>
                                                    <td width="10">&nbsp;</td>
                                                    <td class="discount_class6" id="save_percent2"></td>
                                                    <td class="discount_class7" valign="bottom">%</td>
                                                </tr>
                                            </table>
                                        </div>

                                    </div>
                                {/if}
                            {/if}
                        </td>
                        <td rowspan="3" class="save_td">&nbsp;</td>
                    {else}
                        <td valign="top" width="*" style="padding-left: 20px; vertical-align: middle" class="full_product_cell">
                            <div class="btn_full_product_line"></div>
                            <div class="full_line_info">Click here to see full product line</div>
                            {if $config.Security.ssl_seal ne ""}
                            <div class="seal">
                               {$config.Security.ssl_seal}
                            </div>
                            {/if}

                        </td>
                    {/if}
                </tr>
                {if !$is_group}
                    <tr>
                        <td colspan="2" style="padding-left: 16px;">
                            <div class="line_subtotal"></div>
                        </td>
                    </tr>
                    <tr style="height: 165px;">
                        <td valign="top" style="padding-left: 16px;">
                            <table cellspacing="0" cellpadding="0" border="0">
                                {if $product.min_amount gte 1 && $product.product_availability eq "in stock"}
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="93" class="subtotal_class1">
                                            {if $product_subtotal_value eq ""}
                                                {math equation="price*quantity" price=$current_price quantity=$product.min_amount format="%3.5f" assign=product_subtotal_value}
                                            {/if}
                                            Subtotal:
                                        </td>
                                        <td>
                                            <div class="subtotal_class2"
                                                 id="product_subtotal_value">{include file="currency.tpl" value=$product_subtotal_value plain_text_message=true price_type="product_subtotal_value"}</div>
                                        </td>
                                    </tr>
                                {/if}

                                <tr>
                                    <td colspan="2">
                                        <input type="hidden" name="mode" value="add"/>

                                        <input type="hidden" name="pbrand" id="pbrand"
                                               value="{$product.brand|escape:quotes}"/>
                                        <input type="hidden" name="pname" id="pname"
                                               value="{$product.product|escape:quotes}"/>
                                        <input type="hidden" name="pcategory" id="pcategory"
                                               value="{$product.category|escape:quotes}"/>
                                        <input type="hidden" name="pcategory" id="pcategory"
                                               value="{$product.category|escape:quotes}"/>
                                        <input type="hidden" name="ga_page_name" id="ga_page_name"
                                               value="{$ga_page_name}"/>


                                        {if $config.General.unlimited_products eq "Y" or ($product.avail gt 0 and $product.avail ge $product.min_amount)}
                                            {if $js_enabled}
                                                <script type="text/javascript">
                                                    var lbl_added = "{$lng.lbl_added}";
                                                    var lbl_error = "{$lng.lbl_error}";
                                                </script>
                                                <br/>
                                            {if $product.forsale ne "B"}
                                                <table cellspacing="0" cellpadding="0" border="0">
                                                    <tr>
                                                        <td id="add2cart_{$product.productid}" nowrap="nowrap">
                                                            {if $product.lead_time_message ne ""}
                                                                {include file="buttons/buy_now.tpl" style="button" href="javascript: if ('`$config.General.opt_ajax_cart`' == 'Y') if (confirm('`$product.lead_time_message`')) ajax_add_to_cart(`$product.productid`, `$product.add_date`, 'product'); if (!('`$config.General.opt_ajax_cart`' == 'Y')) document.orderform.submit();" b=1 class="ajax_button" add_to_cart_btn="big"}
                                                            {else}
                                                                {include file="buttons/buy_now.tpl" style="button" href="javascript: if ('`$config.General.opt_ajax_cart`' == 'Y') ajax_add_to_cart(`$product.productid`, `$product.add_date`, 'product'); else document.orderform.submit();" b=1 class="ajax_button" add_to_cart_btn="big"}
                                                            {/if}
                                                        </td>
                                                    </tr>
                                                </table>
                                            {else}
                                                {$lng.txt_pconf_product_is_bundled}
                                            {/if}
                                            {if $smarty.get.pconf ne "" && $active_modules.Product_Configurator}
                                            <br/><br/>
                                            <input type="hidden" name="slot" value="{$smarty.get.slot}"/>
                                            <input type="hidden" name="addproductid" value="{$product.productid}"/>
                                                {include file="buttons/button.tpl" button_title=$lng.lbl_pconf_add_to_configuration style="button" href="javascript:if (FormValidation()) `$ldelim`document.orderform.productid.value='`$smarty.get.pconf`';document.orderform.action='pconf.php';document.orderform.submit()`$rdelim`"}
                                            {if $config.General.unlimited_products ne "Y" && $product.pconf_avail le 0}
                                            <br/>
                                                <font class="Message"><b>{$lng.lbl_note}
                                                        :</b> {$lng.lbl_pconf_slot_out_of_stock_note}
                                                </font>
                                            <br/>
                                            {/if}
                                            <br/>
                                                {$lng.txt_add_to_configuration_note}
                                            <br/>
                                            {/if}
                                            {else}
                                                {include file="submit_wo_js.tpl" value=$lng.lbl_add_to_cart}
                                            {/if}
                                        {/if}
                                        {if $active_modules.Feature_Comparison ne ""}
                                            {include file="modules/Feature_Comparison/product_buttons.tpl"}
                                        {/if}

                                        {if $config.Security.ssl_seal ne ""}
                                            <br/>
                                            {$config.Security.ssl_seal}
                                        {/if}

                                        {if $variant_id_for_point5 eq "0"}
                                            <br/>
                                            <br/>
                                            {assign var="social_buttons_data_services" value=$config.Appearance.social_buttons_data_services}
                                            {$config.Appearance.social_buttons_script_code|replace:"[data-services]":"$social_buttons_data_services"|replace:"[size]":"big"}
                                        {/if}

                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td width="196">
                            {if $shipping_rate_show}
                                <script type="text/javascript">
                                    {literal}
                                    ga('send', 'event', 'calculate shipping', 'showed', {nonInteraction: true});
                                    {/literal}
                                </script>
                                <span id="calculate_shipping_button" data-product-id="{$product.productid}"
                                      style="margin-top: -5px;"
                                      class="cidev_new_button cidev_new_white">Show shipping</span>
                            {/if}
                        </td>
                    </tr>
                    {if $shipping_rate_show}
                        <tr id="calculate_shipping_text" class="hidden">
                            <td colspan="2"></td>
                            <td colspan="2" class="shipping_info"></td>
                        </tr>
                    {/if}
                    {if $oProduct && $oProduct->isGroupChild() && $oProduct->parent}
                        <tr>
                            <td colspan="4" style="line-height: 18px;">&nbsp;</td>
                        </tr>
                        <tr class="full_product_line_button">
                            <td colspan="2"></td>
                            <td colspan="2" style="padding-left: 16px;">
                                <span style="font-size: 19px" class="cidev_new_button cidev_new_white" onclick="self.location = '{$oProduct->parent->getUrl()}'">See other product variations</span></td>
                        </tr>
                    {/if}
                {/if}
            </table>
            <input type="hidden" name="productid" value="{$product.productid}"/>
            <input type="hidden" name="cat" value="{$smarty.get.cat|escape:"html"}"/>
            <input type="hidden" name="page" value="{$smarty.get.page|escape:"html"}"/>
        </form>
    {/capture}
    {include file="dialog.tpl" title=$producttitle content=$smarty.capture.dialog extra='width="100%"' product=$product save_label="true" product_sku=$product.productcode product_free_ship=$product.free_ship_text use_h1="Y" lbl_minimum_order_amount_message_product=$product.lbl_minimum_order_amount_message_product d_minimum_order_amount_in_us=$product.d_minimum_order_amount_in_us}
{/if}
{if $product.upc_ean_isbn}
    <br/>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="22%" class="BlackT">{$product.upc_ean_isbn.type}:</td>
            <td nowrap="nowrap">{if $use_schema_org eq "Y"}<span id="so_gtin"
                                                                 itemprop="gtin13">{/if}{$product.upc_ean_isbn.value}{if $use_schema_org eq "Y"}</span>{/if}
            </td>
        </tr>
    </table>
{/if}


{if $product.cart_manufact_text_displayed ne ""}
    <br/>
    {include file="customer/main/ui_tabs.tpl" prefix="product-tabs-" mode="inline" tabs=$product_tabs productid=$product.productid}
    <br/>
{/if}

{if $is_group}
    {include file="customer/main/group_product_line.tpl"}
{/if}

{if $active_modules.Magnifier ne "" && ($config.Magnifier.magnifier_image_popup ne 'Y' || $js_enabled ne 'Y')}
    <p/>
    {include file="modules/Magnifier/product_magnifier.tpl" productid=$product.productid}
{/if}
{if $config.Appearance.send_to_friend_enabled eq 'Y'}
    <p/>
    {include file="customer/main/send_to_friend.tpl" }
{/if}
{if $active_modules.Detailed_Product_Images ne "" && ($config.Detailed_Product_Images.det_image_popup ne 'Y' || $js_enabled ne 'Y')}
    <p/>
    <a name="dp_images"></a>
    {include file="modules/Detailed_Product_Images/product_images.tpl" }
{/if}

<br/>
{if $oProduct && $oProduct->isGroupChild() && $oProduct->parent}
    <div style="text-align: center;" class="full_product_line_button">
            <span style="font-size: 19px" class="cidev_new_button cidev_new_white" onclick="self.location = '{$oProduct->parent->getUrl()}'">See other product variations</span>
    </div>
    <br/>
    <br/>
{/if}

<div id="products_also_bought_with_this_product"
     style="display: none;">{include file="customer/main/ajax_carousel_products.tpl" section_name="products_also_bought_with_this_product" section_title=$lng.lbl_products_also_bought_with_this_product}</div>

<br/>

<div id="related_products"
     style="display: none;">{include file="customer/main/ajax_carousel_products.tpl" section_name="related_products" section_title=$lng.lbl_related_products}</div>

<br/>

<div id="similar_products"
     style="display: none;">{include file="customer/main/ajax_carousel_products.tpl" section_name="similar_products" section_title=$lng.lbl_similar_products}</div>

<br/>
<div id="similar_products_ob"
     style="display: none;">{include file="customer/main/ajax_carousel_products.tpl" section_name="similar_products_ob" section_title="Similar products other brands"}</div>

<br/>

<div id="recently_viewed_products"
     style="display: none;">{include file="customer/main/ajax_carousel_products.tpl" section_name="recently_viewed_products" section_title=$lng.lbl_recently_viewed_products}</div>

<script type="text/javascript">
    func_load_ALL_ajax_carousels("products_also_bought_with_this_product,related_products,similar_products,recently_viewed_products", 0);
</script>

{if $active_modules.Recommended_Products ne ""}
    {if $recommends}
        <br/>
        <p/>
    {/if}
    {include file="modules/Recommended_Products/recommends.tpl" }
{/if}
{if $active_modules.Customer_Reviews ne ""}
    <p/>
    {include file="modules/Customer_Reviews/vote_reviews.tpl" }
{/if}
{if $active_modules.Product_Options ne ''}
    <script type="text/javascript">
        check_options();
    </script>
{/if}
{literal}
    <script type="text/javascript">
        $('#calculate_shipping_button').on('click', function (e) {

            $('#calculate_shipping_text').find('.shipping_info').html('Please wait...').attr('align', 'center').end().fadeIn();

            var qty = parseInt($('#product_avail').val());

            if (!e.ctrlKey) {
                ga('send', 'event', 'click', 'Shipping calculation', 'Quantity', qty);
            }
            $.get(
                '/cidev_ajax_suggestions.php',
                {
                    product_id: $(this).data('product-id'),
                    qty: qty,
                    section_name: 'shipping'
                },
                function (data) {
                    $('#calculate_shipping_text')
                        .find('.shipping_info')
                        .html(data)
                        .attr('align', 'left')
                        .end();
                });

        });
        $('#qty-inc, #qty-dec').on('click', function () {
            $('#calculate_shipping_text').fadeOut();
        });

        $('#product_avail').on('keyup', function () {
            $('#calculate_shipping_text').fadeOut();
        })
    </script>
{/literal}
