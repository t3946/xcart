<?php

namespace Modules\Search\Helpers;

use Elastic\AppSearch\Client\Client;
use Elastic\AppSearch\Client\ClientBuilder;

class ElasticHelper
{
    public string $apiEndpoint = '';
    public string $apiKey = '';
    public ?Client $client = null;

    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = ClientBuilder::create($this->apiEndpoint, $this->apiKey)->build();
        }
        return $this->client;
    }

}