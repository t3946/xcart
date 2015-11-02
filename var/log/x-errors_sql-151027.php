<?php die(); ?>
[27-Oct-2015 21:01:11] (shop: 27-Oct-2015 21:01:11) SQL error:
    Site        : http://www.artistsupplysource.com
    Remote IP   : 108.181.154.128
    Logged as   : 
    SQL query   : SELECT COUNT(*), xcart_products.productid FROM xcart_pricing, xcart_products LEFT JOIN xcart_quick_flags ON xcart_quick_flags.productid = xcart_products.productid LEFT JOIN xcart_variants as search_variants ON search_variants.productid = xcart_products.productid LEFT JOIN xcart_cidev_filter_products ON xcart_cidev_filter_products.productid = xcart_products.productid LEFT JOIN xcart_cidev_filter_values ON xcart_cidev_filter_values.fv_id = xcart_cidev_filter_products.fv_id LEFT JOIN xcart_cidev_filters ON xcart_cidev_filters.f_id = xcart_cidev_filter_values.f_id INNER JOIN xcart_products_sf ON xcart_products.productid=xcart_products_sf.productid AND xcart_products_sf.sfid = 0 INNER JOIN xcart_quick_prices ON xcart_quick_prices.productid = xcart_products.productid /*AND xcart_quick_prices.membershipid = 0*/ LEFT JOIN xcart_variants ON xcart_variants.productid = xcart_products.productid AND xcart_quick_prices.variantid = xcart_variants.variantid INNER JOIN xcart_products_categories ON xcart_products_categories.productid = xcart_products.productid INNER JOIN xcart_categories ON xcart_products_categories.categoryid = xcart_categories.categoryid WHERE xcart_quick_prices.priceid = xcart_pricing.priceid and xcart_pricing.quantity = 1 AND (IF(xcart_products_lng.productid != '', xcart_products_lng.product, xcart_products.product) LIKE '%plein air easels%' OR IF(xcart_products_lng.productid != '', xcart_products_lng.descr, xcart_products.descr) LIKE '%plein air easels%' OR IF(xcart_products_lng.productid != '', xcart_products_lng.fulldescr, xcart_products.fulldescr) LIKE '%plein air easels%' OR IF(xcart_products_lng.productid != '', xcart_products_lng.keywords, xcart_products.keywords) LIKE '%plein air easels%' OR IFNULL(search_variants.productcode, xcart_products.productcode) LIKE '%plein air easels%') AND xcart_products.forsale = 'Y' GROUP BY xcart_products.productid
    Error code  : 1054
    Description : Unknown column 'xcart_products_lng.productid' in 'where clause'
Request URI: /search.php?mode=search&page=1&substring=plein+air+easels&by_title=Y&by_shortdescr=Y&by_fulldescr=Y&by_sku=Y
Backtrace:
/var/www/stores/include/func/func.db.php:235
/var/www/stores/include/func/func.db.php:150
/var/www/stores/include/search.php:1251
/var/www/stores/search.php:146
-------------------------------------------------
[27-Oct-2015 21:01:12] (shop: 27-Oct-2015 21:01:12) SQL error:
    Site        : http://www.artistsupplysource.com
    Remote IP   : 108.181.154.128
    Logged as   : 
    SQL query   : SELECT COUNT(*), xcart_products.productid FROM xcart_pricing, xcart_products LEFT JOIN xcart_quick_flags ON xcart_quick_flags.productid = xcart_products.productid LEFT JOIN xcart_variants as search_variants ON search_variants.productid = xcart_products.productid LEFT JOIN xcart_cidev_filter_products ON xcart_cidev_filter_products.productid = xcart_products.productid LEFT JOIN xcart_cidev_filter_values ON xcart_cidev_filter_values.fv_id = xcart_cidev_filter_products.fv_id LEFT JOIN xcart_cidev_filters ON xcart_cidev_filters.f_id = xcart_cidev_filter_values.f_id INNER JOIN xcart_products_sf ON xcart_products.productid=xcart_products_sf.productid AND xcart_products_sf.sfid = 0 INNER JOIN xcart_quick_prices ON xcart_quick_prices.productid = xcart_products.productid /*AND xcart_quick_prices.membershipid = 0*/ LEFT JOIN xcart_variants ON xcart_variants.productid = xcart_products.productid AND xcart_quick_prices.variantid = xcart_variants.variantid INNER JOIN xcart_products_categories ON xcart_products_categories.productid = xcart_products.productid INNER JOIN xcart_categories ON xcart_products_categories.categoryid = xcart_categories.categoryid WHERE xcart_quick_prices.priceid = xcart_pricing.priceid and xcart_pricing.quantity = 1 AND (IF(xcart_products_lng.productid != '', xcart_products_lng.product, xcart_products.product) LIKE '%plein air easels%' OR IF(xcart_products_lng.productid != '', xcart_products_lng.descr, xcart_products.descr) LIKE '%plein air easels%' OR IF(xcart_products_lng.productid != '', xcart_products_lng.fulldescr, xcart_products.fulldescr) LIKE '%plein air easels%' OR IF(xcart_products_lng.productid != '', xcart_products_lng.keywords, xcart_products.keywords) LIKE '%plein air easels%' OR IFNULL(search_variants.productcode, xcart_products.productcode) LIKE '%plein air easels%') AND xcart_products.forsale = 'Y' GROUP BY xcart_products.productid
    Error code  : 1054
    Description : Unknown column 'xcart_products_lng.productid' in 'where clause'
Request URI: /search.php?mode=search&page=1&substring=plein+air+easels&by_title=Y&by_shortdescr=Y&by_fulldescr=Y&by_sku=Y
Backtrace:
/var/www/stores/include/func/func.db.php:235
/var/www/stores/include/func/func.db.php:150
/var/www/stores/include/search.php:1251
/var/www/stores/search.php:146
-------------------------------------------------
