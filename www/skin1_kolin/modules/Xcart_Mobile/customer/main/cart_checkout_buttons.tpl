{*
$Id$ 
vim: set ts=2 sw=2 sts=2 et:
*}
<div>
  {include file="customer/buttons/button.tpl" button_title=$lng.lbl_checkout style="div_button" href="cart.php?mode=checkout" data_theme="b" data_icon="arrow-r" data_iconpos="right" data_inline="false"}
</div>
{if $paypal_express_active}
  <div>
    {include file="payments/ps_paypal_pro_express_checkout.tpl" paypal_express_link="button"}
  </div>
{/if}
{if $gcheckout_enabled}
  <div>
    {include file="modules/Google_Checkout/gcheckout_button.tpl"}
  </div>
{/if}
{if $amazon_enabled}
  <div>
    {include file="modules/Amazon_Checkout/checkout_btn.tpl"}
  </div>
{/if}