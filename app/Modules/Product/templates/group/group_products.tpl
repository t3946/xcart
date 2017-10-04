<tr>
    <td colspan="4">
        <table width="100%" class="products" cellspacing="1" cellpadding="3" data-level="{$parent_level}">
            <tr class="TableHead">
                <td colspan="2">Products</td>
            </tr>
            {if $products}
                {foreach $products as $product}
                    <tr data-prefix="{$product->getNotModelAttribute('prefix')}-GROUP-{$product->getNotModelAttribute('g_max')}"
                        data-description="{$product->fulldescr}"
                        data-product-id="{$product->productid}"
                        data-manufacturer-id="{$product->manufacturerid}">
                        <td class="checkbox">
                            <input type="checkbox" name="group[products][{$product->productid}]" />
                        </td>
                        <td class="product-title" data-product="{$product->product}">
                            ({$product->productcode}) <a href="//{$site->domain}/product/{$product->productid}/" target="_blank">{$product->product}</a>
                        </td>
                    </tr>
                {/foreach}
            {/if}
        </table>
    </td>
</tr>