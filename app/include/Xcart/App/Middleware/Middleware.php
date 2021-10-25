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
     * @param Request|HttpRequest $request
     */
    public function processRequest(Request $request)
    {
        $this->requestIsWeb($request) ?
            $this->processHttpRequest($request) :
            $this->processCliRequest($request);
    }

    /**
     * @param Request $request
     */
    public function processCliRequest($request) {}

    /**
     * @param HttpRequest $request
     */
    public function processHttpRequest(HttpRequest $request) {}

    /**
     * Event owner RenderTrait
     * @param Request $request
     * @param $output string
     */
    public function processView(Request $request, string $output) {}

    public function processException(Exception $exception) {}

    /**
     * @param Request|HttpRequest $request
     */
    public function processResponse(Request $request)
    {
        $this->requestIsWeb($request) ?
            $this->processHttpResponse($request) :
            $this->processCliResponse($request);
    }


    /**
     * @param Request $request
     */
    public function processCliResponse(CliRequest $request) {}

    /**
     * @param HttpRequest $request
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

    /**
     * @param Request $request
     */
    public function processEnd($request) {}

}
