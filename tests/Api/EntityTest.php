<?php

namespace UwPsych\UwWebservices\Tests\Api;

class EntityTest extends TestCase
{
    /**
     * @return string
     */
    protected function getApiClass()
    {
        return \UwPsych\UwWebservices\Api\Entity::class;
    }

    public function testGetEntityByRegID()
    {
        $expectedArray = $this->loadJson('user-result-full.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/entity/ADA12DA10F7649B2A8861B14633F0A0A.json')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->getByRegID('ADA12DA10F7649B2A8861B14633F0A0A'));
    }

    public function testGetEntityByUwNetId()
    {
        $expectedArray = $this->loadJson('user-result-full.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/entity/testuser1.json')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->getByNetID('testuser1'));
    }

    public function testFindEntityByName()
    {
        $expectedArray = $this->loadJson('search-result-single.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/entity.json?page_size=10&page_start=1&display_name=Psych&only_entities=on')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByName('Psych'));
    }
}
