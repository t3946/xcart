<?php

use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

$desktop_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36';
$mobile_user_agent  = 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Mobile Safari/537.36';

const LIMIT = 300;

$updated = 0;

function getResource()
{
    global $updated;
    $loop = true;

    while ($updated < LIMIT && $loop) {
        $ids = [];
        $records = db_query("select t.* from xcart_cidev_updated_products t where t.`type` = 10 ORDER BY t.time_stamp DESC limit 20");

        while ($record = db_fetch_array($records)) {
            yield $record;
            $ids[] = $record['resourceid'];
        }

        if ($ids) {
            $loop = true;
            $ids = implode(',', $ids);
            db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid in ({$ids}) and type='10' ");
        }
        else {
            $loop = false;
        }
    }
}


$guzzle = new \GuzzleHttp\Client();

foreach (getResource() as $record) {
    $key = 'product-' . $record['resourceid'];

    Xcart::app()->cache->getDriver('html')->set($key, null);
    Xcart::app()->cache->getDriver('html')->set($key . '-mobile', null);

    /** @var ProductModel $model */
    if ($model = ProductModel::objects()->get(['pk' => $record['resourceid']])) {
        foreach ($model->sites as $site) {
            /** @var SiteModel $site */

            if ($site->isWork()) {
                $updated++;

                $ssl = ($site->getConfig()['https_enabled'] == 'Y');
                $url = ($ssl ? 'https' : 'http') . '://' . $site->domain  . $model->getAbsoluteUrl();

                $guzzle->get($url, ['headers' => ['User-Agent' => $desktop_user_agent]]);
                $guzzle->get($url, ['headers' => ['User-Agent' => $mobile_user_agent]]);
            }
        }
    }
}

$sites = SiteModel::objects()->all();

if (mt_rand(0, 10000) < 10) {
    foreach ($sites as $site) {
        /** @var SiteModel $site */

        if ($site->isWork()) {
            for($i = 1; $i < 11; $i++) {
                Xcart::app()->cache->getDriver('html')->set('home-' . $site->domain. '-' . $i, null);
                Xcart::app()->cache->getDriver('html')->set('home-' . $site->domain. '-' . $i . '-mobile', null);
            }
        }
    }
}

if (rand(1, 7) > 5) {
    foreach ($sites as $site) {
        /** @var SiteModel $site */

        if ($site->isWork()) {
            $ssl = ($site->getConfig()['https_enabled'] == 'Y');
            $url = ($ssl ? 'https' : 'http') . '://' . $site->domain;

            $guzzle->get($url, ['headers' => ['User-Agent' => $desktop_user_agent]]);
            $guzzle->get($url, ['headers' => ['User-Agent' => $mobile_user_agent]]);
        }
    }
}

Xcart::app()->cache->gc(true);