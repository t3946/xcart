{*
$Id: product.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{include file="form_validation_js.tpl"}

{include file="main/include_js.tpl" src="ajax_add_to_cart.js"}

{assign var="is_group" value=$oProduct->isGroup()}

{if $use_schema_org eq "Y"}
{if $current_storefront eq "0"}
{if $product.clean_url ne ""}
<meta id="so_url" itemprop="url" content="//www.artistsupplysource.com/{$product.clean_url}/" />
{else}
<meta id="so_url" itemprop="url" content="//www.artistsupplysource.com/product.php?productid={$product.productid}" />
{/if}
{else}
{if $product.clean_url ne ""}
<meta id="so_url" itemprop="url" content="//{$site_domain}/{$product.clean_url}/" />
{else}
<meta id="so_url" itemprop="url" content="//{$site_domain}/product.php?productid={$product.productid}" />
{/if}
{/if}
{/if}

{if $use_schema_org eq "Y"}
<link id="pm_1" itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#PaymentMethodCreditCard" />
<link id="pm_2" itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#VISA" />
<link id="pm_3" itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#MasterCard" />
<link id="pm_4" itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#PayPal" />


<meta itemscope="" itemtype="//schema.org/Product" itemref="so_image so_category so_name so_url so_description so_gtin so_weight so_brand so_manuf so_sku so_mpn so_model so_offer"/>

<div id="so_o_seller" itemprop="seller" itemscope="" itemtype="//schema.org/Organization">
	<meta itemprop="logo" content="//www.artistsupplysource.com/skin1_kolin/images/S3-Stores-Logo-S2.png"/>
	<meta itemprop="url" content="//www.s3stores.com/"/>
	<meta itemprop="name" content="S3 Stores Inc."/>
</div>

{assign var="oStorefront" value=$oProduct->getStoreFront()}

<span id="so_manuf" itemprop="manufacturer" itemscope="" itemtype="//schema.org/Organization">
	<span itemprop="name" content="{$product.manufacturer}">
	</span>
</span>

{if $cidev_mpn ne ""}
<meta id="so_mpn" itemprop="mpn" content="{$cidev_mpn}"/>
<meta id="so_model" itemprop="model" content="{$cidev_mpn}"/>
{/if}

<meta id="so_offer" itemprop="offers" itemscope="" itemtype="//schema.org/Offer" itemref="so_o_stock so_o_condition so_o_currency so_o_price so_o_function so_o_delivery so_o_seller pm_1 pm_2 pm_3 pm_4"/>
<div id="so_weight" itemprop="weight" itemscope="" itemtype="//schema.org/QuantitativeValue" itemref="so_weight_value">
	<meta itemprop="unitCode" content="lbs">
</div>
{if $cat_name_for_itemprop ne ""}
<meta id="so_category" itemprop="category" content="{$cat_name_for_itemprop}"/>
{/if}

<meta id="so_o_condition" itemprop="itemCondition" content="NewCondition"/>
<meta id="so_o_currency" itemprop="priceCurrency" content="USD">

<meta id="so_o_function" itemprop="businessFunction" href="//purl.org/goodrelations/v1#Sell"/>
<div id="so_o_delivery" itemprop="deliveryLeadTime"  itemscope="" itemtype="//schema.org/QuantitativeValue">
	<meta itemprop="value" content="6">
	<meta itemprop="unitText" content="days">
</div>
{/if}

{if $product.new_notify_in_stock_price ne ""}
        {assign var="current_price" value=$product.new_notify_in_stock_price}
{else}
        {if $product.map_price gt $product.taxed_price}
                {assign var="current_price" value=$product.map_price}
        {else}
                {assign var="current_price" value=$product.taxed_price}
        {/if}
{/if}

{if $product_wholesale.0.price ne "" && $product.new_notify_in_stock_price eq "" && $product.map_price lte $product.taxed_price}
        {assign var="current_price" value=$product_wholesale.0.price}
{/if}

<div class="product-details">
    {if $active_modules.Special_Offers || ($product.appearance.has_market_price and $product.appearance.market_price_discount gt 0)}
        {assign var="custom_top_info" value="true"}
    {/if}
    <div class="top-info ui-body ui-body-b ui-overlay-shadow">
        <div class="ui-grid-{if $active_modules.Special_Offers && $product.bonus_points gt 0}a{else}solo{/if}">
            <div class="ui-block-a">
                <h1 {if $main eq "product"}{if $use_schema_org eq "Y"} id="so_name" itemprop="name"{/if}{/if}>{$product.producttitle}</h1>
            </div>
            {if $active_modules.Special_Offers && $product.bonus_points gt 0}
                <div class="ui-block-b">
                    <div class="right-block bp-info">
                        <ul data-role="listview" data-inset="true">
                            <li data-theme="e" class="bp-info">
                                +{$product.bonus_points}&nbsp;{$lng.lbl_sp_ttl_bonus_points}
                            </li>
                        </ul>
                    </div>
                </div>
            {/if}
        </div>
        <div class="ui-grid-a">
            <div class="ui-block-a">
                <div class="sku{if $product.appearance.has_market_price and $product.appearance.market_price_discount gt 0} save-mark-here{/if}"> {if $main eq "product"}{if $use_schema_org eq "Y"}
                    <span id="so_sku"
                          itemprop="sku">{/if}{/if}{$product.productcode|escape}{if $main eq "product"}{if $use_schema_org eq "Y"}</span>{/if}{/if}
                </div>
                {if $product.distribution eq "" && !($product.product_type eq "C" and $active_modules.Product_Configurator)}
                    <div id="so_o_stock" itemprop="availability"
                         content="{if $product.product_availability eq "in stock"}InStock{else}OutOfStock{/if}"
                         class="product-quantity-text-top{if $product.avail gt 0 or $config.General.unlimited_products eq "Y"} in-stock{/if}">

                        {if $product.avail gt 0 or $config.General.unlimited_products eq "Y"}
                            {$lng.lbl_in_stock_top}
                        {else}
                            {$lng.lbl_out_stock}
                        {/if}

                    </div>
                {/if}
            </div>

            {if !($product.product_type eq "C" and $active_modules.Product_Configurator)}
                <div class="ui-block-b">
                    <div class="right-block">
                        <ul data-role="listview" data-inset="true">
                            {if $product.appearance.has_market_price and $product.appearance.market_price_discount gt 0}
                                {strip}
                                    <li data-theme="c" class="save-percent-container" id="save_percent_box">
                    <span class="save">
                      {$lng.lbl_save}&nbsp;
                      <span id="save_percent">{$product.appearance.market_price_discount}</span>%
                    </span>
                                    </li>
                                {/strip}
                            {/if}

                            {if
                                ($config.General.unlimited_products eq "N" and ($product.avail le 0 or $product.avail lt $product.min_amount) and $variants eq '' && $product_feed_enabled eq "Y" && $notify_when_in_stock[$product.productid] ne "Y")
                            ||
                                !($product.avail gt 0 or $config.General.unlimited_products eq "Y")
                            }

                            {else}
                                {if !$is_group}
                                <li data-theme="b" id="top-cart-button">
                                    {strip}
                                        <a href="{$catalogs.customer}/cart.php"

                                                {if $product.lead_time_message ne ""}
                                                    onclick="javascript: if (confirm('{$product.lead_time_message}')) {ldelim}  ajax_add_to_cart('{$product.productid}', '{$product.add_date}', 'product'); $('#orderform-{$product.productid}').submit(); {rdelim}"
                                                {else}
                                                    onclick="javascript: $('#orderform-{$product.productid}').submit();"
                                                {/if}

                                        >
                                            {currency value=$product.taxed_price tag_id=""}
                                            {if $product.appearance.added_to_cart}
                                                {$lng.lbl_add_more}
                                            {else}
                                                {$lng.lbl_add_to_cart}
                                            {/if}
                                        </a>
                                    {/strip}
                                </li>
                                {/if}
                            {/if}
                        </ul>
                    </div>
                </div>
            {/if}
        </div>
    </div>
</div>
<div class="product-details">
  <div class="image">
    <div class="image-box"{if $active_modules.Detailed_Product_Images and $images ne ''} style="display: block;"{/if}>
      {if $active_modules.Detailed_Product_Images and $images ne ''}
        <ul data-role="listview" data-inset="true">
          <li data-icon="false">
            <a href="{$current_location}/product.php?productid={$product.productid}&mobile_mode=get_detailed_images" class="ga_click" data-label="More Images">
            {/if}
                {include file="product_thumbnail.tpl" productid=$product.productid image_x=$product.image_x image_y=$product.image_y product=$producttitle tmbn_url=$product.tmbn_url id="product_thumbnail" type="P" splash=$product.oSplash}
            {if $active_modules.Detailed_Product_Images and $images ne ''}
            </a>
          </li>
        {/if}

        {if $active_modules.Detailed_Product_Images and $images ne ''}
          <li data-icon="plus" data-theme="b">
            <a href="{$current_location}/product.php?productid={$product.productid}&mobile_mode=get_detailed_images" class="ga_click" data-label="More Images">{$lng.lbl_more_images}</a>
          </li>
        </ul>
      {/if}
    </div>
  </div>
  <div class="details">
      {if !$is_group}
          {if $product.product_type eq "C" and $active_modules.Product_Configurator}
              {include file="modules/Product_Configurator/pconf_customer_product.tpl"}
          {else}
              {include file="customer/main/product_details.tpl"}
              {if $active_modules.Feature_Comparison ne ""}
                  {include file="modules/Feature_Comparison/product_buttons.tpl"}
              {/if}
          {/if}
      {/if}
  </div>
</div>
<div>
    {if $shipping_rate_show}
    {literal}
        <script type="text/javascript">
            ga('send', 'event', 'calculate shipping', 'showed', {nonInteraction: true});
        </script>
    {/literal}
        <span id="calculate_shipping_button" data-product-id="{$product.productid}" style="margin-top: -5px;" class="cidev_new_button cidev_new_white">Calculate shipping</span>
    {/if}
    <div id="calculate_shipping_text" class="hidden">
        <div colspan="2" class="shipping_info" style="padding: 20px 0;">

        </div>
    </div>
</div>

{if $product_tabs}
<script src="{$SkinDir}/check_email_script.js" type="text/javascript"></script>
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}
function check_question_email_form() {

        if ($("#email").val()!="" && $("#phone").val()!="" && $("#question").val()!=""){

                if (checkEmailAddress(document.product_question_email_form.email, 'Y')){
                  send_question_email_form();
                }

        } else {
                alert("Please fill in all fields");
                return false;
        }
}

function send_question_email_form(){

        cidev_xmlHttp=cidev_createHttpRequestObject();
        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                var cidev_parameters = 'cidev_mode=send&email=' + $("#email").val() + '&phone=' + $("#phone").val() + '&question=' + $("#question").val() + '&productid=' + $('#question_productid').val();

                cidev_xmlHttp.onreadystatechange=function(){
                        if(cidev_xmlHttp.readyState==4){
                                if(cidev_xmlHttp.status==200){
                                        cidev_id$("product_question_after").innerHTML=cidev_xmlHttp.responseText;
                                        $("#product_question_pre").hide();
                                }else{
                                        cidev_Error('no_server', 'Y');
                                }
                        }
                };

                cidev_xmlHttp.open('POST','product_question.php',true);
                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
//                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
//                cidev_xmlHttp.setRequestHeader('Connection','close');
                cidev_xmlHttp.send(cidev_parameters);
        }
        else {
                setTimeout('send_question_email_form()', 1000);
        }
}


{/literal}

//]]>
</script>


<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
  $(document).ready(function() {
        $('#email').focusout(function() {

                if ($('#email').val() != ""){
//                        checkEmailAddress(document.product_question_email_form.email, 'Y');
                }
        });
  });
{/literal}
//]]>
</script>


  {foreach from=$product_tabs item=tab key=ind}
    <div data-role="collapsible" data-collapsed="true" class="ga_click">
      <h3>{$tab.title}</h3>
      <div>
{*
{include file=$tab.tpl nodialog='Y'}
*}

        {if $tab.tpl eq "_product_description_"}

          {if $use_schema_org eq "Y"}<span id="so_description" itemprop="description">{/if}
            {if $product.seo_fulldescr ne ""}
                {$product.seo_fulldescr|stripslashes}
            {elseif $product.fulldescr ne ""}
                {$product.fulldescr|stripslashes}
            {else}
                {$product.descr|stripslashes}
            {/if}
            {if $use_schema_org eq "Y"}</span>{/if}

        {elseif $tab.tpl eq "_Brand_"}

<br />
{php}
    $this->assign("arr_schemas", [
        'brand' => [
            'id' => 'so_brand',
            'itemtype' => 'http://schema.org/Brand',
            'itemprop' => 'brand',
        ]
    ]);
{/php}
{capture name=dialog}

{if $brand_image.filename ne ""}
    {assign var="imagePath" value=$xcart_web_dir}
    {if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}
        {assign var="imagePath" value="//`$config.Appearance.CDN_domain`"}
    {/if}
<img src="{$imagePath}/images/B/{$brand_image.filename}" style="float: left; margin: 10px 10px 10px 0;" />
{/if}

<p align="justify">
{$brandid_brands_info[$product.brandid].descr}
<br />
<a href="/brands.php?brandid={$product.brandid}" class="NavigationPath">All {$brandid_brands_info[$product.brandid].brand} products</a>
</p>

{/capture}
{include file="dialog.tpl" title=$brandid_brands_info[$product.brandid].brand content=$smarty.capture.dialog schema='brand' title_itemprop='brand' extra='width="100%"' use_h2="Y" }

        {elseif $tab.tpl eq "_product_question_tpl_"}
{* --------------------------------------------------*}
<div id="product_question_pre">
{$lng.lbl_product_question_pre_instructions}
<br />
<br />
<form name="product_question_email_form" action="" method="POST" >
<table cellpadding="1" cellspacing="3" width="100%">

 <tr>
  <td align="right" class="cidev_padding_top">Your email:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
        <input type="text" id="email" name="email" size="32" maxlength="128" value="" />
        <input type="hidden" id="question_productid" name="question_productid" value="{$product.productid}" />
  </td>
 </tr>

 <tr>
  <td class="cidev_padding_top" align="right">Your phone:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
        <input type="text" id="phone" name="phone" size="32" maxlength="32" value="" onkeyup="cidev_check_field_phone('phone')" />
  </td>
 </tr>

 <tr>
  <td class="cidev_padding_top" align="right">Product question:
        <div class="cidev_checkout_descr">{$lng.lbl_FIELD_DESCRIPTION_Product_question}</div>
  </td>
  <td><font class="Star">*</font></td>
  <td>
        <textarea style="width: 98%" name="question" id="question" cols="60" rows="10"></textarea>
  </td>
 </tr>

 <tr>
  <td colspan="3" align="center">
        <input type="button" name="Submit question" value="Submit question" onclick="javasript: check_question_email_form();" />
{*
        {include file="buttons/button.tpl" button_title="Submit question" type="input" href="javascript: check_question_email_form();" js_to_href="Y" b="1"}
*}
  </td>
 </tr>

</table>
</form>
</div>

<div id="product_question_after"></div>


{if $product.product_questions ne ""}
<br />
<br />
{foreach from=$product.product_questions item=v_q key=k_q}

        {$v_q.question}<br />

        {if $v_q.answer ne ""}
                <div style="padding-left: 50px;">{$v_q.answer}</div>
        {/if}

{/foreach}
{/if}
{* --------------------------------------------------*}

        {elseif $tab.tpl eq "_product_discussions_tpl_"}
{* --------------------------------------------------*}

{*

<div id="disqus_thread"></div>
<script type="text/javascript">
//<![CDATA[
{literal}
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 's3stores';

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
{/literal}
//]]>
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>

*}

{* --------------------------------------------------*}

        {else}

{if $tab.title eq "Shipping"}
        {if $product.weight ne "0.00" || $variants ne '' || $show_dimensions}
        <br />
        {/if}

<table width="100%" cellpadding="0" cellspacing="0">

{if $product.upc_ean_isbn}
<tr>
        <td width="22%" nowrap="nowrap">{$product.upc_ean_isbn.type}:</td>
        <td nowrap="nowrap">{if $use_schema_org eq "Y"}<span id="so_gtin" itemprop="gtin13">{/if}{$product.upc_ean_isbn.value}{if $use_schema_org eq "Y"}</span>{/if}</td>
</tr>
{/if}

{if $product.weight ne "0.00" || $variants ne ''}

<tr id="product_weight_box">
        <td width="22%">Shipping weight:</td>
        <td nowrap="nowrap"><span id="so_weight_value" itemprop="value">{$product.weight|formatprice}</span> {$config.General.weight_symbol}</td>
</tr>
{/if}
{if $show_dimensions}
<tr>
        <td width="22%" nowrap="nowrap">{$lng.lbl_shipping_dimensions}:</td>
        <td nowrap="nowrap"><span id="product_weight">{$product.dim_x}" x {$product.dim_y}" x {$product.dim_z}"</span></td>
</tr>
{/if}
</table>
        {if $product.weight ne "0.00" || $variants ne '' || $show_dimensions}
        <br />
        {/if}
{/if}

                {$tab.tpl}
        {/if}


      </div>
    </div>
  {/foreach}
{/if}


{*
{if $product.cart_manufact_text_displayed ne ""}
<br />
{include file="customer/main/ui_tabs.tpl" prefix="product-tabs-" mode="inline" tabs=$product_tabs productid=$product.productid}
<br />
{/if}
*}

{if $active_modules.Product_Options and ($product_options ne '' or $product_wholesale ne '') and ($product.product_type ne "C" or not $active_modules.Product_Configurator)}
  <script type="text/javascript">
    //<![CDATA[
    check_options();
    //]]>
  </script>
{/if}

{if $is_group}
    {include file="modules/Xcart_Mobile/customer/main/group_product_line.tpl"}
{/if}

{if $config.Security.ssl_seal ne ""}
<br />{$config.Security.ssl_seal}
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

{literal}
<script type="text/javascript">
    $('#calculate_shipping_button').on('click', function(e){

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
    $('#product_avail').on('change', function(){
        $('#calculate_shipping_text').fadeOut();
    })
</script>
{/literal}