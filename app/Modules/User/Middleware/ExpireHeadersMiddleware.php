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

        if (!headers_sent()) {

            header("Vary: User-Agent");
//            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            $this->noCache();
            return;

            if (defined('AREA_TYPE') && AREA_TYPE == 'A') {
                $this->noCache();
            }
            else {
                if ($request->getIsAjax()) {
                    $this->noCache();
                }
                else {

                    $request->getIsSecureConnection() ?
                        header("Cache-Control: private, max-age=3600, must-revalidate") :
                        header("Cache-Control: public, max-age=3600");

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
        header("Pragma: no-cache");
    }
}
