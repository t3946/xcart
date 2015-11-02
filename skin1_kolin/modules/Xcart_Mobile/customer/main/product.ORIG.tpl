{*
$Id: product.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{include file="form_validation_js.tpl"}
<div class="product-details">
  {if $active_modules.Special_Offers || ($product.appearance.has_market_price and $product.appearance.market_price_discount gt 0)}
    {assign var="custom_top_info" value="true"}
  {/if}
  <div class="top-info ui-body ui-body-b ui-overlay-shadow">
    <div class="ui-grid-{if $active_modules.Special_Offers && $product.bonus_points gt 0}a{else}solo{/if}">
      <div class="ui-block-a">
        <h1>{$product.producttitle|amp}</h1>
      </div>
      {if $active_modules.Special_Offers && $product.bonus_points gt 0}
        <div class="ui-block-b">
          <div class="right-block bp-info">
            <ul data-role="listview" data-inset="true">
              <li data-theme="e" class="bp-info">
                +{$product.bonus_points}&nbsp;{$lng.lbl_sp_ttl_bonus_points}
              </li>
            </ul>
          </div>     
        </div>
      {/if}
    </div>
    <div class="ui-grid-a">
      <div class="ui-block-a">
        <div class="sku{if $product.appearance.has_market_price and $product.appearance.market_price_discount gt 0} save-mark-here{/if}" id="product_code">{$product.productcode|escape}</div>
        {if $product.distribution eq "" && !($product.product_type eq "C" and $active_modules.Product_Configurator)}
          <div class="product-quantity-text-top{if $product.avail gt 0 or $config.General.unlimited_products eq "Y"} in-stock{/if}">
            {if $product.avail gt 0 or $config.General.unlimited_products eq "Y"}
              {$lng.lbl_in_stock_top}
            {else}
              {$lng.lbl_out_stock}
            {/if}
          </div>
        {/if}
      </div>

      {if !($product.product_type eq "C" and $active_modules.Product_Configurator)}
        <div class="ui-block-b">
          <div class="right-block">
            <ul data-role="listview" data-inset="true">
              {if $product.appearance.has_market_price and $product.appearance.market_price_discount gt 0}
                {strip}
                  <li data-theme="c" class="save-percent-container" id="save_percent_box">
                    <span class="save">
                      {$lng.lbl_save}&nbsp;
                      <span id="save_percent">{$product.appearance.market_price_discount}</span>%
                    </span>
                  </li>
                {/strip}
              {/if}
              <li data-theme="b" id="top-cart-button">
                {strip}
                  <a href="{$catalogs.customer}/cart.php" onclick="javascript: $('#orderform-{$product.productid}').submit();">
                    {currency value=$product.taxed_price tag_id=""}
                    {if $product.appearance.added_to_cart}
                      {$lng.lbl_add_more}
                    {else}
                      {$lng.lbl_add_to_cart}
                    {/if}
                  </a>
                {/strip}
              </li>
            </ul>
          </div>
        </div>	   
      {/if}
    </div>
  </div>
</div>
<div class="product-details">
  <div class="image">
    <div class="image-box"{if $active_modules.Detailed_Product_Images and $images ne ''} style="display: block;"{/if}>
      {if $active_modules.Detailed_Product_Images and $images ne ''}
        <ul data-role="listview" data-inset="true">
          <li data-icon="false">
            <a href="{$current_location}/product.php?productid={$product.productid}&mobile_mode=get_detailed_images">
            {/if}
            <img src="{if $product.image_url}{$product.image_url|amp}{else}{$xcart_web_dir}/image.php?type={$type|default:"T"}&amp;id={$productid}{/if}" id="product_thumbnail" style="width: {$product.image_x}px; height: {$product.image_y}px;" alt="{$product.product}" />
            {if $active_modules.Detailed_Product_Images and $images ne ''}
            </a>
          </li>
        {/if}

        {if $active_modules.Detailed_Product_Images and $images ne ''}
          <li data-icon="plus" data-theme="b">
            <a href="{$current_location}/product.php?productid={$product.productid}&mobile_mode=get_detailed_images" >{$lng.lbl_more_images}</a>
          </li>
        </ul>
      {/if}
    </div>
  </div>
  <div class="details">
    {if $product.product_type eq "C" and $active_modules.Product_Configurator}
      {include file="modules/Product_Configurator/pconf_customer_product.tpl"}
    {else}
      {include file="customer/main/product_details.tpl"}
      {if $active_modules.Feature_Comparison ne ""}
        {include file="modules/Feature_Comparison/product_buttons.tpl"}
      {/if}
    {/if}
  </div>
</div>
{if $product_tabs}
  {foreach from=$product_tabs item=tab key=ind}
    <div data-role="collapsible" data-collapsed="true">
      <h3>{$tab.title}</h3>
      <div>{include file=$tab.tpl nodialog='Y'}</div>
    </div>
  {/foreach}
{/if}

{if $active_modules.Product_Options and ($product_options ne '' or $product_wholesale ne '') and ($product.product_type ne "C" or not $active_modules.Product_Configurator)}
  <script type="text/javascript">
    //<![CDATA[
    check_options();
    //]]>
  </script>
{/if}
