<?php

namespace Modules\Search\Helpers;


use Elastic\EnterpriseSearch\AppSearch\Request\CreateEngine;
use Elastic\EnterpriseSearch\AppSearch\Request\DeleteDocuments;
use Elastic\EnterpriseSearch\AppSearch\Request\GetEngine;
use Elastic\EnterpriseSearch\AppSearch\Request\IndexDocuments;
use Elastic\EnterpriseSearch\AppSearch\Request\QuerySuggestion;
use Elastic\EnterpriseSearch\AppSearch\Request\Search;
use Elastic\EnterpriseSearch\AppSearch\Schema\Engine;
use Elastic\EnterpriseSearch\AppSearch\Schema\PaginationResponseObject;
use Elastic\EnterpriseSearch\AppSearch\Schema\QuerySuggestionRequest;
use Elastic\EnterpriseSearch\AppSearch\Schema\SearchRequestParams;
use Elastic\EnterpriseSearch\AppSearch\Schema\SimpleObject;
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

    public function delete(string $engine, array $documents): void
    {
        $request = new DeleteDocuments($engine, $documents);

        $this->getClient()->appSearch()->deleteDocuments($request);

    }

    public function search(string $engine, string $query, int $page, int $size): array
    {
        $searchParam = new SearchRequestParams(trim($query));

        $searchObject = new SimpleObject();
        $searchObject->all = [(object)['is_group_root' => 0], (object)['in_stock' => 1]];

        $paginationObject = new PaginationResponseObject();
        $paginationObject->current = $page;
        $paginationObject->size = $size;

        $searchParam->filters = $searchObject;
        $searchParam->page = $paginationObject;

        $request = new Search($engine, $searchParam);

        return $this->getClient()->appSearch()->search($request)->asArray();
    }

    public function suggestion(string $engine, string $query, $size = 5): array
    {
        $query = trim($query);

        $request = new QuerySuggestionRequest();
        $request->query = $query;
        $request->types = (object)['documents' => ['fields' => ['product', 'brand', 'categories', 'productcode', 'upc']]];
        $request->size = $size;

        $suggestion = new QuerySuggestion($engine, $request);

        return $this->getClient()->appSearch()->querySuggestion($suggestion)->asArray();
    }

}