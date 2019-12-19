<?php

namespace Modules\Core\Helpers;


use Goutte\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class PoolClient extends Client
{
    public $headers = array();
    private $auth;
    public $timeout = 10;
    public $proxy;
    public $response;
    public $internalResponse;

    public function pool ($requests)
    {
        foreach (\GuzzleHttp\Pool::batch($this->getClient(), $requests, []) as $response) {

            if ($response instanceof  ResponseInterface) {
                $this->response = $this->createResponse($response);
                $this->internalResponse = $this->filterResponse($this->response);
                $crawler = $this->createCrawlerFromContent('', $this->response, null);
                $res[] = ['crawler' => $crawler, 'response' => $this->internalResponse];
            } else {
                if ($response instanceof RequestException) {
                    print $response->getMessage()."\n";
                } else {
                    print 'Error in response '. serialize($response);
                }
            }
        }

        return $res ?? [];
    }

    protected function doRequest($request)
    {
        $headers = array();
        foreach ($request->getServer() as $key => $val) {
            $key = strtolower(str_replace('_', '-', $key));
            $contentHeaders = array('content-length' => true, 'content-md5' => true, 'content-type' => true);
            if (0 === strpos($key, 'http-')) {
                $headers[substr($key, 5)] = $val;
            }
            // CONTENT_* are not prefixed with HTTP_
            elseif (isset($contentHeaders[$key])) {
                $headers[$key] = $val;
            }
        }

        $cookies = CookieJar::fromArray(
            $this->getCookieJar()->allRawValues($request->getUri()),
            parse_url($request->getUri(), PHP_URL_HOST)
        );

        $requestOptions = array(
            'cookies' => $cookies,
            'allow_redirects' => false,
            'auth' => $this->auth,
            'http_errors' => false,
            'timeout' => $this->timeout,
            'verify' => false
        );

        if ($this->proxy) {
            $requestOptions['proxy'] = [
                'https' => $this->proxy,
                'timeout' => $this->timeout
            ];
        }

        if (!in_array($request->getMethod(), array('GET', 'HEAD'))) {
            if (null !== $content = $request->getContent()) {
                $requestOptions['body'] = $content;
            } else {
                if ($files = $request->getFiles()) {
                    $requestOptions['multipart'] = [];

                    $this->addPostFields($request->getParameters(), $requestOptions['multipart']);
                    $this->addPostFiles($files, $requestOptions['multipart']);
                } else {
                    $requestOptions['form_params'] = $request->getParameters();
                }
            }
        }

        if (!empty($headers)) {
            $requestOptions['headers'] = $headers;
        }

        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach ($this->headers as $name => $value) {
            $requestOptions['headers'][$name] = $value;
        }

        // Let BrowserKit handle redirects
        try {
            $response = $this->getClient()->request($method, $uri, $requestOptions);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if (null === $response) {
                throw $e;
            }
        }

        return $this->createResponse($response);
    }
}
