<?php

namespace Xcart\App\Middleware;
use Exception;
use Xcart\App\Cli\Cli;
use Xcart\App\Request\Request;
use Xcart\App\Request\HttpRequest;

abstract class Middleware implements IMiddleware
{
    public $isProcessRequest = false;
    public $isProcessView = false;
    public $isProcessException = false;
    public $isProcessResponse = false;

    /**
     * @param \Xcart\App\Request\Request|\Xcart\App\Request\HttpRequest $request
     */
    public function processRequest($request)
    {
        if (!Cli::isCli() || $request instanceof HttpRequest) {
            $this->processHttpRequest($request);
        }
        else {
            $this->processCliRequest($request);
        }
    }

    /**
     * @param \Xcart\App\Request\Request $request
     */
    public function processCliRequest($request) {}

    /**
     * @param \Xcart\App\Request\HttpRequest $request
     */
    public function processHttpRequest($request) {}

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
