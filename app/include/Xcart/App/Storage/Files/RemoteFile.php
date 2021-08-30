<?php

namespace Xcart\App\Storage\Files;

use Exception;
use GuzzleHttp\Client;

/**
 * Class RemoteFile.
 */
class RemoteFile extends ResourceFile
{
    private Client $client;

    public function __construct($url, $name = null, $tempDir = null)
    {
        $this->client = new Client(['verify' => false, 'timeout' => 30]);

        if (!$this->urlExists($url)) {
            throw new Exception("File not found");
        }

        $name = $name ?: basename(strtok($url, '?'));
        parent::__construct($this->client->get($url, ['http_errors' => false])->getBody()->getContents(), $name);
    }

    public function urlExists($url)
    {
        $res = $this->client->head($url, ['http_errors' => false]);

        $code = $res->getStatusCode();

        if ($code === 200) {
            return true;
        }
        if ($code === 404) {
            return false;
        }
        throw new Exception($res->getReasonPhrase());
    }
}
