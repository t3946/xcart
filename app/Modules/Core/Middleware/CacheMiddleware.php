<?php

namespace Modules\Core\Middleware;

use Detection\MobileDetect;
use Modules\Core\Helpers\Cache;
use Xcart\App\Cli\Cli;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;
use Xcart\App\Request\HttpRequest;

class CacheMiddleware extends Middleware
{
    public $globalCacheTime = 360;
    public $cacheDriver = 'html';
    public $cacheEnabled = true;

    protected $show_from_cache = false;
    protected $params = false;

    public function processHttpRequest($request)
    {
        /** @var \Xcart\App\Request\HttpRequest $request */

        if (!headers_sent() && $this->cacheEnabled) {

            $ignoreCache = ($request->get->has('no_cache') || $request->cookie->has('no_cache'));

            $params = [];
            $params = $params ?: $this->getAdvancedDetector($request);
//            $params = $params ?: $this->getDetector($request);

            if ($params) {
                $this->params = $params;
                [$cacheTime, $key, $a_output] = $params;

                if ($a_output && !$ignoreCache) {
                    list($output, $headers, $etag, $modTime, $wheres) = $a_output;

                    if (empty($wheres) ||
                        (
                            $wheres['domain'] == $request->getDomain()
                        )
                    )
                    {
                        if ($request->getHeaderValue('IF_NONE_MATCH') == "\"{$etag}\"") {
                            header("HTTP/1.1 304 Not Modified");
                            Xcart::app()->end();
                        }

                        foreach ($headers as $header) {
                            header($header);
                        }

                        $this->setCacheHeaders($modTime, $cacheTime, $etag);

                        echo $output;
                        Xcart::app()->end();
                    }
                }
            }
        }
    }

    public function processView($request, &$output)
    {
        if (!Cli::isCli()) {

            /** @var \Xcart\App\Request\HttpRequest $request */
            $match = Xcart::app()->router->match($request->getUrl(), $request->getMethod());

            if ($cacheTime = $this->getCacheTime($match)) {
                [$modTime, $etag] = $this->saveCache($output);


                $this->setCacheHeaders($modTime, Cache::CACHE_HALF_HOUR, $etag);
            }
        }
    }

//    public function processEnd($request)
//    {
//        if (!Cli::isCli() && ! $this->show_from_cache) {
//
//           $this->saveCache(ob_get_clean());
//        }
//    }

    private function saveCache($output)
    {
        [$cacheTime, $key] = $this->params;


        $headers = array_filter(headers_list(), function($header) {
            if (!preg_match('/(X-Powered-By|Set-Cookie)/', $header)) {

                return $header;
            }

            return false;
        });

        $data = [$output, $headers];
        $etag = md5(serialize($data));
        $modTime = gmdate("D, d M Y H:i:s", time());
        $data[] = $etag;
        $data[] = $modTime;
        $data[] = [
            'domain' => Xcart::app()->request->getDomain(),
        ];

        Xcart::app()->cache->getDriver($this->cacheDriver)->set($key, $data, $cacheTime?:null);

        return [$modTime, $etag];
    }

    public function processSave($output)
    {
        if ($this->params && $this->cacheEnabled) {
            [$modTime, $etag] = $this->saveCache($output);

            $this->setCacheHeaders($modTime, Cache::CACHE_HALF_HOUR, $etag);
        }
    }

    private function getDetector($request)
    {
        /** @var HttpRequest $request */
        $match = Xcart::app()->router->match($request->getUrl(), $request->getMethod());

        if ($cacheTime = $this->getCacheTime($match)) {
            $key = $this->getCacheKey($request, $match);

            $a_output = Xcart::app()->cache->getDriver($this->cacheDriver)->get($key);
            return [$cacheTime, $key, $a_output];
        }

        return false;
    }

    private function getAdvancedDetector($request)
    {
        /** @var HttpRequest $request */

        if (!$request->getIsAjax()) {
            $detector = new MobileDetect();

            $isMobile = false;

            if ( ($detector->isMobile() || $detector->isTablet()) && Xcart::app()->request->session->get('mobile_view_trigger') != 'common' ) {
                $isMobile = true;
            }

            if ( !$request->getIsPost()
                && !$request->get->has('mobile_mode')
                && !$request->get->has('mode_search')
                && empty($request->getQueryArray())
                && in_array($request->getPath(),['', '/', '/home.php'])
            ) {
                $key = 'home-'. $request->getHost() . '-' . rand(1, 10);

                if ($isMobile) {
                    $key .= '-mobile';
                }

                $content = Xcart::app()->cache->getDriver($this->cacheDriver)->get($key);

                return [Cache::CACHE_HOUR * 3, $key, $content];
            }

            if (strpos($request->getPath(), 'amp/') === false )
            {
                if (strpos($request->getPath(), 'product') !== false && preg_match("/\/product\/(\d+)\/.*/", $request->getPath(), $match)) {
                    $key = 'product-' . $match[1];

                    if ($isMobile) {
                        $key .= '-mobile';
                    }

                    $content = Xcart::app()->cache->getDriver($this->cacheDriver)->get($key);

                    return [Cache::CACHE_YEAR, $key, $content];
                }
            }
        }

        return false;
    }


    private function getCacheKey($request, $match)
    {
        $advanced = [];

        if (is_array($match['target']) && isset($match['target'][0])) {
            $class = $match['target'][0];

            if ( is_subclass_of($class, FrontendController::class) ) {
                /** @var FrontendController $controller */
                $controller = new $class($request);
                $advanced = $controller->getAdvancedCacheData();
            }
        }

        /**  @var \Xcart\App\Request\HttpRequest $request*/
        return implode('-', $advanced) . $request->getServerName() .$request->getUrl().$request->getMethod().$request->getQueryString();
    }

    private function getCacheTime($match)
    {
        if (!empty($match['config']) && !empty($match['config']['cache']) && $match['config']['cache'])
        {
            if (!empty($match['config']['cache']['time'])) {
                return $match['config']['cache']['time'];
            }
            else {
                return $this->globalCacheTime;
            }
        }

        return null;
    }


    private function setCacheHeaders($modTime, $lifeTime, $etag)
    {
        if (!headers_sent()) {
//            header("Last-Modified: {$modTime} GMT");
//            header("Cache-Control: max-age={$lifeTime}");
            header("ETag: \"{$etag}\"");
        }
    }
}
