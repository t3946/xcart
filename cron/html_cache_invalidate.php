<?php

use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

$desktop_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36';
$mobile_user_agent  = 'Mozilla/5.0 (Linux; Android 6.0.1; SM-G920V Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36';


$records = db_query("select t.* from xcart_cidev_updated_products t where t.`type` = 10 limit 200");

$guzzle = new \GuzzleHttp\Client();

while ($record = db_fetch_array($records)) {
    /** @var ProductModel $model */
    if ($model = ProductModel::objects()->get(['pk' => $record['resourceid']])) {
        $key = 'product-' . $model->pk;

        Xcart::app()->cache->getDriver('html')->set($key, null, 1);
        Xcart::app()->cache->getDriver('html')->set($key . '-mobile', null, 1);
        Xcart::app()->cache->getDriver('html')->set($key . '-mobile-ajax', null, 1);

        foreach ($model->sites as $site) {
            /** @var SiteModel $site */

            $ssl = ($site->getConfig()['https_enabled'] == 'Y');
            $url = ($ssl ? 'http' : 'https') . '://' . $site->domain . '/' . $model->getAbsoluteUrl();

            $guzzle->get($url, ['headers' => ['User-Agent' => $desktop_user_agent]]);
            $guzzle->get($url, ['headers' => ['User-Agent' => $mobile_user_agent]]);
        }
    }

    db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid='{$record['resourceid']}' AND type='10' and timeshtamp='{$record['time_stamp']}'");
}
db_free_result($records);