{*
$Id: add_to_cart.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{* Uncomment this line if you don't want buy more button behavior: 
{include file="customer/buttons/button.tpl" button_title=$lng.lbl_add_to_cart additional_button_class=$additional_button_class|cat:' add-to-cart-button'}
*}
{* Comment the following 5 lines if you don't want buy more button behavior: *} 
{if $product.appearance.added_to_cart} 
  {include file="customer/buttons/button.tpl" button_title="`$title_price``$lng.lbl_add_more`" additional_button_class=$additional_button_class|cat:' add-to-cart-button added-to-cart-button'}
{else} 
  {include file="customer/buttons/button.tpl" button_title="`$title_price``$lng.lbl_add_to_cart`" additional_button_class=$additional_button_class|cat:' add-to-cart-button'}
{/if} 
