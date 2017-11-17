<?php

namespace Modules\User\Helpers;

use Xcart\App\Cli\Cli;
use Xcart\Helpers\CrawlerDetect\CrawlerDetect;
use Xcart\Helpers\CrawlerDetect\Crawlers;
use Xcart\Helpers\CrawlerDetect\CrawlersIp;
use Xcart\Helpers\CrawlerDetect\CrawlersUserAuth;

class BotsHelper
{
    private static $isRobot = null;

    public static function IsBot()
    {
        if (Cli::isCli()) {
            return false;
        }

        if (is_null(self::$isRobot)) {
            $cr = new CrawlerDetect;
            if ($cr->isCrawler()
                || $cr->setCrawlers(new Crawlers())->isCrawler()
                || $cr->setCrawlers(new CrawlersIp())->isCrawler()
                || $cr->setCrawlers(new CrawlersUserAuth())->isCrawler()
            ) {
                self::$isRobot = true;
            }
            else {
                self::$isRobot = false;
            }
        }

        return self::$isRobot;
    }
}