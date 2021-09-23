<?php

namespace Modules\Core\Helpers;

use Symfony\Component\DomCrawler\Crawler;

interface DownloaderInterface
{
    public function fetch($url, $type = 'GET', $params = [], $files = []);
    public function get($url, $type = 'GET', $params = [], $files = []);
    public function checkLogin(Crawler $crawler): bool;
    public function beforeProcess();
}
