<?php

namespace Modules\Amazon\Sqls;


class AmazonSql
{
    public static function getAmazonProductsForCalculateSql()
    {
        $sql= /** @lang MySQL */
            <<<SQL
SELECT p.productid
FROM xcart_products as p
INNER JOIN xcart_products_amz_fields af ON p.productid = af.productid
INNER JOIN xcart_manufacturers m ON p.manufacturerid = m.manufacturerid
INNER JOIN xcart_products_sf sf ON p.productid = sf.productid
INNER JOIN xcart_storefronts_external_marketplaces EM ON EM.storefront_id = sf.sfid AND EM.marketplace_id = 3
LEFT JOIN xcart_products_disabled_marketplaces DM ON DM.resource_id = p.productid and DM.resource_type = 'P' and DM.marketplace_id = 3
LEFT JOIN xcart_products_disabled_marketplaces DM2 ON DM2.resource_id = p.brandid and DM2.resource_type = 'B' and DM2.marketplace_id = 3
LEFT JOIN xcart_products_disabled_marketplaces DM3 ON DM3.resource_id = p.manufacturerid and DM3.resource_type = 'D' and DM3.marketplace_id = 3
WHERE p.amazon_enabled = 'Y' 
AND af.amazon_fba_restricted = 'N'
AND DM.marketplace_id IS NULL
AND DM2.marketplace_id IS NULL
AND DM3.marketplace_id IS NULL
SQL;
            return $sql;
    }

    public static function getAmazonReorderingSql($params)
    {
        $sql= /** @lang MySQL */
            <<<SQL
SELECT 
m.manufacturerid,
p.productid,
p.productcode,
COALESCE (CASE WHEN af.amazon_listing_sku_to_load = '' THEN NULL END, p.productcode) as SKU,
p.cost_to_us,
p.amazon_fba,
COALESCE(cidev_get_amazon_FBA_stock_total(p.productid), 0) + COALESCE(cidev_get_FBA_amount_in_working_shipments(p.productid), 0) As total_stock,
cidev_get_amazon_FBA_lastorder_days(p.productid) As last_order_days,
cidev_get_amazon_FBA_sold_items(p.productid, -1) As items_sold_last_1m,
cidev_get_amazon_FBA_overall_instock_days(p.productid, 3) As instock_days_3m,
cidev_get_amazon_FBA_items_sold_for_last_stock_days(p.productid, 30) As items_sold_last_1m_of_stock,
LEAST(cidev_get_amazon_FBA_overall_instock_days(p.productid, 9999),30) As instock_days_1m,
IFNULL(cidev_get_amazon_FBA_sold_items(p.productid, -9999) / cidev_get_amazon_FBA_overall_instock_days(p.productid, 9999),0) as overall_orders_rate,
IFNULL(cidev_get_amazon_FBA_items_sold_for_last_stock_days(p.productid, 30) / cidev_get_amazon_FBA_overall_instock_days(p.productid, 1),0) as orders_rate_last_1_month,
cidev_get_amazon_price(p.productid) as price,
cidev_get_minimum_amazon_price(p.productid) as min_fba_price,
cidev_get_amazon_competitive_price_stat(p.productid, -60, 'AVG') as avg_comp_price,
p.r_avail as r_avail,
amazon.restocking_get_reorder_quantity(p.productid, {$params['tau']}, {$params['tau_m']}, {$params['day_reorder']}, m.amazon_leadtime_to_ship, cidev_get_amazon_FBA_stock_total(p.productid) + cidev_get_FBA_amount_in_working_shipments(p.productid), 2, 'N') as restocking_qty
FROM xcart_products as p
INNER JOIN xcart_products_amz_fields af ON p.productid = af.productid
INNER JOIN xcart_manufacturers m ON p.manufacturerid = m.manufacturerid
WHERE p.productid = {$params['productid']}
SQL;

        return $sql;
    }
}