<?php

namespace UwPsych\UwWebservices;

use GuzzleHttp\Client as GuzzleClient;
use UwPsych\UwWebservices\Api\Entity;
use UwPsych\UwWebservices\Api\Person;

/**
 * PHP client for UW web services.
 */
class Client
{
    private \GuzzleHttp\Client $httpClient;

    public function __construct(string $baseUri, string $cert, string $sslKey, $httpClient = null)
    {
        $this->httpClient =
            $httpClient ??
            new GuzzleClient([
                'base_uri' => $baseUri,
                'timeout' => 5.0,
                'cert' => $cert,
                'ssl_key' => $sslKey,
                'http_errors' => false
            ]);
    }

    public function person(): Person
    {
        return new Person($this);
    }

    public function entity(): Entity
    {
        return new Entity($this);
    }

    public function getHttpClient(): \GuzzleHttp\Client
    {
        return $this->httpClient;
    }
}
