<?php

namespace UwPsych\UwWebservices;

use GuzzleHttp\Client as GuzzleClient;
use UwPsych\UwWebservices\Api\Entity;
use UwPsych\UwWebservices\Api\Person;
use UwPsych\UwWebservices\Exception\InvalidArgumentException;

/**
 * PHP client for UW web services.
 *
 * @method Api\Person getByRegID()
 * @method Api\Person getByNetID()
 * @method Api\Person findByRegid()
 * @method Api\Person findByNetID()
 * @method Api\Person findByEmployeeID()
 * @method Api\Person findByStudentNumber()
 * @method Api\Person findByName()
 * @method Api\Entity getByRegID()
 * @method Api\Entity getByNetID()
 * @method Api\Entity findByName()
 *
 * Website: http://github.com/uwpsych/uw-webservices-php
 */
class Client
{
    /** @var GuzzleHttp\Client */
    private $httpClient;

    /**
     * Creates the UW web services client.
     *
     * @param string $cert Your authorized client certificate file.
     * @param string $ssl_key Your associated private key file.
     */
    public function __construct(string $baseUri, string $cert, string $ssl_key, $httpClient = null)
    {
        $this->httpClient =
            $httpClient ??
            new GuzzleClient([
                'base_uri' => $baseUri,
                'timeout' => 5.0,
                'cert' => $cert,
                'ssl_key' => $ssl_key,
                'http_errors' => false
            ]);
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function api($name)
    {
        switch ($name) {
            case 'person':
                $api = new Person($this);
                break;

            case 'entity':
                $api = new Entity($this);
                break;

            default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return $api;
    }

    /**
     * @return HttpMethodsClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
