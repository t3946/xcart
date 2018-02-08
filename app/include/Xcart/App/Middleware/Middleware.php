<?php

namespace Xcart\App\Middleware;
use Exception;
use Xcart\App\Cli\Cli;
use Xcart\App\Request\CliRequest;
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
    public function processRequest(Request $request)
    {
        $this->requestIsWeb($request) ?
            $this->processHttpRequest($request) :
            $this->processCliRequest($request);
    }

    /**
     * @param \Xcart\App\Request\Request $request
     */
    public function processCliRequest(CliRequest $request) {}

    /**
     * @param \Xcart\App\Request\HttpRequest $request
     */
    public function processHttpRequest(HttpRequest $request) {}

    /**
     * Event owner RenderTrait
     * @param \Xcart\App\Request\Request $request
     * @param $output string
     * @return string
     */
    public function processView(Request $request, string $output):string {}

    public function processException(Exception $exception) {}

    /**
     * @param \Xcart\App\Request\Request|\Xcart\App\Request\HttpRequest $request
     */
    public function processResponse(Request $request)
    {
        $this->requestIsWeb($request) ?
            $this->processHttpResponse($request) :
            $this->processCliResponse($request);
    }


    /**
     * @param \Xcart\App\Request\Request $request
     */
    public function processCliResponse(CliRequest $request) {}

    /**
     * @param \Xcart\App\Request\HttpRequest $request
     */
    public function processHttpResponse(HttpRequest $request) {}

    protected function requestIsWeb($request, $force = false)
    {
        if (is_null($this->requestIsWeb) || $force)
        {
            $this->requestIsWeb = (!Cli::isCli() || $request instanceof HttpRequest);
        }

        return $this->requestIsWeb;
    }

}
