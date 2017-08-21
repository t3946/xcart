<tr>
    <td colspan="4">
        <table width="100%" class="products" cellspacing="1" cellpadding="3" data-level="{$parent_level}">
            <tr class="TableHead">
                <td colspan="2">Products</td>
            </tr>
            {if $products}
                {foreach $products as $product}
                    <tr data-description="{$product->fulldescr}">
                        <td class="checkbox">
                            <input type="checkbox" name="" />
                        </td>
                        <td>
                            <a href="{$product->getUrl()}" target="_blank">{$product->product}</a>
                        </td>
                    </tr>
                {/foreach}
            {/if}
        </table>
    </td>
</tr>