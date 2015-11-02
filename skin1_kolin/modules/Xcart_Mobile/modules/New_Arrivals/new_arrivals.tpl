{*
2178a473e6db2465d21fe538044f4d24f3a45dca, v2 (xcart_4_5_3), 2012-09-24 07:58:13, new_arrivals.tpl, aim
vim: set ts=2 sw=2 sts=2 et:
*}
{if $new_arrivals && (($is_home_page eq "Y" && $config.New_Arrivals.new_arrivals_home eq "Y") || ($new_arrivals_main eq "Y" && $config.New_Arrivals.new_arrivals_main eq "Y") || $is_new_arrivals_page)}
  {if $is_new_arrivals_page}
    {include file="customer/main/navigation.tpl"}
  {/if}
  {include file="customer/main/products.tpl" products=$new_arrivals title=$lng.lbl_new_arrivals new_arrivals_show_date="Y" is_new_arrivals_products="Y"}

  {if $is_new_arrivals_page}
    {include file="customer/main/navigation.tpl"}
  {/if}
{/if}
