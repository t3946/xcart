<table class="restocking-table" width="100%" cellspacing="1" cellpadding="3" data-batch-id="{$batch_id}" data-manufacturer-name="{$distributor}" data-manufacturer-code="{$products[0].code}"
       data-manufacturer-address="{$products[0].m_address}" data-manufacturer-city="{$products[0].m_city}"
       data-manufacturer-country="{$products[0].m_country}" data-manufacturer-state="{$products[0].m_state}"
       data-manufacturer-zip="{$products[0].m_zipcode}">
    <tr class="no-export">
        <td colspan="17" align="right">
            <a class="fba-button" href="#">Save to FBA</a>
        </td>
        <td align="right">
            <a class="csv-button" href="#">Save to CSV</a>
        </td>
    </tr>
    <tr class="TableHead no-export">
        <td>SKU /<br/>Amazon SKU to load</td>
        <td>Amazon FBA</td>
        <td>Last order days</td>
        <td>Items sold last 1m</td>
        <td>Instock days 3m</td>
        <td>Items sold last 1m of stock</td>
        <td>Instock days 1m</td>
        <td>Orders rate last 1 month</td>
        <td>Overall Orders rate</td>
        <td>Cost to us</td>
        <td>Current Amazon Price</td>
        <td>Min FBA price</td>
        <td>AVG comp price</td>
        <td title="Amazon Average Daily Sales">ADSa</td>
        <td title="X-Cart Average Daily Sales">ADSx</td>
        <td>Dx stock qty</td>
        <td>Total stock</td>
        <td>Restocking qty<br/><input class="group-apply-val" style="width: 40px;" type="number" min="0"/><input style="line-height:16px;" class="group-apply" type="button" value="↓"/>
        </td>
    </tr>
    {foreach $products as $product}
        <tr class="{cycle ["", "TableSubHead"]}">
            <td class="fba-required"><a target="_blank" href="/admin/product_modify.php?productid={$product.productid}&switch_sf=true">{$product.productcode}</a>{if $product.productcode != $product.SKU}<br/>{$product.SKU}{/if}</td>
            <td align="center">{$product.amazon_fba}</td>
            <td align="center">{$product.last_order_days}</td>
            <td align="center">{$product.items_sold_last_1m}</td>
            <td align="center">{$product.instock_days_3m}</td>
            <td align="center">{$product.items_sold_last_1m_of_stock}</td>
            <td align="center">{$product.instock_days_1m}</td>
            <td align="center">{$product.orders_rate_last_1_month|formatprice:",":"."}</td>
            <td align="center">{$product.overall_orders_rate|formatprice:",":"."}</td>
            <td class="float" align="center">${$product.cost_to_us|formatprice:",":"."}</td>
            <td class="float" align="center">${$product.price|formatprice:",":"."}</td>
            <td class="float" align="center">${$product.min_fba_price|formatprice:",":"."}</td>
            <td align="center">{if $product.avg_comp_price >= 0}${$product.avg_comp_price|formatprice:",":"."}{/if}</td>
            <td align="center">{$product.ads_a}</td>
            <td align="center">{$product.asd_x}</td>
            <td align="center">{$product.r_avail}</td>
            <td align="center">{$product.total_stock}</td>
            <td class="fba-required" align="center"><input name="restocking_qty[{$batch_id}][{$product.productid}]" data-original-value="{$product.restocking_qty}" class="restocking-qty" size="3" type="text" value="{$product.restocking_qty}" /></td>
        </tr>
    {/foreach}
</table>