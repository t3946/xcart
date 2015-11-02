{*
2aca87f302048436ed08b4e6738089849840409f, v1 (xcart_4_5_3), 2012-08-07 09:50:06, on_sale.tpl, tito
vim: set ts=2 sw=2 sts=2 et:
*}
{if $on_sale_products ne "" && $usertype eq "C" && ($is_home_page neq "Y" || ($is_home_page eq "Y" && $config.On_Sale.on_sale_home eq "Y"))}
  {if $navigation eq "Y"}
    {include file="customer/main/navigation.tpl"}
  {/if}
  {include file="customer/main/products.tpl" title=$lng.lbl_on_sale products=$on_sale_products is_on_sale_products="Y"}
  {if $navigation eq "Y"}
    {include file="customer/main/navigation.tpl"}
  {/if}
{/if}
