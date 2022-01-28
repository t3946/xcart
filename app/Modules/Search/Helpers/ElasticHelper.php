<?php

namespace Modules\Search\Helpers;


use Elastic\EnterpriseSearch\AppSearch\Request\CreateEngine;
use Elastic\EnterpriseSearch\AppSearch\Request\GetEngine;
use Elastic\EnterpriseSearch\AppSearch\Request\IndexDocuments;
use Elastic\EnterpriseSearch\AppSearch\Schema\Engine;
use Elastic\EnterpriseSearch\Client;
use Elastic\EnterpriseSearch\Exception\ClientErrorResponseException;

class ElasticHelper
{
    public string $apiEndpoint = '';
    public string $apiKey = '';
    public ?Client $client = null;

    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = new Client([
                'host' => $this->apiEndpoint,
                'app-search' => [
                    'token' => $this->apiKey
                ]
            ]);
        }
        return $this->client;
    }

    public function checkEngine(string $name, string $lang_code): void
    {
        try {
            $this->getClient()->appSearch()->getEngine(new GetEngine($name));
        } catch (ClientErrorResponseException $e) {
            if ($e->getCode() === 404) {
                $new_engine = new Engine($name);

                $new_engine->language = $lang_code;

                $this->getClient()->appSearch()->createEngine(new CreateEngine($new_engine));
            }
        }
    }

    public function index(string $engine, array $documents): void
    {
        $request = new IndexDocuments($engine, $documents);

        $this->getClient()->appSearch()->indexDocuments($request);

    }

}