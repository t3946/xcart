{*
$Id: cart_contents.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<div class="products cart cart-contents">
  <ul data-role="listview" data-type="cart-products" data-theme="e" data-inset="true">
    {foreach from=$products item=product name=products}
      <li>
        <div class="cart-item">
          <div class="product-thumbnail">
            {strip}
              <div class="img-wrapper">
                {include file="product_thumbnail.tpl" productid=$product.display_imageid product=$product.product tmbn_url=$product.pimage_url type=$product.is_pimage image_x=$product.tmbn_x}
              </div>
            {/strip}
          </div>
          <div class="product-details">
            <h3>{$product.product}</h3>
            <div class="descr">
              <div class="left-block">
                <div class="sku">{$product.productcode}</div>

                {if $product.product_type eq "C" and $product.display_price lt 0}
                  <span class="pconf-negative-price"> {$lng.lbl_pconf_discounted}</span>
                {/if}
                {if $active_modules.Gift_Registry}
                  {include file="modules/Gift_Registry/product_event_cart_contents.tpl"}
                {/if}
                {if $cart.display_cart_products_tax_rates eq "Y"}
                  <div class="cart-column-tax">
                    {foreach from=$product.taxes key=tax_name item=tax}
                      {if $tax.tax_value gt 0}
                        <div style="white-space: nowrap;">
                          {if $cart.product_tax_name eq ""}
                            <span>{$tax.tax_display_name}:</span>
                          {/if}
                          {if $tax.rate_type eq "%"}
                          {$tax.rate_value}%{else}{currency value=$tax.rate_value}
                          {/if}
                        </div>
                      {/if}
                    {/foreach}
                  </div>
                {/if}
              </div>
              <div class="total right-block">
                <div class="price-row">
                  <span class="product-price-text">
                    {currency value=$product.display_price display_sign=$product.price_show_sign} &times; 
                    {if $config.Appearance.allow_update_quantity_in_cart eq "N" or ($active_modules.Egoods and $product.distribution) or ($active_modules.Product_Configurator and $product.hidden) or $link_qty eq "Y"}
                      <span id="cart-{$product.cartid}" class="amount">{$product.amount}</span>
                    {else}
                      <input type="nubmer" data-inline="true" class="amount-input" name="productindexes[{$product.cartid}]" value="{$product.amount}" />
                    {/if}
                    = </span>
                  <span class="product-price-value">
                    {multi x=$product.display_price y=$product.amount assign="total"}
                    {currency value=$total display_sign=$product.price_show_sign}
                  </span>
                </div>

              </div>
              <div class="clearing"></div>
            </div>
          </div>
        </div>
      </li>
    {/foreach}
  </ul>
</div>
{if $active_modules.Gift_Certificates ne ""}
  {include file="modules/Gift_Certificates/gc_checkout.tpl"}
{/if}
{*
<table cellspacing="1" class="cart-content width-100" summary="{$lng.lbl_products|escape}">
<tr class="head-row">
<th>{$lng.lbl_sku}</th>
<th class="cart-column-product">{$lng.lbl_product}</th>
{if $cart.display_cart_products_tax_rates eq "Y"}
<th class="cart-column-tax">
{if $cart.product_tax_name ne ""}
{$cart.product_tax_name}
{else}
{$lng.lbl_tax}
{/if}
</th>
{/if}
<th class="cart-column-price">{$lng.lbl_price}</th>
<th>{$lng.lbl_qty}</th>
<th class="cart-column-total">{$lng.lbl_total}</th>
</tr>
{foreach from=$products item=product name=products}
<tr{interline index=$smarty.foreach.products.index total=$list_length}>
<td>{$product.productcode}</td>
<td>
</td>

<td class="cart-column-price cart-content-text"></td>
<td class="cart-content-text">
</td>
<td class="cart-column-total cart-content-text">
</td>
</tr>
{/foreach}
</table>
*}