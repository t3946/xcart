{*
$Id: checkout_3_place.tpl 78 2012-12-28 13:59:37Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<h1>{$lng.lbl_place_order}</h1>
{include file="customer/main/cart_contents.tpl" link_qty="Y"}
{* TODO: think over this section *}
{*if $config.Appearance.show_cart_details eq "Y"} 
{include file="customer/main/cart_details.tpl" link_qty="Y"}
{else}
{include file="customer/main/cart_contents.tpl" link_qty="Y"}
{*/if*}
{include file="customer/main/cart_totals.tpl" link_shipping="Y" no_form_fields=true show_shipping_section="N"}
{if $cart.coupon_discount eq 0 and $products and $active_modules.Discount_Coupons}
  {include file="modules/Discount_Coupons/add_coupon.tpl" page='place_order'}
{/if}
{include file="customer/main/cart_totals.tpl" link_shipping="Y" no_form_fields=true show_shipping_section="Y"}
{* TODO: find out why it's not working here
{if $active_modules.TaxCloud}
{include file="modules/TaxCloud/cart_totals.tpl"}
{/if}
*}
<script type="text/javascript">
  //<![CDATA[
  var xpc_iframe_method = false;
  {literal}
    (function($) {
      $.fn.block = function(opts) {
      };
      // plugin method for unblocking element content
      $.fn.unblock = function(opts) {
      };
    })(jQuery);
  {/literal}
    //]]>
</script>
<form action="{$payment_data.payment_script_url}" method="post" name="checkout_form" onsubmit="return (window.xpc_iframe_method) ? checkCheckoutFormXP() : true;" data-ajax="false">
  <input type="hidden" name="paymentid" value="{$payment_data.paymentid}" />
  <input type="hidden" name="action" value="place_order" />
  <input type="hidden" name="{$XCARTSESSNAME}" value="{$XCARTSESSID}" />
  <input type="hidden" name="payment_method" value="{$payment_data.payment_method_orig}" />
  <div data-role="collapsible" data-theme="d" data-content-theme="b">
    <h3>{$lng.lbl_personal_information}</h3>
    <div>
      {include file="modules/Fast_Lane_Checkout/customer_details_html.tpl" paymentid=$payment_data.paymentid}
    </div>
  </div>
  <div data-role="collapsible" data-theme="d" data-collapsed="false">
    <h3>{$lng.lbl_payment_details}</h3>
    <div>
      <div class="ui-grid-{if $ignore_payment_method_selection eq ""}a{else}solo{/if}">
        <div class="ui-block-a">
          <h3>{$lng.lbl_payment_method}:<br /> {$payment_data.payment_method}</h3>
        </div>
        {if $ignore_payment_method_selection eq ""}
          <div class="ui-block-b">
            {include file="customer/buttons/button.tpl" button_title=$lng.lbl_change href="cart.php?mode=checkout&section=payment_method" style="link"}
          </div>
        {/if}
      </div>
      <script type="text/javascript">
    //<![CDATA[
    requiredFields = [];
    //]]>
      </script>
      {include file="check_required_fields_js.tpl" use_email_validation="N"}
      <div class="flc-checkout-box-info">
        {if $payment_cc_data.background eq "I"}
          <noscript>
          <font class="error-message">{$lng.txt_payment_js_required_warn}</font>
          <br /><br />
          </noscript>
        {elseif $payment_data.payment_template ne ""}
          {capture name="payment_section"}

            {capture name=payment_template_output}
              {include file=$payment_data.payment_template hide_header="Y" payment=$payment_data}
            {/capture}
            {if $smarty.capture.payment_template_output ne ""}
              <div class="flc-payment-options">
                {$smarty.capture.payment_template_output}
              </div>
            {/if}
          {/capture}
          {if !($active_modules.XPayments_Connector && $config.XPayments_Connector.xpc_use_iframe eq 'Y' && $payment_data.processor_file eq 'cc_xpc.php')}
            {$smarty.capture.payment_section}
          {/if}
        {/if}
        {if $payment_cc_data.cmpi eq 'Y' and $config.CMPI.cmpi_enabled eq 'Y'}
          {include file="main/cmpi.tpl"}
        {/if}
        <div class="text-block">
          {include file="customer/main/checkout_notes.tpl"}
        </div>
      </div>
    </div>
  </div>
  {if $active_modules.XPayments_Connector && $config.XPayments_Connector.xpc_use_iframe eq 'Y' && $payment_data.processor_file eq 'cc_xpc.php'}
    <script type="text/javascript">
      //<![CDATA[
      {include file="modules/Fast_Lane_Checkout/xpc_func_tpl.js"}
      //]]>
    </script>
    <div id="xpc_iframe_wrapper" style="display: none;">
      {assign var="xpc_is_set" value=" xpc-is-set"}
      {$smarty.capture.payment_section}
    </div>
  {/if}
  <div class="buttons-wrapper">
    <div class="ui-body ui-body-e{$xpc_is_set}{if $config.XPayments_Connector.xpc_api_version gte '1.2'} xpc-16{/if}">
      <div class="buttons-itself">
        <div class="ui-grid-a">
          <div class="ui-block-a">
            {if $xcart_mobile_config.submit_order_dlg_disabled ne 'Y'}
              {assign var="terms_button_theme" value="b"}
            {/if}
            {include file="customer/buttons/button.tpl" button_title=$lng.lbl_terms_n_conditions href="`$current_location`/pages.php?alias=conditions" style="link" data_inline="false" data_mini="true" data_theme=$terms_button_theme|default:"c"}
          </div>
          <div class="ui-block-b">
            {include file="customer/buttons/button.tpl" button_title=$lng.lbl_privacy_statement href="`$current_location`/pages.php?alias=business" style="link" data_inline="false" data_mini="true"}
          </div>
        </div>
      </div>
      {if $xcart_mobile_config.submit_order_dlg_disabled ne 'Y'}
        <label for="accept_terms">
          <input type="checkbox" name="accept_terms" id="accept_terms" value="Y" />
          {$lng.txt_mobile_terms_and_conditions_note}
        </label>
      {else}
        <input type="hidden" name="accept_terms" value="Y" />
      {/if}
      {if $payment_data.processor_file eq 'ps_gcheckout.php'}
        {include file="buttons/gcheckout.tpl" onclick=$button_href}
      {elseif !$xpc_is_set or $config.XPayments_Connector.xpc_api_version gte '1.2'}
        {include file="modules/Fast_Lane_Checkout/checkout_js.tpl"}
        <div class="button-row center{if $xcart_mobile_config.submit_order_dlg_disabled ne 'Y'} ui-disabled{/if}" id="btn_box">
          {include file="customer/buttons/button.tpl" button_title=$lng.lbl_submit_order href=$button_href type="input" additional_button_class="main-button" data_inline="false"}
        </div>
        {if $xcart_mobile_config.submit_order_dlg_disabled ne 'Y'}
          {literal}
            <script type="text/javascript">
              //<![CDATA[
              $(function() {
                $('#accept_terms').live('change', function() {
                  $('#btn_box').toggleClass('ui-disabled', !$(this).is(':checked'));
                });
              });
              //]]>
            </script>
          {/literal}
        {/if}
        <div id='msg' style="display: none;" class="order-placed-msg"><h1>{$lng.msg_order_is_being_placed}</h1></div>
      {/if}
    </div>
  </div>
</form>