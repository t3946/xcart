<?php

use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Pool;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductStorefrontModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

const desktop_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36';
const mobile_user_agent  = 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Mobile Safari/537.36';

const PROCESS = 'HTML_CACHE_INVALIDATE';
const DATEFORMAT = '%H:%I:%S';
const LIMIT = 5000;
const TIME_PRODUCTS_LIMIT = 113;

$cookie = ['418' => "I'm a teapot"];
$guzzle = new \GuzzleHttp\Client();
$date = new DateTime();
$time = time();
$updated = 0;
$requests = [];


function poolSend($client, &$requests)
{
    Pool::send($client, $requests, [
        'complete' => function (CompleteEvent $event) {},
        'error' => function (ErrorEvent $event) {},
    ]);

    $requests = [];
}

function writeLog($str)
{
    global $date;
    $diff = "[{$date->diff(new DateTime())->format(DATEFORMAT)}] ";
    func_backprocess_log(PROCESS, $diff.$str);
}

function checkTimeLimit()
{
    global $time;

    return (time() - $time) < TIME_PRODUCTS_LIMIT;
}

function getResourcesCount()
{
    return UpdatedProductModel::objects()->filter(['type' => 10])->count();
}

function getResource()
{
    global $updated;
    $loop = true;

    while ($updated < LIMIT && $loop && checkTimeLimit()) {
        $loop = false;

        $ids = [];
        $models = UpdatedProductModel::objects()->filter(['type' => 10])->order(['-time_stamp'])->limit(20)->all();

        if ($models) {
            $loop = true;

            foreach ($models as $model) {
                yield $model;
                $ids[] = $model->resourceid;
            }

            UpdatedProductModel::objects()->filter(['resourceid__in' => $ids, 'type' => 10])->delete();
        }
    }
}

function getEmptySurfMeta()
{
    $selected = 0;
    $limit = 1000;
    $loop = true;

    while ($limit > $selected && $loop ) {
        $loop = false;

        $models = \Modules\User\Models\SurfMetaModel::objects()
            ->filter(['is_mobile' => '', 'points_visited' => 0, 'date__lt' => time() - \Modules\Core\Helpers\Cache::CACHE_HALF_DAY])
            ->order(['-date'])
            ->limit(20)
            ->all();

        if ($models) {
            $loop = true;

            foreach ($models as $model) {
                yield $model;
                $selected++;
            }
        }
    }

    if ($selected) {
        writeLog("Looped surf meta from robot: {$selected}");
    }
}


$sites = [];
foreach (SiteModel::objects()->all() as $site) {
    $sites[$site->pk] = $site;
}

if ($resources_count = getResourcesCount()) {
    writeLog("Started. Resource count in queue: {$resources_count}");

    foreach (getResource() as $model) {
        $key = 'product-' . $model->resourceid;

        Xcart::app()->cache->getDriver('html')->set($key, null);
        Xcart::app()->cache->getDriver('html')->set($key . '-mobile', null);

        /** @var ProductModel $model */
        if ($model = ProductModel::objects()->get(['pk' => $model->resourceid])) {
            if ($model->group_root) {
                if ($model->isGroupChild()) {
                    $model_root = $model->parent;
                }
                else {
                    $model_root = $model;
                }

                if ($model_root) {
                    $model_root->forsale = $model_root->getFrontendChilds()->count() ? 'Y' : 'N';
                    $model_root->save();
                }
            }

            foreach (ProductStorefrontModel::objects()->filter(['productid' => $model->pk])->valuesList(['sfid'], true) as $sf_id)
            {
                /** @var SiteModel $site */
                $site = $sites[$sf_id];

                if ($site && $site->isWork()) {
                    $updated++;

                    $ssl = ($site->getConfig()['https_enabled'] == 'Y');
                    $url = ($ssl ? 'https' : 'http') . '://' . $site->domain  . $model->getAbsoluteUrl();

                    $requests[] = $guzzle->createRequest('GET', $url, ['headers' => ['User-Agent' => desktop_user_agent], 'cookies' => $cookie]);
                    $requests[] = $guzzle->createRequest('GET', $url, ['headers' => ['User-Agent' => mobile_user_agent ], 'cookies' => $cookie]);
                }
            }
        }

        if (count($requests) > 50) {
            poolSend($guzzle, $requests);
        }
    }

    if (count($requests)) {
        poolSend($guzzle, $requests);
    }

    writeLog("End products cache invalidate. Updated: {$updated}");
}


if (mt_rand(0, 10000) < 10) {

    writeLog( "Remove home cache started.");
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

    writeLog("Get home pages started.");
    foreach ($sites as $site) {
        /** @var SiteModel $site */

        if ($site->isWork()) {
            $ssl = ($site->getConfig()['https_enabled'] == 'Y');
            $url = ($ssl ? 'https' : 'http') . '://' . $site->domain;

            $requests[] = $guzzle->createRequest('GET', $url, ['headers' => ['User-Agent' => desktop_user_agent], 'cookies' => $cookie]);
            $requests[] = $guzzle->createRequest('GET', $url, ['headers' => ['User-Agent' => mobile_user_agent ], 'cookies' => $cookie]);
        }
    }

    poolSend($guzzle, $requests);
}

writeLog("Cache GC.");
Xcart::app()->cache->gc(true);

writeLog("Sessions GC.");
(new \Modules\User\Components\XcartSession())->gc(null);

/** @var \Modules\User\Models\SurfMetaModel $model */
foreach (getEmptySurfMeta() as $model) {
    \Modules\User\Models\SessionDataModel::objects()->delete(['sessid' => $model->sessid]);
}

#
# Clean temporary data
#
if ((rand() % 100) == 0) {
    db_query("DELETE FROM $sql_tbl[temporary_data] WHERE expire<UNIX_TIMESTAMP(NOW())");
}

writeLog("End.");