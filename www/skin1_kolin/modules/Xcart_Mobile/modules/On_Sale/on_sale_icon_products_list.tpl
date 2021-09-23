{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}

{if $usertype eq "C" && $product.on_sale eq "Y"
  && (
    ($main eq "catalog" && ($cat le "0" || $cat eq "") && $config.On_Sale.on_sale_on_home_page eq "Y")
    || ($main eq "catalog" && $cat gt "0" && $config.On_Sale.on_sale_on_product_list eq "Y")
    || ($main eq "on_sale" && $config.On_Sale.on_sale_on_sale_page eq "Y")
    || (($main eq "search" || $main eq "advanced_search") && $config.On_Sale.on_sale_on_search_page eq "Y")
    || ($main eq "pmap_customer" && $config.On_Sale.on_sale_on_pmap_page eq "Y")
  )}

  <span class="on-sale-icon"></span>

{/if}