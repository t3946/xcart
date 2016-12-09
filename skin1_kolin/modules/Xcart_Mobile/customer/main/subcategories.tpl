{*
$Id: subcategories.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $active_modules.Special_Offers}
  {include file="modules/Special_Offers/customer/category_offers_short_list.tpl"}
{/if}
{* {if $list_categories || !$show_subnav} *}
{if $categories || !$show_subnav}
{*
  {if $current_category.is_icon}
    <div class="subcategory-image">
      <img src="{get_category_image_url category=$current_category}" alt="{$current_category.category|escape}"{if $current_category.image_x} width="{$current_category.image_x}"{/if}{if $current_category.image_y} height="{$current_category.image_y}"{/if} />
    </div>
  {/if}
*}
  {if !$cat}
{*    {assign var="mob_categories" value=$categories_menu_list} *}
    {assign var="mob_categories" value=$categories}
  {elseif $cat && $categories|@is_array}
{*    {assign var="mob_categories" value=$categories} *}
    {assign var="mob_categories" value=$subcategories}
  {/if}
  {if $mob_categories}
    {include file="customer/main/subcategories_list.tpl" categories=$mob_categories}
  {/if}
{/if}
{if !$list_categories}
  {if !$cat and $manufacturers_menu}
    {include file="modules/Manufacturers/menu_manufacturers.tpl"}
  {elseif $cat ne 0}
    {if $active_modules.Bestsellers and $xcart_mobile_config.cat_bestsellers eq 'Y' and $bestsellers}
      {include file="customer/main/bestsellers.tpl"}
    {/if}
    {if $active_modules.New_Arrivals}
      {include file="modules/New_Arrivals/new_arrivals.tpl" new_arrivals_main="Y"}
      {if $new_arrivals}
        {assign var="title" value=$lng.lbl_products}
      {/if}
    {/if}
    {if $f_products and $xcart_mobile_config.cat_featured eq 'Y'}
      {include file="customer/main/products.tpl" products=$f_products title=$lng.lbl_featured_products featured="Y"}
      {if $f_products}
        {assign var="title" value=$lng.lbl_products}
      {/if}
    {/if}
{*    {if $cat_products} *}
    {if $products}
      
      {if $total_pages gt 2 and $title}
        <div class="inner-gap"></div>
      {/if}
      
      {include file="customer/main/navigation.tpl"}
{*      {include file="customer/main/products.tpl" title=$title products=$cat_products} *}
      {include file="customer/main/products.tpl" title=$title products=$products}
      {include file="customer/main/navigation.tpl"}
    {/if}
  {/if}
{/if}

{if $categories || !$show_subnav}
  {if $current_category.description ne ""}
<br />
    {include file="sliders/slider.tpl" mode='recently_viewed_products' title="Your Recently Viewed Items"}
    <div class="subcategory-descr">{$current_category.description}</div>
  {/if}
{/if}

