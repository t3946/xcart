{*
$Id: products.tpl 78 2012-12-28 13:59:37Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $products}
  {if $active_modules.Feature_Comparison and $products_has_fclasses and not $featured}
    {include file="modules/Feature_Comparison/compare_selected_button.tpl"}
  {/if}
  <div class="content-secondary">
    <ul data-role="listview" data-type="products-list" data-divider-theme="c">
      {if $title}
        <li data-role="list-divider" role="heading">
          <h2>{$title}</h2>
        </li>
      {/if}
      {foreach from=$products item=product}

{if $product.new_notify_in_stock_price ne ""}
        {assign var="current_price" value=$product.new_notify_in_stock_price}
{else}
        {if $product.map_price gt $product.taxed_price}
                {assign var="current_price" value=$product.map_price}
        {else}
                {assign var="current_price" value=$product.taxed_price}
        {/if}
{/if}

        <li>
          <a href="{$current_location}/product.php?productid={$product.productid}">
            <span class="product-thumbnail">
              {include file="product_thumbnail.tpl" productid=$product.productid product=$product.product tmbn_url=$product.tmbn_url}
              <img src="{$ImagesDir}/spacer.gif" class="leveler" alt="" />
              <span class="labels">
                {if $active_modules.On_Sale}
                  {include file="modules/On_Sale/on_sale_icon_products_list.tpl" product=$product}
                {/if}
                {if $active_modules.Special_Offers and $product.have_offers}
                  <span class="so-thumb" onclick="javascript: $.mobile.changePage('offers.php?mode=product&amp;productid={$product.productid}');"></span>
                {/if}
              </span>
            </span>
            <span class="product-details">
              <span class="ui-li-heading">
                <span class="list">{$product.product|amp}</span>
                <span class="grid">{$product.product|amp|truncate:79:'...'}</span>
                {if $active_modules.New_Arrivals}
                  {include file="modules/New_Arrivals/new_arrivals_show_date.tpl" product=$product}
                {/if}
              </span>
              <span class="ui-li-desc">
                {if $config.Appearance.display_productcode_in_list eq "Y" && $product.productcode}
                  <span class="sku">{$lng.lbl_sku}: {$product.productcode|escape}</span>
                {/if}
                {if $product.product_type ne "C"}
                  {if $product.appearance.is_auction}
                    <span class="price">{$lng.lbl_enter_your_price}</span><br />
                    {$lng.lbl_enter_your_price_note}
                  {else}

{*
                    {if $product.appearance.has_price || !$product.appearance}
                      {if $product.appearance.has_market_price and $product.appearance.market_price_discount gt 0}
                        <span class="market-price">
                          {strip}
                            <span class="market-price-value">{currency value=$product.list_price}</span>
                            {if $product.appearance.market_price_discount gt 0}
                              {if $config.General.alter_currency_symbol ne ""}
                                ,
                              {/if}
                              <span class="price-save">&nbsp;{$lng.lbl_save_price} {$product.appearance.market_price_discount}%</span>
                            {/if}
                          {/strip}
                        </span>
                      {/if}
*}
                      <span class="price">
{*                        <span class="price-value">{currency value=$product.taxed_price}</span> *}
                        Price: <span class="price-value">{include file="currency.tpl" value=$current_price}</span>
{*                        <span class="market-price">{alter_currency value=$product.taxed_price}</span> *}
                      </span>

<span class="sku">
            {if $product.avail gt 0 or $config.General.unlimited_products eq "Y"}
              {$lng.lbl_in_stock_top}
            {else}
              {$lng.lbl_out_stock}
            {/if}
</span>

                      {if $product.taxes}
                        <span class="taxes">{include file="customer/main/taxed_price.tpl" taxes=$product.taxes is_subtax=true}</span>
                      {/if}
{*
                    {/if}
*}
                    {if $active_modules.Special_Offers and $product.use_special_price}
                      {include file="modules/Special_Offers/customer/product_special_price.tpl"}
                    {/if}
                  {/if}
                {/if}
              </span>
            </span>
          </a>
          {if $active_modules.Feature_Comparison && $product.fclassid gt 0 and not $featured}
            {include file="modules/Feature_Comparison/compare_checkbox.tpl" id=$product.productid assign="fcomp_checkbox"}
            {$fcomp_checkbox|replace:"fcomp-checkbox-box":"fcomp-checkbox-box left-block"}
            <div class="clearing"></div>
          {/if}
        </li>
      {/foreach}
    </ul>
    <div class="clearing"></div>
  </div>

  {if $active_modules.Feature_Comparison and $products_has_fclasses and not $featured}
    {include file="modules/Feature_Comparison/compare_selected_button.tpl"}
  {/if}
{/if}
