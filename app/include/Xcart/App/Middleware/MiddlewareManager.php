<?php

namespace Xcart\App\Middleware;

use Exception;
use Xcart\App\Helpers\Creator;
use Xcart\App\Helpers\SmartProperties;
use Xcart\App\Request\Request;
use Xcart\App\Traits\Configurator;

class MiddlewareManager implements IMiddleware
{
    use Configurator, SmartProperties;

    /**
     * @var Middleware[]
     */
    public $middleware = [];

    /**
     * @var Middleware[]
     */
    private $_middleware = [];

    private $isProcessView = false;
    private $isProcessRequest = false;
    private $isProcessException = false;
    private $isProcessResponse = false;

    public function isProcessView()
    {
        return $this->isProcessView;
    }
    public function isProcessRequest()
    {
        return $this->isProcessRequest;
    }
    public function isProcessException()
    {
        return $this->isProcessException;
    }
    public function isProcessResponse()
    {
        return $this->isProcessResponse;
    }

    public function init()
    {
        foreach ($this->middleware as $name => $middleware)
        {
            /** @var Middleware $mw */
            $mw = Creator::createObject($middleware);

            $this->isProcessView ?: $this->isProcessView = $mw->isProcessView;
            $this->isProcessRequest ?: $this->isProcessRequest = $mw->isProcessRequest;
            $this->isProcessException ?: $this->isProcessException = $mw->isProcessException;
            $this->isProcessResponse ?: $this->isProcessResponse = $mw->isProcessResponse;

            $this->_middleware[$name] = $mw;
        }
    }

    public function processView(Request $request, string $output):string
    {
        foreach ($this->_middleware as $middleware) {
            $output = $middleware->processView($request, $output);
        }

        return $output;
    }

    public function processRequest(Request $request)
    {
        foreach ($this->_middleware as $middleware) {
            $middleware->processRequest($request);
        }
    }

    /**
     * @param Exception $exception
     * @void
     */
    public function processException(Exception $exception)
    {
        foreach ($this->_middleware as $middleware) {
            $middleware->processException($exception);
        }
    }

    public function processResponse(Request $request)
    {
        foreach ($this->_middleware as $middleware) {
            $middleware->processResponse($request);
        }
    }

    public function processEnd($request)
    {
        foreach ($this->_middleware as $middleware) {
            $middleware->processEnd($request);
        }
    }

    public function getMiddleware($name)
    {
        return $this->_middleware[$name] ?? null;
    }
}
