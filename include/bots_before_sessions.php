<?php
#
# Bot identificator module
#

use Xcart\Helpers\CrawlerDetect\CrawlerDetect;
use Xcart\Helpers\CrawlerDetect\Crawlers;
use Xcart\Helpers\CrawlerDetect\CrawlersIp;

if (!defined('XCART_START')) {
    header("Location: ../");
    die("Access denied");
}

header("Vary: User-Agent");

if (!empty($HTTP_USER_AGENT) && !defined("IS_ROBOT") && empty($is_robot))
{
    $cr = new CrawlerDetect;

    if (   $cr->isCrawler()
           || $cr->setCrawlers(new Crawlers())->isCrawler()
           || $cr->setCrawlers(new CrawlersIp())->isCrawler()
    ) {
        define("IS_ROBOT", 1);
    }
    unset($cr);

    if (defined("IS_ROBOT")) {
        $is_robot = 'Y';
    }
    else {
        $is_robot = 'N';
    }
}
elseif (defined("IS_ROBOT") && IS_ROBOT == 1) {
    $is_robot = 'Y';
}
elseif (!empty($is_robot) && $is_robot == 'Y') {
    define("IS_ROBOT", 1);
}
