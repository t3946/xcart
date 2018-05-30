<?php

namespace Modules\Main\Controllers;


use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class RobotsController extends FrontendController
{
    public function actionIndex()
    {
        if (defined('APP_LOCAL') && APP_LOCAL) {
            header("Content-type: text/plain");
            echo <<<ECHO
User-agent: *
Disallow: /
ECHO;
            die();
        }

        $text = <<<ROBOTS

User-agent: Googlebot%s
Disallow: /admin/
Disallow: /provider/
Disallow: /verificator/
Disallow: /cgi-bin/
Disallow: /cart.php
Disallow: /cart/
Disallow: /checkout/
Disallow: /payment/
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
Disallow: /cart/
Disallow: /checkout/
Disallow: /payment/
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

        $host = Xcart::app()->getModule('Sites')->getSite();

        header("Content-type: text/plain");
        printf($text, '', $host->domain, '', $host->domain, $host->domain);
    }
}