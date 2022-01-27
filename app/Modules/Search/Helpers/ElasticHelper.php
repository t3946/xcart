<?php

namespace Modules\Search\Helpers;


use Elastic\EnterpriseSearch\Client;

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

}