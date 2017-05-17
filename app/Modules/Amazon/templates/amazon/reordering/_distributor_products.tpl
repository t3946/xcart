<table class="restocking-table" width="100%" cellspacing="1" cellpadding="3" data-batch-id="{$batch_id}" data-manufacturer-name="{$distributor}" data-manufacturer-code="{$products[0].code}"
       data-manufacturer-address="{$products[0].m_address}" data-manufacturer-city="{$products[0].m_city}"
       data-manufacturer-country="{$products[0].m_country}" data-manufacturer-state="{$products[0].m_state}"
       data-manufacturer-zip="{$products[0].m_zipcode}">
    <tr class="no-export">
        <td colspan="19" align="right">
            <a class="fba-button" href="#">Save to FBA</a>
        </td>
        <td align="right">
            <a class="csv-button" href="#">Save to CSV</a>
        </td>
    </tr>
    <tr class="TableHead no-export">
        <td>Amazon SKU to load</td>
        <td>ASIN</td>
        <td>UPC</td>
        <td>Amazon FBA</td>
        <td title="Число дней с последнего заказа на Амазон">Last order days</td>
        <td title="Число штук проданных за последние 30 дней">Items sold last 1m</td>
        <td title="Дней в стоке за период последние 90 дней">Instock days 3m</td>
        <td title="Число штук проданных за последние 30 дней, когда продукт был в стоке">Items sold last 1m of stock</td>
        <td title="Дней в стоке за период последние 30 дней">Instock days 1m</td>
        <td title="Число заказов Амазон в день за последние 30 дней">Orders rate last 1 month</td>
        <td title="Число заказов Амазон в день за всю историю продукта">Overall Orders rate</td>
        <td title="Закупочная цена" >Cost to us</td>
        <td title="Текущая цена на Амазон (может быть MFN / AFN)">Current Amazon Price</td>
        <td title="Минимальная цена FBA со стандартной наценкой">Min FBA price</td>
        <td title="Средняя цена конкурентов за последние 60 дней">AVG comp price</td>
        <td title="Средние продажи FBA в день">ADSa</td>
        <td title="Средние продажи XCart с доставкой через Амазон  в день">ADSx</td>
        <td title="Остатки на складе дистрибьютора">Dx stock qty</td>
        <td title="Число штук на складе Amazon FBA (склад + inbound)">Total stock</td>
        <td title="">Restocking qty<br/><input class="group-apply-val" style="width: 40px;" type="number" min="0"/><input style="line-height:16px;" class="group-apply" type="button" value="↓"/>
        </td>
    </tr>
    {foreach $products as $product}
        <tr class="{cycle ["", "TableSubHead"]}">
            <td class="fba-required"><a target="_blank" href="/admin/product_modify.php?productid={$product.productid}&sf={$product.sfid}">{$product.SKU}</td>
            <td><a target="_blank" href="https://sellercentral.amazon.com/hz/inventory?_encoding=UTF8&asin={$product.ASIN}&ref=xx_invmgr_shel_home&tbla_myitable=sort:%7B%22sortOrder%22%3A%22ASCENDING%22%2C%22sortedColumnId%22%3A%22skucondition%22%7D;search:{$product.ASIN};pagination:1;">{$product.ASIN}</td>
            <td>{$product.UPC}</td>
            <td align="center">{$product.amazon_fba}</td>
            <td align="center">{$product.last_order_days}</td>
            <td align="center">{$product.items_sold_last_1m}</td>
            <td align="center">{$product.instock_days_3m}</td>
            <td align="center">{$product.items_sold_last_1m_of_stock}</td>
            <td align="center">{$product.instock_days_1m}</td>
            <td align="center">{$product.orders_rate_last_1_month|formatprice:",":"."}</td>
            <td align="center">{$product.overall_orders_rate|formatprice:",":"."}</td>
            <td class="float cost-to-us" align="center">${$product.cost_to_us|formatprice:",":"."}</td>
            <td class="float" align="center">${$product.price|formatprice:",":"."}</td>
            <td class="float" align="center">${$product.min_fba_price|formatprice:",":"."}</td>
            <td align="center">{if $product.avg_comp_price >= 0}${$product.avg_comp_price|formatprice:",":"."}{/if}</td>
            <td align="center">{$product.ads_a}</td>
            <td align="center">{$product.ads_x}</td>
            <td align="center">{$product.r_avail}</td>
            <td align="center">{$product.total_stock}</td>
            <td class="fba-required" align="center"><input style="width:3rem;" name="restocking_qty[{$batch_id}][{$product.productid}]" data-original-value="{$product.restocking_qty}" class="restocking-qty" size="3" type="number" value="{$product.restocking_qty}" /></td>
        </tr>
    {/foreach}
</table>