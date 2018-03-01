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

    private $requestIsWeb = null;

    /**
     * @param \Xcart\App\Request\Request|\Xcart\App\Request\HttpRequest $request
     */
    public function processRequest($request)
    {
        $this->requestIsWeb($request) ?
            $this->processHttpRequest($request) :
            $this->processCliRequest($request);
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
    public function processView($request, &$output) {}

    public function processException(Exception $exception) {}

    /**
     * @param \Xcart\App\Request\Request|\Xcart\App\Request\HttpRequest $request
     */
    public function processResponse($request)
    {
        $this->requestIsWeb($request) ?
            $this->processHttpResponse($request) :
            $this->processCliResponse($request);
    }


    /**
     * @param \Xcart\App\Request\Request $request
     */
    public function processCliResponse($request) {}

    /**
     * @param \Xcart\App\Request\HttpRequest $request
     */
    public function processHttpResponse($request) {}

    protected function requestIsWeb($request, $force = false)
    {
        if (is_null($this->requestIsWeb) || $force)
        {
            $this->requestIsWeb = (!Cli::isCli() || $request instanceof HttpRequest);
        }

        return $this->requestIsWeb;
    }

    /**
     * @param \Xcart\App\Request\Request $request
     */
    public function processEnd($request) {}

}
