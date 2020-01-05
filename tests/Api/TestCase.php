<?php

namespace UwPsych\UwWebservices\Tests\Api;

use GuzzleHttp\Client as GuzzleClient;
use UwPsych\UwWebservices\Client;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return string
     */
    abstract protected function getApiClass();

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getApiMock()
    {
        $httpClient = $this->getMockBuilder(GuzzleClient::class)
            ->setMethods(['get', 'getStatusCode'])
            ->getMock();

        $client = new Client('', '', $httpClient);

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['get'])
            ->setConstructorArgs([$client])
            ->getMock();
    }

    protected function loadJson($filename)
    {
        $basepath = dirname(__FILE__);
        $string = file_get_contents("{$basepath}/sample_data/{$filename}");
        if ($string === false) {
            // deal with error...
        }

        $data = json_decode($string, true);
        if ($data === null) {
            // deal with error...
        }

        return $data;
    }
}
