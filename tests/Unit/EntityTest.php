<?php

namespace UwPsych\UwWebservices\Tests\Api;

use \UwPsych\UwWebservices\Api\Entity;

beforeEach(function () {
    $this->api = $this->getApiMock(Entity::class);
});

test('test_get_entity_by_reg_id', function () {
    $expectedArray = $this->loadJson('user-result-full.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/entity/ADA12DA10F7649B2A8861B14633F0A0A.json')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->getByRegID('ADA12DA10F7649B2A8861B14633F0A0A'));
});

test('test_get_entity_by_uw_net_id', function () {
    $expectedArray = $this->loadJson('user-result-full.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/entity/testuser1.json')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->getByNetID('testuser1'));
});

test('test_find_entity_by_name', function () {
    $expectedArray = $this->loadJson('search-result-single.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/entity.json?page_size=10&page_start=1&display_name=Psych&only_entities=on')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByName('Psych'));
});
