<?php

namespace Xcart\Helpers\CrawlerDetect;

use Jaybizzle\CrawlerDetect\Fixtures\AbstractProvider;

class CrawlersUserAuth extends AbstractProvider
{
    protected $data = null;
    private $raw_data
        = [
            "Indix" => ['indix'],
        ];

    public function __construct()
    {
        $data = [];
        foreach ($this->raw_data as $k => $v) {
            $v      = $this->clearData(implode('|', $v));
            $data[] = "($v)";
        }
        $this->data = $data;
    }

    private function clearData($data)
    {
        return preg_replace('/([\+\/\\\.@\(\)])/i', '\\\${1}', $data);
    }

    public function getCrawlerName($n)
    {
        $h = array_keys($this->raw_data);

        return $h[$n];
    }

    public function getMode()
    {
        return CrawlerDetect::MODE_BY_AUTH;
    }

}