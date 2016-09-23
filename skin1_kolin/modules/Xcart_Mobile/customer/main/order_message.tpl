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
    $("body").append(gts_code);
  </script>
{/if}
{/if}

