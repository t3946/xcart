{*
$Id: product_details.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<script type="text/javascript">
  //<![CDATA[
  var has_options = false;
  {literal}
    // workaround for the disabled "ajax add to cart" widget
  var ajax = {widgets: {
          add2cart : function () { return false }
        }
      }
  {/literal}
    //]]>
</script>

{if $product.new_notify_in_stock_price ne ""}
        {assign var="current_price" value=$product.new_notify_in_stock_price}
{else}
        {if $product.map_price gt $product.taxed_price}
                {assign var="current_price" value=$product.map_price}
        {else}
                {assign var="current_price" value=$product.taxed_price}
        {/if}
{/if}


{if $config.General.unlimited_products eq "N" and ($product.avail le 0 or $product.avail lt $product.min_amount) and $variants eq '' && $product_feed_enabled eq "Y"}
<form name="notifyform" method="post" action="product.php">
<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" id="notify_mode" name="mode" value="" />
<input type="hidden" id="notify_email" name="notify_email" value="" />
</form>
{/if}



<form name="orderform" method="post" action="{$catalogs.customer}/cart.php" onsubmit="javascript: return FormValidation(this);" id="orderform-{$product.productid}">
  <input type="hidden" name="mode" value="{if $active_modules.Gift_Registry and $wishlistid}wl2cart{else}add{/if}" />
  <input type="hidden" name="productid" value="{$product.productid}" />
  <input type="hidden" name="cat" value="{$smarty.get.cat|escape:"html"}" />
  <input type="hidden" name="page" value="{$smarty.get.page|escape:"html"}" />

  <input type="hidden" name="pbrand" id="pbrand" value="{$product.brand|escape:quotes}" />
  <input type="hidden" name="pname" id="pname" value="{$product.product|escape:quotes}" />
  <input type="hidden" name="pcategory" id="pcategory" value="{$product.category|escape:quotes}" />


  {if $active_modules.Gift_Registry and $wishlistid}
    <input type="hidden" name="fwlitem" value="{$wishlistid}" />
    <input type="hidden" name="eventid" value="{$eventid}" />
  {/if}
  {if $product.forsale neq "B" or ($product.forsale eq "B" and $smarty.get.pconf ne "" and $active_modules.Product_Configurator)}
    <div class="product-properties">
{*
      {if not $product.appearance.quantity_input_box_enabled}
        <fieldset data-role="controlgroup">
        {/if}
*}
{*
        {if $active_modules.Product_Options ne ""}
          {include file="modules/Product_Options/customer_options.tpl" disable=$lock_options}
        {/if}
*}
        {if $product.appearance.empty_stock and ($variants eq '' or ($variants ne '' and $product.avail le 0))}
          <label class="property-name product-input">{$lng.lbl_quantity}</label>
          <span class="property-value">
            <script type="text/javascript">
              //<![CDATA[
              var min_avail = 1;
              var avail = 0;
              var product_avail = 0;
              //]]>
            </script>
            <strong>{$lng.txt_out_of_stock}</strong>
          </span>
        {elseif not $product.appearance.force_1_amount and $product.forsale ne "B"}

{ include file="modules/Product_Options/customer_options.tpl"}

<table width="100%" cellpadding="0" cellspacing="0" style="font-size: 20px;">

{if $current_price gt 0 and $product.list_price gt 0 and $product.list_price gt $current_price}
<tr>
<td nowrap="nowrap" class="BlackT" width="30%" valign="top">{$lng.lbl_list_price}:</td>
<td><font style="{* FONT-FAMILY: strickeout; *} font-size: 20px; color: #848C84;"><strike>{include file="currency.tpl" value=$product.list_price plain_text_message=true price_type="list_price"}</strike></font></td>
</tr>
{/if}

<tr>
<td width="30%" class="ProductPriceConverting" valign="top">{$lng.lbl_price}:</td>
<td width="70%" valign="top">
{if $current_price ne 0 || $variant_price_no_empty}

        {* --- *}
        {if $product.new_notify_in_stock_price ne "" && $current_price eq $product.new_notify_in_stock_price}
                <input type="hidden" name="new_notify_in_stock_price" id="new_notify_in_stock_price" />
        {/if}
        {* --- *}

<font class="ProductPriceConverting"><span id="product_price" style="white-space: nowrap;">{include file="currency.tpl" value=$current_price plain_text_message=true  price_type="product_price"}</span></font>
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

{if $current_price gt 0 and $product.list_price gt 0 and $product.list_price gt $current_price}
{math equation="100-(price/lprice)*100" price=$current_price lprice=$product.list_price format="%3.5f" assign=discount}
{if $discount gte 1}
{math equation="lprice - price" price=$current_price lprice=$product.list_price format="%3.5f" assign=saved_price}
<TR id="save_percent_box"{if $product.taxed_price >= $product.list_price} style="display: none;"{/if}>
<TD nowrap="nowrap">
<font style="font-size: 20px; color: #CC3333;">You save:</font>
</TD>
<TD nowrap="nowrap" style="font-size: 12px; font-weight: normal; color: #CC3333;">
<SPAN id="save_percent">${$saved_price|price_format} ({$discount|string_format:"%3.0f"|replace:" ":""}%)</SPAN>
</TD>
</TR>
{/if}
{/if}

{*
</table>
*}

{*

          {capture name="qty_title"}
            {if $config.Appearance.show_in_stock eq "Y" and not $product.appearance.quantity_input_box_enabled and $config.General.unlimited_products ne 'Y'}
              {$lng.lbl_quantity_x|substitute:quantity:$product.avail}
            {else}
              {$lng.lbl_quantity}
            {/if}
          {/capture}
          {if $product.appearance.quantity_input_box_enabled}
            <label class="qty-label" for="product_avail_input">
              {$smarty.capture.qty_title}<br />
              {if $product.appearance.quantity_input_box_enabled and $config.Appearance.show_in_stock eq "Y" and $config.General.unlimited_products ne 'Y'}
                <span id="product_avail_text" class="quantity-text">{$lng.lbl_product_quantity_from_to|substitute:"min":$product.appearance.min_quantity:"max":$product.avail}</span>
              {/if}
            </label>
          {/if}
*}
          <script type="text/javascript">
            //<![CDATA[
            var min_avail = {$product.appearance.min_quantity|default:1};
            var avail = {$product.appearance.max_quantity|default:1};
            var product_avail = {$product.avail|default:"0"};
            //]]>
          </script>
{*
          {if $product.appearance.quantity_input_box_enabled}
*}

<tr>
<TD nowrap="nowrap" style="font-size: 20px; font-weight: normal;">
{$lng.lbl_quantity}:
</TD>
<TD>
{if $config.General.unlimited_products eq "N" and ($product.avail le 0 or $product.avail lt $product.min_amount) and $variants eq ''}
<b>{$lng.txt_out_of_stock}</b>
{else}
            <input type="number" class="qty-input" id="product_avail" name="amount" maxlength="11" size="6"{* onchange="javascript:  return check_quantity_input_box(this); check_wholesale(this.value);  " *} onkeyup="check_wholesale(this.value);" value="{$smarty.get.quantity|default:$product.min_amount}"{* {if not $product.appearance.quantity_input_box_enabled} disabled="disabled" style="display: none;"{/if} *} style="width: 100px; height: 50px;" />

{if $product.min_amount gt 1}
<font style="font-size: 20px; color: #CC3333;">{if $product.mult_order_quantity eq "Y"}{$lng.txt_need_min_amount_mult|substitute:"items":$product.min_amount}{else}{$lng.txt_need_min_amount|substitute:"items":$product.min_amount}{/if}</font>
{/if}

{/if}
</TD>
</TR>

{if $product.min_amount gte 1 && $product.product_availability eq "in stock"}
<tr>
<td>
{if $product_subtotal_value eq ""}
{math equation="price*quantity" price=$current_price quantity=$product.min_amount format="%3.5f" assign=product_subtotal_value}
{/if}
<font style="font-size: 20px; color: #000000; font-weight: bold;">Subtotal:</font>
</td>
<td>
<div style="font-size: 20px; color: #000000; font-weight: bold;" id="product_subtotal_value">{include file="currency.tpl" value=$product_subtotal_value plain_text_message=true price_type="product_subtotal_value"}</div>
</td>
</tr>
{/if}


</TABLE>
            <div class="clearing"></div>
{*
          {/if}
*}
          <div class="ui-select"{* {if $product.appearance.quantity_input_box_enabled} *} style="display: none;"{* {/if} *}>
            <div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="arrow-d" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-corner-bottom ui-controlgroup-last ui-btn-up-c">
              <span class="ui-btn-inner ui-corner-bottom ui-controlgroup-last">
              {strip}<span class="ui-btn-text">{$smarty.capture.qty_title|strip}:&nbsp;<span id="qty_select">1</span></span>{/strip}
              <span class="ui-icon ui-icon-arrow-d ui-icon-shadow">&nbsp;</span>
            </span>
            <select data-role="none" id="product_avail" name="amount"{if $active_modules.Product_Options ne '' and ($product_options ne '' or $product_wholesale ne '')} onchange="javascript: check_wholesale(this.value);"{/if}{* {if $product.appearance.quantity_input_box_enabled} *} disabled="disabled" style="display: none;"{* {/if} *}>
              <option value="{$product.appearance.min_quantity}"{if $smarty.get.quantity eq $product.appearance.min_quantity} selected="selected"{/if}>{$product.appearance.min_quantity}</option>
{*
              {if not $product.appearance.quantity_input_box_enabled}
                {section name=quantity loop=$product.appearance.loop_quantity start=$product.appearance.min_quantity}
                  {if %quantity.index% ne $product.appearance.min_quantity}
                    <option value="{%quantity.index%}"{if $smarty.get.quantity eq %quantity.index%} selected="selected"{/if}>{%quantity.index%}</option>
                  {/if}
                {/section}
              {/if}
*}
            </select>
          </div>
        </div>
{*
        {if not $product.appearance.quantity_input_box_enabled}
        </fieldset>
      {/if}
*}
    </div>
  {else}
    <label class="property-name product-input">{$lng.lbl_quantity}</label>
    <span class="property-value">
      <script type="text/javascript">
        //<![CDATA[
        var min_avail = 1;
        var avail = 1;
        var product_avail = 1;
        //]]>
      </script>
      <span class="product-one-quantity">1</span>
      <input type="hidden" name="amount" value="1" />
      {if $product.distribution ne ""}
        {$lng.txt_product_downloadable}
      {/if}
    </span>
  {/if}


<table style="font-size: 20px; font-weight: normal;">
{if $product.eta_date_in_future eq "Y"}
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td>Expected availability:</td>
<td>{$product.eta_date_dd_month_yyyy}</td>
</tr>
{if $product.allow_pre_orders ne "Y"}
<tr><td colspan="2">Sorry we don't take pre-orders.</td></tr>
{/if}
{/if}

{if $config.General.unlimited_products eq "N" and ($product.avail le 0 or $product.avail lt $product.min_amount) and $variants eq '' && $product_feed_enabled eq "Y" && $notify_when_in_stock[$product.productid] ne "Y"}

        {if $product.eta_date_in_future ne "Y"}
        <tr><td colspan="2">&nbsp;</td></tr>
        {/if}

<tr id="notify_tr1">
<td colspan="2">
{* <I><a href="javascript: void(0);" onclick="javascript: $('#notify_tr').toggle();" >Notify me when it's in stock</a></I> *}
<I><a href="javascript: void(0);" onclick="javascript: $('#notify_tr1').hide(); $('#notify_tr2').show();" >Notify me when it's in stock</a></I>
</td>
</tr>

<tr id="notify_tr2" style="display: none;">
<td>Your email address:</td>
<td>
<input type="text" name="notify_email" value="" />
{include file="customer/buttons/button.tpl" button_title="Notify me" style="button" href="javascript:if (checkEmailAddress(document.orderform.notify_email, 'Y')) `$ldelim`document.notifyform.mode.value='notify';document.notifyform.notify_email.value=document.orderform.notify_email.value;document.notifyform.submit()`$rdelim`"}
<tr>
<tr><td colspan="2">&nbsp;</td></tr>
{/if}
</table>


  {if $product.forsale ne "B" and ($product_wholesale or $variants|@func_mobile_variants_has_wl)}
    <div id="wl-prices-wrapper"{if not $product_wholesale} style="display: none;"{/if}>
      {include file="customer/main/product_prices.tpl" mobile_skin="Y"}
    </div>
  {/if}
{*
  <div class="price-row">
    {if $product.appearance.has_market_price and $product.appearance.market_price_discount gt 0}
      <span class="product-market-price">{currency value=$product.list_price}</span>
    {/if}
    {if $product.taxed_price ne 0 or $variant_price_no_empty}
      <span class="product-price-value">{currency value=$product.taxed_price tag_id="product_price"}</span>
      <span class="product-alt-price">{alter_currency value=$product.taxed_price tag_id="product_alt_price"}</span>
      {if $product.taxes}
        <div class="taxes">
          {include file="customer/main/taxed_price.tpl" taxes=$product.taxes}
        </div>
      {/if}
    {else}
      <input type="text" size="7" name="price" />
    {/if}
  </div>
*}

{*
  {$product.fulldescr|default:$product.descr}
*}

  <ul class="properties-list">
{*
    {if $product.min_amount gt 1}
      <li>
        <span class="property-value product-min-amount">{$lng.txt_need_min_amount|substitute:"items":$product.min_amount}</span>
      </li>
    {/if}
*}
  {/if}
{*
  {if $config.Appearance.show_in_stock eq "Y" and $config.General.unlimited_products ne "Y" and $product.distribution eq ""}
    <li>
      <label>{$lng.lbl_in_stock}</label>
      <span class="property-value product-quantity-text">
        {if $product.avail gt 0}
          {$lng.txt_items_available|substitute:"items":$product.avail}
        {else}
          {$lng.lbl_no_items_available}
        {/if}
      </span>
    </li>
  {/if}
*}
  {if $product.weight ne "0.00" or $variants ne ''}
    <li id="product_weight_box"{if $product.weight eq '0.00'} style="display: none;"{/if}>
      <label class="property-name">{$lng.lbl_weight}</label>
      <span class="property-value">
        <span id="product_weight">{$product.weight|formatprice}</span> {$config.General.weight_symbol}
      </span>
    </li>
  {/if}
  {if $active_modules.Extra_Fields && $extra_fields}
    {foreach from=$extra_fields item=v}
      {if $v.active eq "Y" and $v.field_value}
        <li>
          <label class="property-name">{$v.field}:</label>
          <span class="property-value">{$v.field_value}</span>
        </li>
      {/if}
    {/foreach}
  {/if}
  {if $active_modules.Feature_Comparison && $product.features.options}
    {foreach from=$product.features.options item=v}
      <li>
        <label class="property-name">
          {include file="modules/Feature_Comparison/option_hint.tpl" opt=$v}
        </label>
        <span class="property-value">
          {if $v.option_type eq 'S'}
            {$v.variants[$v.value].variant_name}
          {elseif $v.option_type eq 'M'}
            {foreach from=$v.variants item=o}
              {if $o.selected ne ''}
                {$o.variant_name}<br />
              {/if}
            {/foreach}
          {elseif $v.option_type eq 'B'}
            {if $v.value eq 'Y'}
              {$lng.lbl_yes}
            {else}
              {$lng.lbl_no}
            {/if}
          {elseif ($v.option_type eq 'N' or $v.option_type eq 'D') and $v.value ne ''}
            {$v.formated_value}
          {else}
            {$v.value|replace:"\n":"<br />"}
          {/if}
        </span>
      </li>
    {/foreach}
  {/if}
</ul>
{if $product.appearance.buy_now_buttons_enabled}
  {if $product.forsale ne "B"}
    <div class="ui-grid-solo">
      <div class="ui-block-a add-to-cart-button">
        {capture name="add_to_cart_button"}
          {currency value=$product.taxed_price tag_id="" assign="top_price"}
          {include file="customer/buttons/add_to_cart.tpl" style="link" type="input" title_price=$top_price additional_button_class="main-button" data_inline="false" button_id="bottom-cart-button"}
        {/capture}
        {$smarty.capture.add_to_cart_button}
      </div>
    </div>
    <div class="ui-grid-{if $config.Company.support_department neq ""}a{else}solo{/if}">
      <div class="ui-block-a">
        {if $product.appearance.dropout_actions}
          {include file="customer/buttons/add_to_list.tpl" id=$product.productid js_if_condition="FormValidation()" data_inline="false"}
        {elseif $product.appearance.buy_now_add2wl_enabled}
          {include file="customer/buttons/add_to_wishlist.tpl" href="javascript: if (FormValidation()) submitForm(document.orderform, 'add2wl', arguments[0]);" data_inline="false"}
        {/if}
      </div>
      {if $config.Company.support_department neq ""}
        <div class="ui-block-b ask-question">
          {include file="customer/buttons/button.tpl" button_title=$lng.lbl_ask_question_about_product style="link" href="javascript: return !popupOpen(xcart_web_dir + '/popup_ask.php?productid=`$product.productid`')" data_inline="false"}
        </div>
      {/if}
    </div>
  {else}
    {$lng.txt_pconf_product_is_bundled}
  {/if}
  {if $smarty.get.pconf ne "" and $active_modules.Product_Configurator}
    <input type="hidden" name="slot" value="{$smarty.get.slot}" />
    <input type="hidden" name="addproductid" value="{$product.productid}" />
    <div class="button-row">
      {include file="customer/buttons/button.tpl" button_title=$lng.lbl_pconf_add_to_configuration href="javascript: if (FormValidation()) `$ldelim`document.orderform.productid.value='`$smarty.get.pconf`'; document.orderform.action='pconf.php'; document.orderform.submit();`$rdelim`" additional_button_class="light-button"}
    </div>
    {if $product.appearance.empty_stock}
      <p class="message">
        <strong>{$lng.lbl_note}:</strong> {$lng.lbl_pconf_slot_out_of_stock_note}
      </p>
    {/if}
    {if $product.appearance.min_quantity eq $product.appearance.max_quantity}
      <p>{$lng.txt_add_to_configuration_note|substitute:"items":$product.appearance.min_quantity}</p>
    {/if}
  {/if}
{/if}
</form>
{*
{if $active_modules.Product_Options and ($product_options ne '' or $product_wholesale ne '') and ($product.product_type ne "C" or not $active_modules.Product_Configurator)}
*}
  <script type="text/javascript">
    //<![CDATA[
//    setTimeout(check_options, 200);
//    has_options = true;
check_options();
    //]]>
  </script>
{*
{/if}
*}

<script type="text/javascript">
//<![CDATA[
{literal}
$(document).ready(function() {
        window.onload = check_wholesale($('#product_avail').val());
});
{/literal}
//]]>
</script>


{if $product_details_standalone}
  {load_defer_code type="css"}
  {load_defer_code type="js"}
{/if}
