{*
$Id: content.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{include file="modules/Fast_Lane_Checkout/tabs_menu.tpl"}
{if $top_message or $alt_content}
{*
  {include file="customer/dialog_message.tpl"}
*}
{/if}
{if $main eq 'cart' or $main eq "fast_lane_checkout"}
  {if !$std_checkout_disabled}
    {capture name="checkout_button"}
      
      {if $cart.display_discounted_subtotal ne $cart.display_subtotal}
        {currency value=$cart.display_discounted_subtotal assign="checkout_sum"}
      {else}
        {currency value=$cart.display_subtotal assign="checkout_sum"}
      {/if}
      {assign var="checkout_button_title" value=$checkout_sum|cat:" `$lng.lbl_checkout`"}
      
      {if $gcheckout_enabled or $amazon_enabled or $paypal_express_active}
        {include file="customer/buttons/button.tpl" button_title=$checkout_button_title style="dropout" data_theme="b" data_popup_theme="e" data_icon="arrow-r" data_iconpos="right" data_inline="false" prefix="top_checkout_button" dropout_tpl="customer/main/cart_checkout_buttons.tpl"}
      {else}
        {include file="customer/buttons/button.tpl" button_title=$checkout_button_title style="div_button" href="cart.php?mode=checkout" data_theme="b" data_icon="arrow-r" data_iconpos="right" data_inline="false"}
      {/if}
    {/capture}
  {/if}
  {capture name="continue_button"}
{*    {include file="customer/buttons/button.tpl" button_title=$lng.lbl_continue_shopping style="div_button" href=$stored_navigation_script data_inline="false"} *}
    {include file="customer/buttons/button.tpl" button_title=$lng.lbl_continue_shopping style="div_button" href="home.php?cat=`$last_categoryid`" data_inline="false"}
  {/capture}
{/if}
{include file="modules/Fast_Lane_Checkout/home_main.tpl"}
