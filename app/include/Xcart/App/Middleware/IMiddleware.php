<?php

namespace Xcart\App\Middleware;

use Exception;
use Xcart\App\Request\Request;

interface IMiddleware
{
    /**
     * @param \Xcart\App\Request\Request|\Xcart\App\Request\RequestManager $request
     *
     * @void
     */
    public function processRequest(Request $request);

    /**
     * Event owner RenderTrait
     * @param \Xcart\App\Request\Request|\Xcart\App\Request\RequestManager $request
     * @param $output string
     * @return string
     */
    public function processView(Request $request, string $output):string;

    /**
     * @param Exception $exception
     * @void
     */
    public function processException(Exception $exception);

    /**
     * @param \Xcart\App\Request\Request|\Xcart\App\Request\RequestManager $request
     * @return mixed
     */
    public function processResponse(Request $request);

    /**
     * @param \Xcart\App\Request\Request|\Xcart\App\Request\RequestManager $request
     * @return mixed
     */
    public function processEnd($request);
}
