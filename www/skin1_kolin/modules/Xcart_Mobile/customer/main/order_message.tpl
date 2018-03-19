{*
$Id: order_message.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<h1>{$lng.lbl_invoice}</h1>
<div class="order-placed">
  <p class="text-block">{$lng.txt_order_placed}</p>
  {$lng.txt_order_placed_msg}
</div> 
  <br />
  
  
  
<!-- Google Code for Conversion Tracking: Order Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
{literal}
var google_conversion_id = 1072406910;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "9T_YCJXjmXMQ_sKu_wM";
var google_conversion_value = {/literal}{$orders[0].order.total}{literal};
var google_conversion_order_id = {/literal}{$orders[0].order.orderid}{literal};
var google_conversion_currency = "USD";
var google_remarketing_only = false;
{/literal}
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1072406910/?value={$orders[0].order.total}&amp;currency_code=USD&amp;label=9T_YCJXjmXMQ_sKu_wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Code for Conversion Tracking: Order Conversion Page -->


  
{include file="customer/buttons/button.tpl" button_title=$lng.lbl_continue_shopping href="home.php" additional_button_class="main-button" data_inline="false"}
{if $active_modules.XAuth}
  {include file="modules/XAuth/rpx_ss_invoice.tpl"}
{/if}

{if $GTS_order_confirmation_module_code ne ""}
  <script>
    gts_code=$('{$GTS_order_confirmation_module_code|escape:javascript}');
  </script>
{/if}

{assign var=aRetailTrustProductDetails value=$oOrder->getOrderDetailsWithProductsRetailTrust()}
{assign var=aRetailTrustOrderDetails value=$oOrder->getOrderDetailsWithRetailTrust()}
{if !empty($aRetailTrustProductDetails) && empty($aRetailTrustOrderDetails)}
  {assign var=oOrder value=$oOrder}
  {include file="customer/main/retail_trust.tpl"}
{else}
{if $GTS_order_confirmation_module_code ne ""}
  <script>
      $(document).ready(function() {
          $("body").append(gts_code);
      });
  </script>
{/if}
{/if}

