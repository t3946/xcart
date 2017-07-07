<?php

namespace Modules\Core\Middleware;

use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;

class CacheMiddleware extends Middleware
{
    public $globalCacheTime = 360;

    public function processRequest($request)
    {
        if (Cli::isCli() == false) {
            /** @var \Xcart\App\Request\HttpRequest $request */

//            if (!headers_sent() && !($request->getIsAjax() || $request->getIsPjax()) ) {
            if (!headers_sent()) {

                $match = Xcart::app()->router->match($request->getUrl(), $request->getMethod());

                if (!empty($match['config']) && !empty($match['config']['cache']) && $match['config']['cache']) {
                    if ($a_output = Xcart::app()->cache->get($this->getCacheKey($request))) {
                        list($output, $headers, $etag, $modTime) = $a_output;

                        if ($request->getHeaderValue('IF_NONE_MATCH') == "\"{$etag}\"") {
                            header("HTTP/1.1 304 Not Modified");
                            Xcart::app()->end();
                        }

                        foreach ($headers as $header) {
                            header($header);
                        }

                        header("Last-Modified: {$modTime} GMT");
                        header("Cache-Control: max-age={$this->globalCacheTime}");
                        header("ETag: \"{$etag}\"");

                        echo $output;
                        Xcart::app()->end();
                    }
                }
            }
        }
    }

    public function processView($request, &$output)
    {
        if (Cli::isCli() == false) {

            /** @var \Xcart\App\Request\HttpRequest $request */
            $match = Xcart::app()->router->match($request->getUrl(), $request->getMethod());

            if (!empty($match['config']) && !empty($match['config']['cache']) && $match['config']['cache']) {

                $headers = array_filter(headers_list(), function($header) {

                    if (!preg_match('/(X-Powered-By|Set-Cookie)/', $header)) {

                        return $header;
                    }
                });

                $data = [$output, $headers];
                $etag = md5(serialize($data));
                $modTime = gmdate("D, d M Y H:i:s", time());
                $data[] = $etag;
                $data[] = $modTime;

                if (!empty($match['config']['cache']['time'])) {
                    $cacheTime = $match['config']['cache']['time'];
                }
                else {
                    $cacheTime = $this->globalCacheTime;
                }

                header("Last-Modified: {$modTime} GMT");
                header("Cache-Control: max-age={$cacheTime}");
                header("ETag: \"{$etag}\"");

                Xcart::app()->cache->set($this->getCacheKey($request), $data, $cacheTime?:null);
            }
        }
    }

    private function getCacheKey($request)
    {
        /**  @var \Xcart\App\Request\HttpRequest $request*/
        return $request->getServerName() .$request->getUrl().$request->getMethod().$request->getQueryString();
    }
}
