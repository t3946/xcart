<?php

namespace Xcart\App\Middleware;
use Exception;
use Xcart\App\Request\Request;

abstract class Middleware implements IMiddleware
{
    public function processRequest($request)
    {

    }

    /**
     * Event owner RenderTrait
     * @param \Xcart\App\Request\Request $request
     * @param $output string
     */
    public function processView($request, &$output)
    {
    }

    public function processException(Exception $exception)
    {

    }

    public function processResponse($request)
    {
    }
}
