{extends 'amazon/layouts/amazon_layout.tpl'}

{block 'heading'}
    <h1 align="center">Amazon reordering</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Products for amazon reordering'}
        {foreach $amazon_products as $distributor => $products}
        <div class="distributor">
            {$distributor}
        </div>
        <table width="100%" cellspacing="1" cellpadding="3">
            <tr class="TableHead">
                <td>SKU /<br/>Amazon SKU to load</td>
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
                <td>Restocking qty</td>
            </tr>
            {foreach $products as $product}
                <tr>
                    <td><a href="">{$product.productcode}</a>{if $product.productcode != $product.SKU}<br/>{$product.SKU}{/if}</td>
                    <td align="center">${$product.cost_to_us|formatprice:",":"."}</td>
                    <td align="center">{$product.amazon_fba}</td>
                    <td align="center">{$product.total_stock}</td>
                    <td align="center">${$product.price|formatprice:",":"."}</td>
                    <td align="center">{$product.last_order_days}</td>
                    <td align="center">{$product.items_sold_last_1m}</td>
                    <td align="center">{$product.instock_days_3m}</td>
                    <td align="center">{$product.items_sold_last_1m_of_stock}</td>
                    <td align="center">{$product.instock_days_1m}</td>
                    <td align="center">{$product.overall_orders_rate|formatprice:",":"."}</td>
                    <td align="center">{$product.orders_rate_last_1_month|formatprice:",":"."}</td>
                    <td align="center">{$product.dx_stock_qty}</td>
                    <td align="center"><input size="3" type="text" value="{$product.restocking_qty}" /></td>
                </tr>
            {/foreach}
        </table>
        {/foreach}
    {/smarty_admin_block}
{/block}