<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.12.2016
 * Time: 13:46
 */

namespace Xcart\Helpers\CrawlerDetect;

class CrawlerDetect extends \Jaybizzle\CrawlerDetect\CrawlerDetect
{
    public function setCrawlers($crawlers)
    {
        $this->crawlers = $crawlers;

        return $this;
    }

    public function setExclusions($exclusions)
    {
        $this->exclusions = $exclusions;

        return $this;
    }
}