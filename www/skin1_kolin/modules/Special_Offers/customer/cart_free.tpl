{* $Id: cart_free.tpl,v 1.5 2004/12/03 15:39:01 mclap Exp $ *}
{if $products[product].free_amount gt 0 and $products[product].subtotal eq 0}
{$lng.lbl_sp_cart_free_item}
{/if}
{if $config.Shipping.disable_shipping ne "Y" and $products[product].free_shipping_used ne ""}
{$lng.lbl_sp_cart_free_shipping_item}
{/if}
