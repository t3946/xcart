<?php

namespace Modules\Core\Middleware;

use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;

class CacheMiddleware extends Middleware
{
    public function processRequest($request)
    {
        if (Cli::isCli() == false) {
            /** @var \Xcart\App\Request\HttpRequest $request */

//            if (!headers_sent() && !($request->getIsAjax() || $request->getIsPjax()) ) {
            if (!headers_sent()) {

                $match = Xcart::app()->router->match($request->getUrl(), $request->getMethod());

                if (!empty($match['meta']) && !empty($match['meta']['cache']) && $match['meta']['cache']) {
                    if ($a_output = Xcart::app()->cache->get($this->getCacheKey($request))) {
                        list($output, $headers) = $a_output;

                        foreach ($headers as $header) {
                            header($header);
                        }
                        
                        echo $output;
                        die();
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

            if (!empty($match['meta']) && !empty($match['meta']['cache']) && $match['meta']['cache']) {

                $headers = array_filter(headers_list(), function($header) {

                    if (!preg_match('/(X-Powered-By|Set-Cookie)/', $header)) {

                        return $header;
                    }
                });

                Xcart::app()->cache->set($this->getCacheKey($request), [$output, $headers], $match['meta']['cache_time']?:null);
            }
        }
    }

    private function getCacheKey($request)
    {
        /**  @var \Xcart\App\Request\HttpRequest $request*/
        return $request->getServerName() .$request->getUrl().$request->getMethod().$request->getQueryString();
    }
}
