<?php

use UwPsych\UwWebservices\Api\Person;

beforeEach(function () {
    $this->api = $this->getApiMock(Person::class);
});

test('get_person_by_reg_id', function () {
    $expectedArray = $this->loadJson('user-result-full.json');

    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person/ADA12DA10F7649B2A8861B14633F0A0A/full.json')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->getByRegID('ADA12DA10F7649B2A8861B14633F0A0A'));
});

test('get_person_by_uw_net_id', function () {
    $expectedArray = $this->loadJson('user-result-full.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person/testuser1/full.json')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->getByNetID('testuser1'));
});

test('find_person_by_first_name', function () {
    $expectedArray = $this->loadJson('search-result-single.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&first_name=Test+W')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByName(['first_name' => 'Test W']));
});

test('find_person_by_last_name', function () {
    $expectedArray = $this->loadJson('search-result-multiple.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&last_name=User')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByName(['last_name' => 'User']));
});

test('find_person_by_first_and_last_name', function () {
    $expectedArray = $this->loadJson('search-result-single.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&first_name=Test+W&last_name=User')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByName(['first_name' => 'Test W', 'last_name' => 'User']));
});

test('find_person_by_first_and_last_name_with_wildcard', function () {
    $expectedArray = $this->loadJson('search-result-multiple.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&first_name=Test%2A&last_name=User')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByName(['first_name' => 'Test*', 'last_name' => 'User']));
});

test('find_person_by_uw_net_id_string', function () {
    $expectedArray = $this->loadJson('search-result-single.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&uwnetid=testuser1')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByNetID('testuser1'));
});

test('find_person_by_uw_reg_id', function () {
    $expectedArray = $this->loadJson('search-result-single.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&uwregid=ADA12DA10F7649B2A8861B14633F0A0A')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByRegID('ADA12DA10F7649B2A8861B14633F0A0A'));
});

test('find_person_by_uw_employee_id', function () {
    $expectedArray = $this->loadJson('search-result-single.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&employee_id=123456789')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByEmployeeID('123456789'));
});

test('find_person_by_uw_student_number', function () {
    $expectedArray = $this->loadJson('search-result-single.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&student_number=1234567')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByStudentNumber('1234567'));
});

test('find_person_by_uw_net_id_array', function () {
    $expectedArray = $this->loadJson('search-result-single.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&uwnetid=testuser1')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByNetID(['testuser1']));
});

test('find_person_by_multiple_ids', function () {
    $expectedArray = $this->loadJson('search-result-multiple.json');
    
    $this->api
        ->expects($this->once())
        ->method('get')
        ->with('/v2/person.json?page_size=10&page_start=1&uwnetid=testuser1%2Ctestuser2')
        ->will($this->returnValue($expectedArray));

    $this->assertEquals($expectedArray, $this->api->findByNetID(['testuser1', 'testuser2']));
});
