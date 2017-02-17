<?php

namespace Xcart\App\Middleware;

use Exception;
use Xcart\App\Request\Request;

interface IMiddleware
{
    public function processRequest(Request $request);

    /**
     * Event owner RenderTrait
     * @param \Xcart\App\Request\Request $request
     * @param $output string
     */
    public function processView(Request $request, &$output);

    /**
     * @param Exception $exception
     * @void
     */
    public function processException(Exception $exception);

    /**
     * @param Request $request
     * @return mixed
     */
    public function processResponse(Request $request);
}
