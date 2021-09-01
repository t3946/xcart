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

    private static function fetchProxies($url)
    {
        echo "Get proxies list {$url} \n";
        $downloader = new GuzzleDownloader(['timeout' => 120]);
        $downloader->get($url);
        if ($response = $downloader->getInternalResponse()) {
            return $response->getContent();
        }
        return null;
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
            if (($response = self::fetchProxies($url)) && $json = json_decode($response, true)) {
                foreach ($json as $items) {
                    if ((int)$items['type'] === 2 && Helper::validateProxyIpPort($items['addr'])) {
                        $validProxies[] = $items['addr'];
                    }
                }
            }
            $url = 'https://api.proxyscrape.com/?request=getproxies&proxytype=http&timeout=10000&country=all&ssl=yes&anonymity=all';
            if (!$validProxies && ($response = self::fetchProxies($url)) && $json = explode("\n", $response)) {
                foreach ($json as $items) {
                    $p = trim($items);
                    if (Helper::validateProxyIpPort($p)) {
                        $validProxies[] = $p;
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
