<?php

namespace Modules\User\Middleware;

use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;

class ExpireHeadersMiddleware extends Middleware
{
    public $isProcessRequest = true;

    public function processHttpRequest($request)
    {
        header('Vary: Accept-Encoding, X-Requested-With');

        if (!headers_sent())
        {
            if ($request->getIsAjax()) {
                $this->noCache();
                return;
            }

            if ($match = Xcart::app()->router->match(Xcart::app()->request->getUrl(), Xcart::app()->request->getMethod())) {
                [$name] = explode(':', $match['name']);
                if ($name && \in_array($name, ['cart', 'checkout', 'payment'])) {
                    $this->noCache();
                    return;
                }
            }

            $this->autoLastModified();

            if (!defined('SET_EXPIRE')) {
                header("Cache-Control: public, max-age=3600, must-revalidate");
            }

            !defined("SET_EXPIRE") ?:
                header("Expires: " . gmdate("D, d M Y H:i:s", SET_EXPIRE) . " GMT"); // is defined


            if (defined('AREA_TYPE') && AREA_TYPE == 'A')
            {
                header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
                defined("SET_EXPIRE") ?:
                    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 10) . " GMT");
            }

            //TODO remove this. Session not created on main action
            Xcart::app()->request->session->get('category_sort');

        }
    }

    public function noCache()
    {
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
    }

    public function autoLastModified()
    {
        $last_modded = false;
        foreach ( headers_list() as $header) {
            $last_modded = $last_modded ?: strpos(strtolower($header), 'last-modified:') !== false;
        }

        ($last_modded) ?:
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    }
}
