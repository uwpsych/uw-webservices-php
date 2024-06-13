<?php

namespace UwPsych\UwWebservices\Api;

use UwPsych\UwWebservices\Client;
use UwPsych\UwWebservices\HttpClient\Message\ResponseMediator;
use UwPsych\UwWebservices\Exception\DataFailureException;

abstract class AbstractApi
{
    /**
     * Set the client options and default query parameters.
     */
    abstract public function setup(array $options, array $defaultParams): void;

    public function __construct(protected Client $client, protected array $options = [], protected array $defaultParams = [])
    {
        $this->client = $client;
        $this->setup($options, $defaultParams);
    }

    protected function get(string $path = '/'): mixed
    {
        $response = $this->client->getHttpClient()->get($path);

        // return an empty array if nothing was found
        if ($response->getStatusCode() === 404) {
            return [];
        }

        if ($response->getStatusCode() != 200) {
            throw new DataFailureException($path, $response->getStatusCode(), $response->getReasonPhrase());
        }

        return ResponseMediator::getContent($response);
    }

    protected function buildPath($fragment = '', $params = [])
    {
        if (empty($params)) {
            $queryComponent = '';
        } else {
            // merge the default params
            $params = array_merge($this->defaultParams, $params);

            // filter null values
            $params = array_filter($params);

            // build the query string
            $queryComponent = '?' . http_build_query($params);
        }

        $version = $this->options['version'];
        $format = $this->options['format'];

        return '/' . $version . '/' . $fragment . $format . $queryComponent;
    }
}
