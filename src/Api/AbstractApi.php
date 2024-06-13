<?php

namespace UwPsych\UwWebservices\Api;

use UwPsych\UwWebservices\Client;
use UwPsych\UwWebservices\HttpClient\Message\ResponseMediator;
use UwPsych\UwWebservices\Exception\DataFailureException;

abstract class AbstractApi
{
    /** @var Client */
    protected $client;

    /** @var array */
    protected $options;

    /** @var array */
    protected $defaultParams;

    /**
     * Set the client options and default query parameters.
     * 
     * @param $options
     * @param $defaultParams
     * 
     * @return void
     */
    abstract public function setup($options, $defaultParams);

    /**
     * @param Client $client
     * @param array $options
     * @param array $defaultParams
     */
    public function __construct(Client $client, $options = [], $defaultParams = [])
    {
        $this->client = $client;
        $this->setup($options, $defaultParams);
    }

    protected function get(string $path = '/', array $params = [], array $options = [])
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
            $query_component = '';
        } else {
            // merge the default params
            $params = array_merge($this->defaultParams, $params);

            // filter null values
            $params = array_filter($params);

            // build the query string
            $query_component = '?' . http_build_query($params);
        }

        $version = $this->options['version'];
        $format = $this->options['format'];

        return '/' . $version . '/' . $fragment . $format . $query_component;
    }
}
