<?php
namespace Modules\Amazon\Stores;

use Xcart\App\Store\BaseStore;
use Xcart\Connection;

class AmazonStore extends BaseStore
{
    /**
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    public function populate(array $data)
    {

    }

    public function getAmazonProducts()
    {
        $sql= /** @lang MySQL */
            <<<SQL
SELECT 
m.manufacturer,
p.productid,
COALESCE (CASE WHEN af.amazon_listing_sku_to_load = '' THEN NULL END, p.productcode) as SKU,
cost_to_us,
amazon_fba,
cidev_get_amazon_FBA_stock_total(p.productid) + cidev_get_FBA_amount_in_working_shipments(p.productid) As `Total stock`,
cidev_get_amazon_FBA_lastorder_days(p.productid) As `Last order days`,
cidev_get_amazon_FBA_sold_items(p.productid, -1) As `Items sold last 1m`,
cidev_get_amazon_FBA_overall_instock_days(p.productid, 3) As `Instock days 3m`,
cidev_get_amazon_FBA_items_sold_for_last_stock_days(p.productid, 30) As `Items sold last 1m of stock`,
LEAST(cidev_get_amazon_FBA_overall_instock_days(p.productid, 9999),30) As `Instock days 1m`,
IFNULL(cidev_get_amazon_FBA_sold_items(p.productid, -9999) / cidev_get_amazon_FBA_overall_instock_days(p.productid, 9999),0) As `Overall Orders rate`,
IFNULL(cidev_get_amazon_FBA_items_sold_for_last_stock_days(p.productid, 30) / cidev_get_amazon_FBA_overall_instock_days(p.productid, 1),0) As `Orders rate last 1 month`,
cidev_get_amazon_price(p.productid) as price,
p.r_avail As `Dx stock qty`
FROM xcart_products p
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

        $aProducts = Connection::getInstance()->executeQuery($sql)->fetchAll(\PDO::FETCH_GROUP);
        return $aProducts;
    }
}