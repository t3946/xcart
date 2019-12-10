<?php

namespace Modules\Core\Proxy;



use Modules\Core\Helpers\GuzzleDownloader;
use Xcart\App\Main\Xcart;

class Parser {

    const CHECKERPROXY_NET_API_URL = 'https://checkerproxy.net/api/archive/{{date}}';

    public static function getProxies() {
        return self::getProxychekerNetProxies();
    }

    private static function getDate($day_sub = 0) {
        $date = new \DateTime('now');
        if ($day_sub) {
            $date->sub(new \DateInterval('P1D'));
        }
        return $date->format('Y-m-d');
    }

    private static function getProxychekerNetProxies($day_sub = 0) {
        if ($validProxies = Xcart::app()->cache->get('proxies')) {
            $total = count($validProxies);
            echo "{$total} proxies found \n";
            return $validProxies;
        }

        $validProxies = [];
        $total = 0;
        while(!$total) {
            $url = str_replace('{{date}}', self::getDate($day_sub), self::CHECKERPROXY_NET_API_URL);
            echo "Get proxies list {$url} \n";
            $downloader = new GuzzleDownloader(['timeout' => 60]);
            $downloader->get($url);
            if ($response = $downloader->getInternalResponse()) {
                if ($json = json_decode($response->getContent(), true)) {
                    foreach ($json as $items) {
                        if ((int)$items['type'] === 2 && Helper::validateProxyIpPort($items['addr'])) {
                            $validProxies[] = $items['addr'];
                        }
                    }
                } else {
                    if (!$day_sub) {
                        return self::getProxychekerNetProxies(1);
                    }
                }
            }
            $total = count($validProxies);
        }
        echo "{$total} proxies found \n";
        $validProxies = Xcart::app()->cache->set('proxies', $validProxies, 6*60);
        return $validProxies;
    }

}
