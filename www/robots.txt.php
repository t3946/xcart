<?php

$default_host = 'www.artistsupplysource.com';
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
} else {
    $host = $default_host;
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
Sitemap: http://%s/sitemap.xml

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
Sitemap: http://%s/sitemap.xml
Crawl-delay: 15
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
