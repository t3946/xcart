{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}
{if $active_modules.XMultiCurrency}
  {include file="modules/XMultiCurrency/customer/complex_selector.tpl"}
{else}
  {include file="customer/main/language_selector.tpl"}
{/if}
<div data-role="navbar" data-iconpos="top" class="top-nav">
  <ul>
    <li>
      <a data-ajax="false" class="link-home {func_mobile_set_active_tab mode='home'}" data-theme="a" data-icon="custom" href="/home.php?top_btn=Y" data-prefetch><h3>{$lng.lbl_title_home}</h3></a>
    </li>
    <li>
      <a {* data-ajax="false" *} class="link-catalog {func_mobile_set_active_tab mode='catalog'}" data-theme="a" data-icon="custom" href="/home.php?top_btn=Y&mobile_mode=subcategories{if $manufacturers_menu}&list_categories=1{/if}" data-prefetch><h3>{$lng.lbl_title_catalog}</h3></a>
    </li>
    <li>
      <a data-ajax="false" class="link-search {func_mobile_set_active_tab mode='search'}" data-theme="a" data-icon="custom" href="/home.php?mobile_mode=search" data-rel="dialog" data-transition="slidedown" data-prefetch><h3>{$lng.lbl_title_search}</h3></a>
    </li>
    <li class="link-cart-wrapper">
      <a data-ajax="false" class="link-cart {func_mobile_set_active_tab mode='cart'}" data-theme="a" data-icon="custom" href="/cart.php?top_btn=Y" data-prefetch><h3>{$lng.lbl_title_cart}</h3></a>
      <span class="minicart-total-items" style="display: inline;">{$minicart_total_items}</span>
    </li>
    <li>
      <a data-ajax="false" class="link-more {func_mobile_set_active_tab mode='more'}" data-theme="a" data-icon="custom" href="/home.php?top_btn=Y&mobile_mode=more" data-prefetch><h3>{$lng.lbl_title_more}</h3></a>
    </li>
  </ul>
</div>
{getBanners position='top_mobile'}
