<?php

namespace Xcart\App\Middleware;

use Xcart\App\Request\HttpRequest;

class AjaxRedirectMiddleware extends Middleware
{
    public $isProcessResponse = true;

    public function processResponse($request)
    {
        /** @var HttpRequest $request */
        if ($request->getIsPost() && $request->getIsAjax()) {
            header("Location: " . $request->getPath());
            header("HTTP/1.1 278 OK", true, 278);
        }
    }
}
