{*
$Id: simple_products_list.tpl 78 2012-12-28 13:59:37Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $products}
  <div class="products-matrix">
    {foreach from=$products item=product}
      <div class="item ui-shadow ui-btn-corner-all ui-btn-up-c">
        <a href="{$current_location}/product.php?productid={$product.productid}">
          <span class="image-box">
            {include file="product_thumbnail.tpl" productid=$product.productid product=$product.product tmbn_url=$product.tmbn_url}
          </span>
          <script type="text/javascript">
            //<![CDATA[
            products_data[{$product.productid}] = {ldelim}{rdelim};
            //]]>
          </script>
          <span class="product-title">
            {$product.product|amp}
          </span>
          {if $product.product_type ne "C"}
            {if $product.appearance.is_auction}
              <span class="price">{$lng.lbl_enter_your_price}</span><br />
              {$lng.lbl_enter_your_price_note}
            {else}
              {if $product.taxed_price gt 0}
                <span class="price-row">
                  <span class="price-value">{currency value=$product.taxed_price}</span>
                </span>
              {/if}
            {/if}
          {/if}
        </a>
      </div>
    {/foreach}
  </div>
{/if}
