<?php

namespace UwPsych\UwWebservices\Tests;

use GuzzleHttp\Client as GuzzleClient;
use UwPsych\UwWebservices\Client;

trait MockApiTrait
{
    public function getApiMock($class)
    {
        $httpClient = $this->getMockBuilder(GuzzleClient::class)
            ->onlyMethods(['get'])
            ->getMock();

        $client = new Client('', '', '', $httpClient);

        return $this->getMockBuilder($class)
            ->onlyMethods(['get'])
            ->setConstructorArgs([$client])
            ->getMock();
    }

    public function loadJson($filename)
    {
        $basepath = dirname(__FILE__);
        $string = file_get_contents("{$basepath}/Unit/sample_data/{$filename}");
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
