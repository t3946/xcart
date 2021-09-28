<?php

use Xcart\App\Main\Xcart;

require_once '../app/include/vendors/autoload.php';
$config = include '../app/config/settings.php';

Xcart::init($config);

if (defined('APP_LOCAL') && APP_LOCAL) {
    header("Content-type: text/plain");
    echo <<<ECHO
User-agent: *
Disallow: /
ECHO;
    die();
}

$model = Xcart::app()->getModule('Sites')->getSite();

if (!$model || $model->shop_closed) {
    $text = <<<ROBOTS
User-agent: *
Disallow: /
ROBOTS;

    header("Content-type: text/plain");
    printf($text);
    die();
}

if ($model->code === 'AR') {
    $brush = '';
} else {
    $brush = '
Disallow: /brushes/';
}

if ($model->code === 'JP') {
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
Disallow: /giftcert.php
Disallow: /orders.php
Disallow: /register.php
Disallow: /search.php
Disallow: /*_openstat
Disallow: /shop_closed.html
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
Disallow: /giftcert.php
Disallow: /orders.php
Disallow: /register.php
Disallow: /search.php
Disallow: /*_openstat
Disallow: /shop_closed.html
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
printf($text, $brush, $model->domain, $brush, $model->domain, $model->domain);
