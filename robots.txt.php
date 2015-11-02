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
    $jp_all_products = '
Disallow: /product/*';
} else {
    $jp_all_products = '';
}


$text = <<<ROBOTS
User-agent: Googlebot%s
Disallow: /skin1_kolin/%s
Disallow: /cgi-bin/
Disallow: /cart.php
Disallow: /help.php
Disallow: /giftcert.php
Disallow: /orders.php
Disallow: /register.php
Disallow: /search.php
Disallow: /brands.php
Disallow: /*_openstat
Disallow: /shop_closed.html
Allow:    /category/50331/drawing-and-illustration/?page=2
Allow:    /category/50331/drawing-and-illustration/?page=3
Allow:    /category/50331/drawing-and-illustration/?page=4
Disallow: /page/*
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
Disallow: /skin1_kolin/%s
Disallow: /cgi-bin/
Disallow: /cart.php
Disallow: /help.php
Disallow: /giftcert.php
Disallow: /orders.php
Disallow: /register.php
Disallow: /search.php
Disallow: /brands.php
Disallow: /*_openstat
Disallow: /shop_closed.html
Allow:    /category/50331/drawing-and-illustration/?page=2
Allow:    /category/50331/drawing-and-illustration/?page=3
Allow:    /category/50331/drawing-and-illustration/?page=4
Disallow: /page/*
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

header("Content-type: text/plain");
printf($text, $brush, $jp_all_products, $host, $brush, $jp_all_products, $host, $host);
?>
