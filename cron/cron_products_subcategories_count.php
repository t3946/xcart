<?php
define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

if ($config["cron_products_subcategories_count"] == "Y"){
        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cron_products_subcategories_count'");

$start_time = time();


$records = db_query($query = "
Select U.resourceid, MAX(U.`type`) as max_type, C.categoryid_path, C.product_count, C.global_product_count, C.subcategory_count
from xcart_cidev_updated_products U
            left join xcart_categories C ON C.categoryid = U.resourceid
where U.`type` IN (4,5) and FROM_UNIXTIME(U.time_stamp) < NOW()
Group By U.resourceid
order By MAX(U.`type`) desc, C.categoryid_path desc
");
$counter = 0;

while ($record = db_fetch_array($records)) {

    ###
    $counter++;
    if ($counter % 100 == 0) {
        func_flush(".");
        if ($counter % 5000 == 0) {
            func_flush("<br />\n");
        }
        func_flush();
    }
    ###

    /** @var \Modules\Goods\Models\CategoryModel $model */
    if ($model = \Modules\Goods\Models\CategoryModel::objects()->get(['pk' => $record['resourceid']])) {
        $model->reCalcSelfAndParents();
    }


    db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid='$record[resourceid]' AND (type='4' OR type='5')");
}
db_free_result($records);


db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cron_products_subcategories_count'");

print"<br />DONE!";
