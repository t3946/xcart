<?php die(); ?>
[28-Oct-2015 03:58:02] (shop: 28-Oct-2015 03:58:02) SQL error:
    Site        : http://www.artistsupplysource.com
    Remote IP   : 185.44.237.223
    Logged as   : slavaz
    SQL query   : 
            Select Distinct OD.productid, P.amazon_fba_avail, P.productcode, P.product, P.cost_to_us,
                            (Select F.cpr_LandedPrice 
                             From xcart_cidev_amazon_fba_products F
                             Where F.productid = OD.productid
                             Order By F.report_date desc
                             Limit 1) As fba_min_price,
                             cidev_get_minimum_amazon_price(OD.productid) As our_min_price,
                 M.manufacturer As Supplier,
    (SELECT count(F.id) 
                     FROM xcart_cidev_amazon_fba_products F
                     Where F.productid = P.productid and F.lis_InStockSupplyQuantity>0 and F.report_date > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL - MONTH))) As StockingDays
    
            From xcart_orders O
                left join xcart_order_details OD ON OD.orderid = O.orderid
                inner join xcart_products P ON P.productid = OD.productid and P.forsale = 'Y'
            left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid
            Where O.amazon_fulfillment_channel = 'AFN' and O.date > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL - MONTH))
        
    Error code  : 1064
    Description : You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '))) As StockingDays
    
            From xcart_orders O
                left join xcart_order_detail' at line 11
Request URI: /admin/amazon_fba_restocking_report.php?mode=search
Backtrace:
/var/www/stores/include/func/func.db.php:235
/var/www/stores/include/func/func.db.php:150
/var/www/stores/admin/amazon_fba_restocking_report.php:110
-------------------------------------------------
<?php die(); ?>
[28-Oct-2015 03:58:02] (shop: 28-Oct-2015 03:58:02) SQL error:
    Site        : http://www.artistsupplysource.com
    Remote IP   : 185.44.237.223
    Logged as   : slavaz
    SQL query   : 
    Select 
                P.productcode As 'SKU',
                P.product As 'Product',
                M.manufacturer As 'Supplier',
                SUM(OD.amount) As 'Sold amount',
                COUNT(distinct O.orderid) As 'Sales',
                PR.price As 'X-Cart price',
                MAX(OD.price) As 'Maximum sale price (MFN price)',
                MAX(OD.price) - PR.price As 'Price delta',
                cidev_get_amazon_price(P.productid) As `Amazon price`,
                cidev_get_minimum_amazon_price(P.productid) As 'Our minimum FBA price'
                
    From xcart_products P
            left join xcart_order_details OD ON OD.productid = P.productid
            inner join xcart_orders O ON O.orderid = OD.orderid and O.amazon_fulfillment_channel = 'MFN'
            inner join xcart_order_groups OG ON OG.orderid = O.orderid and OG.manufacturerid = P.manufacturerid and OG.cb_status = 'P'
            left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid
            left join xcart_pricing PR ON PR.productid = P.productid and PR.quantity = 1
    where P.forsale = 'Y' and /*P.productcode like 'ALV-%' and*/ O.date > UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL - MONTH))
    Group By P.productid
    HAVING GROUP_CONCAT(O.amazon_fulfillment_channel) like '%MFN%' and MAX(OD.price) - PR.price > 0
    Order By (SUM(OD.amount)*COUNT(distinct O.orderid)*IF(MAX(OD.price) - PR.price<=0,1,ABS(MAX(OD.price) - PR.price)*10)) desc
        
    Error code  : 1064
    Description : You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '))
    Group By P.productid
    HAVING GROUP_CONCAT(O.amazon_fulfillment_channel) like '' at line 19
Request URI: /admin/amazon_fba_restocking_report.php?mode=search
Backtrace:
/var/www/stores/include/func/func.db.php:235
/var/www/stores/include/func/func.db.php:150
/var/www/stores/include/func/func.db.php:373
/var/www/stores/admin/amazon_fba_restocking_report.php:411
-------------------------------------------------
<?php die(); ?>
[28-Oct-2015 03:58:02] (shop: 28-Oct-2015 03:58:02) SQL error:
    Site        : http://www.artistsupplysource.com
    Remote IP   : 185.44.237.223
    Logged as   : slavaz
    SQL query   : 
    Select 
                P.productcode As 'SKU',
                P.product As 'Product name',
                M.manufacturer As 'Distributor',
                M.d_enable_feed As 'Has inventory feed',
                SUM(OD.amount) As 'Units sold',
                COUNT(distinct O.orderid) As 'Orders received',
                PR.price As 'X-Cart price',
                MAX(OD.price) As 'Maximum sale price',
                MAX(OD.price) - PR.price As 'Price delta',
                COALESCE(PriceBounce.fba_get_bb_price(P.productid),'No') As 'Parsed BuyBox price',
                cidev_get_amazon_price(P.productid) As `Our MFN price`,
                cidev_get_minimum_amazon_price(P.productid) As 'Our min FBA price',
                IF(PriceBounce.fba_get_bb_price(P.productid) = 0,COALESCE(1 / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, ),'No'), COALESCE(0.1*(PriceBounce.fba_get_bb_price(P.productid) - xcart_k.cidev_get_minimum_amazon_price(P.productid)) / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, ),'No')) As 'Composite index (E)'
    /*            COALESCE((PriceBounce.fba_get_bb_price(P.productid) - cidev_get_minimum_amazon_price(P.productid)) / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, ),'No') As 'Composite index (E)'*/
    From xcart_products P
            left join xcart_order_details OD ON OD.productid = P.productid
            inner join xcart_orders O ON O.orderid = OD.orderid and O.amazon_fulfillment_channel != 'AFN' and O.amazon_fulfillment_channel != 'MFN'
            inner join xcart_order_groups OG ON OG.orderid = O.orderid and OG.manufacturerid = P.manufacturerid and OG.cb_status = 'P'
            left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid
            left join xcart_pricing PR ON PR.productid = P.productid and PR.quantity = 1 
    where P.forsale = 'Y' and O.date > UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL - MONTH)) and M.d_enable_feed = 'Y' AND P.amazon_enabled != 'Y'
    Group By P.productid
    Order By IF(PriceBounce.fba_get_bb_price(P.productid) = 0,COALESCE(1 / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, ),0),
            COALESCE(0.1*(PriceBounce.fba_get_bb_price(P.productid) - xcart_k.cidev_get_minimum_amazon_price(P.productid)) / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, ),0)) desc
    /*COALESCE((PriceBounce.fba_get_bb_price(P.productid) - cidev_get_minimum_amazon_price(P.productid)) / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, ),0) desc, (SUM(OD.amount)*COUNT(distinct O.orderid)*IF(MAX(OD.price) - PR.price<=0,1,ABS(MAX(OD.price) - PR.price)*10)) desc*/
    
    Error code  : 1064
    Description : You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '),'No'), COALESCE(0.1*(PriceBounce.fba_get_bb_price(P.productid) - xcart_k.cidev' at line 14
Request URI: /admin/amazon_fba_restocking_report.php?mode=search
Backtrace:
/var/www/stores/include/func/func.db.php:235
/var/www/stores/include/func/func.db.php:150
/var/www/stores/include/func/func.db.php:373
/var/www/stores/admin/amazon_fba_restocking_report.php:501
-------------------------------------------------
