<?php

namespace Modules\User\Middleware;

use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Cli\Cli;
use Xcart\App\Middleware\Middleware;

class ExpireHeadersMiddleware extends Middleware
{
    public $isProcessRequest = true;

    public function processHttpRequest($request)
    {
        header('Vary: Accept-Encoding');

        if (!headers_sent()) {
//            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//            $this->noCache();
//            return;

            if (defined('AREA_TYPE') && AREA_TYPE == 'A') {
                $this->noCache();
            }
            else {
                if ($request->getIsAjax()) {
                    $this->noCache();
                }
                else {
                    if (!defined('SET_EXPIRE')) {
                        header("Cache-Control: public, max-age=3600, must-revalidate");
                    }

                    $last_modded = false;
                    foreach ( headers_list() as $header) {
                        $last_modded = $last_modded ?: strpos(strtolower($header), 'last-modified:') !== false;
                    }

                     ($last_modded) ?:
                        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

                    !defined("SET_EXPIRE") ?:
                        header("Expires: " . gmdate("D, d M Y H:i:s", SET_EXPIRE) . " GMT"); // is defined
                }
            }
        }
    }

    public function noCache()
    {
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");

//        header("Vary: User-Agent");
//        header("Pragma: no-cache");
    }
}
