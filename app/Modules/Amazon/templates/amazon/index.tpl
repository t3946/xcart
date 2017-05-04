{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Products for amazon reordering'}
        {foreach $amazon_products as $distributor => $products}
        <table width="100%" cellspacing="1" cellpadding="3">
            <tr class="TableHead">
                <td>SKU</td>
                <td>Amazon SKU to load</td>
                <td>Cost to us</td>
                <td>Amazon FBA</td>
                <td>Total stock</td>
                <td>Current Amazon Price</td>
                <td>Last order days</td>
                <td>Items sold last 1m</td>
                <td>Instock days 3m</td>
                <td>Items sold last 1m of stock</td>
                <td>Instock days 1m</td>
                <td>Overall Orders rate</td>
                <td>Orders rate last 1 month</td>
                <td>Dx stock qty</td>
            </tr>
            {foreach $products as $product}
                <tr>
                    <td>$product.productcode</td>
                    <td>$product.SKU</td>
                    <td>$product.cost_to_us</td>
                    <td>$product.amazon_fba</td>
                    <td>$product.total_stock</td>
                    <td>$product.last_order_days</td>
                    <td>$product.items_sold_last_1m</td>
                </tr>
            {/foreach}
        </table>
        {/foreach}
    {/smarty_admin_block}
{/block}