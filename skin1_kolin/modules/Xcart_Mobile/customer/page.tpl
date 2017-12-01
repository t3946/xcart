{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}

{* ------------------- *}
{include file="cidev_tracking_code.tpl" }
{* ------------------- *}

{$xcartApp->template->render('inSmarty/raw_flash.tpl')}

{if $mobile_mode eq 'get_detailed_images'}
  {if $product and $images ne ''}
    <!-- dialog #detailed-images-{$product.productid} -->

    <div data-role="page" data-add-back-btn="true" data-dom-cache="true" id="detailed-images-{$product.productid}" class="gallery-page">
      <div data-role="header" data-inline="true">
        <a href="#" class="custom-arrow" data-role="button" data-rel="back" data-icon="custom-arrow-l" data-iconpos="notext">{$lng.lbl_back}</a>
        <h1>{$product.product}</h1>
      </div>
      <div data-role="content">
        <ul class="gallery">
          {foreach from=$images item=i}
            <li><a href="{$i.tmbn_url|amp}" rel="external"><img src="{$i.tmbn_url|amp}" alt="{$i.alt|default:$product.product}" /></a></li>
              {/foreach}
        </ul>
      </div>
    </div>
    <!-- dialog #detailed-images-{$product.productid} -->
  {/if}
{else}
  <!-- page {$main} -->
  <div data-role="{$data_role|default:'page'}" {*data-url="{$php_url.url}{if $php_url.query_string}?{$php_url.query_string}{/if}"*} class="page-holder{if $page_container_class} {$page_container_class}{/if}{if $main eq 'product'} product-details-page{/if}{if $main eq 'cart' and not $cart_empty} cart-page{/if}{if $container_classes} {foreach from=$container_classes item=c}{$c} {/foreach}{/if}" data-dom-cache="false" data-add-back-btn="true" data-url="{$data_url}">
    {if !$no_nav}
      {include file="customer/top_navigation.tpl"}
    {/if}
    {if !$no_header}
      <div data-role="header" data-inline="true">
        {if !$no_nav}
          <a href="#" class="custom-arrow back-title-button" data-role="button" data-rel="back" data-icon="custom-arrow-l" data-iconpos="notext" data-theme="b" data-shadow="false" data-corners="false">{$lng.lbl_back}</a>
        {/if}
        <h1>{func_mobile_get_page_title}</h1>
        {if $main eq 'catalog' && ((($cat_products or ($f_products and $xcart_mobile_config.cat_featured eq 'Y')) && $current_category.subcategory_count gt 0) || (!$cat && $manufacturers_menu && $mobile_mode eq 'subcategories'))}
          {assign var="show_subnav" value=true}
          {if $cat}
            {assign var="products_count" value=$current_category.top_product_count|default:$f_products|@count}
          {/if}
        {/if}
        {if !$show_subnav}
          {include file="customer/search_sort_by.tpl"}
        {/if}
      </div>
    {/if}
    <div data-role="content">

      {if $active_modules.EU_Cookie_Law && $is_ajax_request ne 'Y'}
        {include file="modules/EU_Cookie_Law/info_panel.tpl"}
      {/if}
      {if ($main eq 'cart' and not $cart_empty) or $main eq 'checkout' or $main eq "fast_lane_checkout"}
        {include file="modules/`$checkout_module`/content.tpl"}
      {else}
        {if $show_subnav}
          <div class="tabs-menu">
            <div data-role="navbar">
              <ul>
                {capture name="prods_mans_point"}
                  <li><a data-theme="a" data-mini="false" href="{$current_location}/home.php?{if $cat}cat={$cat}{else}mobile_mode=subcategories{/if}"{if !$list_categories} class="ui-btn-active ui-state-persist"{/if} data-prefetch>{if !$cat}{$lng.lbl_manufacturers}{else}{$lng.lbl_products} <span>({$products_count})</span>{/if}</a></li>
                {/capture}
                {capture name="cats_list_point"}
                  <li><a data-theme="a" data-mini="false" href="{$current_location}/home.php?{if $cat}cat={$cat}{else}mobile_mode=subcategories{/if}&list_categories=1"{if $list_categories} class="ui-btn-active ui-state-persist"{/if} data-prefetch>{$lng.lbl_categories}{if $cat} <span>({$current_category.subcategory_count})</span>{/if}</a></li>
                {/capture}
                {if !$cat && $manufacturers_menu}
                  {$smarty.capture.cats_list_point}
                  {$smarty.capture.prods_mans_point}
                {else}
                  {$smarty.capture.prods_mans_point}
                  {$smarty.capture.cats_list_point}
                {/if}
              </ul>
            </div>
          </div>
        {/if}
        {if $page_tabs ne ''}
          {include file="customer/main/top_links.tpl" tabs=$page_tabs}
        {/if}
        {include file="customer/bread_crumbs.tpl"}
        {include file="customer/dialog_message.tpl"}
        {if $active_modules.Special_Offers && ($main eq 'catalog' || $main eq 'product' || $main eq 'manufacturer_products' || $main eq 'search' || $main eq 'advanced_search') && $mobile_mode ne 'more' && $mobile_mode ne 'search'}
          {include file="modules/Special_Offers/customer/new_offers_message.tpl"}
        {/if}
        {if $mobile_mode}
          {include file="customer/main/`$mobile_mode`.tpl"}
        {else}
          {include file="customer/home_main.tpl"}
        {/if}
      {/if}
    </div>
      {if $main eq 'product'}
          {include file="sliders/slider.tpl" productid=$product.productid mode='related_products' title="Related products"}
          {include file="sliders/slider.tpl" productid=$product.productid mode='products_also_bought_with_this_product'  title="Customers Who Bought This Item Also Bought"}
          {if $product.product_availability eq "in stock"}
            {include file="sliders/slider.tpl" productid=$product.productid mode='similar_products'  title="Similar products"}
          {/if}
          {include file="sliders/slider.tpl" mode='recently_viewed_products' title="Your Recently Viewed Items"}
      {/if}

      {if $main eq 'brand_products'}
        {include file="sliders/slider.tpl" mode='recently_viewed_products' title="Your Recently Viewed Items"}
      {/if}

    {if !$no_nav}
      {include file="customer/main/switch_view.tpl"}
      <div data-role="footer" data-inline="true">
        <h4 class="footer">
          {include file="main/prnotice.tpl"}<br />
          {include file="copyright.tpl"}
        </h4>
      </div>
    {/if}
  </div>
  <!-- /page {$main} -->
{/if}
