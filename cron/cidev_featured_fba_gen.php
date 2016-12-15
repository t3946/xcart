<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

$sql = /*** @lang MySQL */ <<<SQL
select *
from (
    SELECT  p.productid, 
            p.productcode, 
            p.product, 
            p.amazon_fba_avail,
            'F' as pfrom
            
    FROM xcart_products p
    JOIN xcart_products_sf ps ON ps.productid = p.productid and ps.sfid = :sfid
    
    WHERE p.forsale = 'Y' 
      and p.amazon_fba_avail >= 2
      and coalesce(cidev_get_amazon_method_available_on_xcart(p.productid),0) > 0
      and cidev_get_amazon_FBA_sold_items(p.productid, -3) / cidev_get_amazon_FBA_overall_instock_days(p.productid, 3) < 0.1000
    
    union
    (
        SELECT  od.productid, 
                p.productcode, 
                p.product,
                p.amazon_fba_avail,
                'O' as pfrom
                
        FROM xcart_order_details od
        JOIN xcart_orders o			ON od.orderid = o.orderid 
        JOIN xcart_products p		ON od.productid = p.productid 
        JOIN xcart_products_sf psf 	ON psf.productid = p.productid and psf.sfid = :sfid 
        JOIN xcart_images_T it		ON it.id = p.productid 
        
        WHERE o.date >= unix_timestamp((NOW() - INTERVAL 90 DAY))
          and o.cb_status in ('P', 'O')
          and p.forsale='Y'
          and it.id != ''
          LIMIT 50
    )
) p
order by FIELD(p.pfrom, 'F', 'O') asc
SQL;

func_query("truncate xcart_featured_products");

$sfids = func_query_column("select storefrontid as sfid from xcart_storefronts");
$sfids[] = 0;

foreach ( $sfids as $sfid)
{
    $values = [];
    $min_required = 50;
    $psql = str_replace(':sfid', $sfid, $sql);
    $products = func_query($psql);

    if (is_array($products))
    {
        foreach ($products as $n => $product)
        {
            if (($product['pfrom'] == 'F') || (($min_required > 0) && ($product['pfrom'] == 'O')) )
            {
                $min_required--;

                $values[] = "({$product['productid']}, {$sfid}, {$n})";
            }
            else { break; }
        }

        if (!empty($values))
        {
            func_query("insert into xcart_featured_products (productid, storefrontid, product_order) VALUES " . implode(', ', $values));
        }
    }
}


