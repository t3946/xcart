{*
$Id: pconf_customer_product.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $product.taxed_price gt 0 or $variant_price_no_empty}
  <div class="price-row">
    {$lng.lbl_pconf_base_price}:
    <span class="product-price-value">{currency value=$product.taxed_price tag_id="product_price"}</span>
    <span class="product-alt-price">{alter_currency value=$product.taxed_price tag_id="product_alt_price"}</span>
    {if $product.taxes}
      <div class="taxes">
        {include file="customer/main/taxed_price.tpl" taxes=$product.taxes}
      </div>
    {/if}
  </div>
{/if}
{$product.fulldescr|default:$product.descr}

<div class="button-row">
  {include file="customer/buttons/button.tpl" button_title=$lng.lbl_pconf_configure href="pconf.php?productid=`$product.productid`" additional_button_class="main-button" data_inline="false"}
</div>

