<?php

use Modules\Sites\Models\SiteConfigModel;
use Modules\Sites\Models\SiteModel;

define("CIDEV_CRON_START", "CRON");

include 'top.inc.php';
include 'init.php';

if (defined('APP_LOCAL') && APP_LOCAL) {
    header("Content-type: text/plain");
    echo <<<ECHO
User-agent: *
Disallow: /
ECHO;
}



$default_host = 'www.artistsupplysource.com';
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
} else {
    $host = $default_host;
}
if (!(SiteConfigModel::objects()->get(['site__domain' => $host , 'name' => 'shop_closed', 'value__isnt' => 'Y']))) {
    $text = <<<ROBOTS
User-agent: *
Disallow: /
ROBOTS;

    header("Content-type: text/plain");
    printf($text);
    die();
}

if  ($host == 'www.artistsupplysource.com')
{
    $brush = '';
} else {
    $brush = '
Disallow: /brushes/';
}

if  ($host == 'www.justpokersupplies.com')
{
    $allow_ads_bot = '
User-agent: AdsBot-Google
Disallow:

User-agent: Googlebot-Image
Disallow:


';

    $jp_all_products = '';
} else {
    $jp_all_products = '';
    $allow_ads_bot = '';
}


$text = <<<ROBOTS

User-agent: Googlebot%s
Disallow: /admin/
Disallow: /provider/
Disallow: /verificator/
Disallow: /cgi-bin/
Disallow: /cart.php
Disallow: /help.php
Disallow: /giftcert.php
Disallow: /orders.php
Disallow: /register.php
Disallow: /search.php
Disallow: /*_openstat
Disallow: /shop_closed.html
Disallow: /retrieve_orders.php
Disallow: ?*page=
Disallow: ?*sort_direction=
Disallow: ?*sort=
Disallow: ?*target=
Disallow: ?*file=
Disallow: ?*path=
Disallow: ?*mode=
Disallow: ?*f_mode=
Sitemap: https://%s/sitemap.xml

User-agent: *%s
Disallow: /admin/
Disallow: /provider/
Disallow: /verificator/
Disallow: /cgi-bin/
Disallow: /cart.php
Disallow: /help.php
Disallow: /giftcert.php
Disallow: /orders.php
Disallow: /register.php
Disallow: /search.php
Disallow: /*_openstat
Disallow: /shop_closed.html
Disallow: /retrieve_orders.php
Disallow: ?*page=
Disallow: ?*sort_direction=
Disallow: ?*sort=
Disallow: ?*target=
Disallow: ?*file=
Disallow: ?*path=
Disallow: ?*mode=
Disallow: ?*f_mode=
Sitemap: https://%s/sitemap.xml
Host: %s
ROBOTS;

$text2 = <<<ROBOTS
User-agent: Googlebot
Allow:    /images/*
Disallow: /

User-agent: *
Allow:    /images/*
Disallow: /
Host: %s
ROBOTS;

header("Content-type: text/plain");
printf($text, $brush, $host, $brush, $host, $host);
