<?php

namespace UwPsych\UwWebservices\Tests\Api;

class PersonTest extends TestCase
{
    /**
     * @return string
     */
    protected function getApiClass()
    {
        return \UwPsych\UwWebservices\Api\Person::class;
    }

    public function testGetPersonByRegID()
    {
        $expectedArray = $this->loadJson('user-result-full.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person/ADA12DA10F7649B2A8861B14633F0A0A/full.json')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->getByRegID('ADA12DA10F7649B2A8861B14633F0A0A'));
    }

    public function testGetPersonByUwNetId()
    {
        $expectedArray = $this->loadJson('user-result-full.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person/testuser1/full.json')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->getByNetID('testuser1'));
    }

    public function testFindPersonByFirstName()
    {
        $expectedArray = $this->loadJson('search-result-single.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&first_name=Test+W')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByName(['first_name' => 'Test W']));
    }

    public function testFindPersonByLastName()
    {
        $expectedArray = $this->loadJson('search-result-multiple.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&last_name=User')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByName(['last_name' => 'User']));
    }

    public function testFindPersonByFirstAndLastName()
    {
        $expectedArray = $this->loadJson('search-result-single.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&first_name=Test+W&last_name=User')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByName(['first_name' => 'Test W', 'last_name' => 'User']));
    }

    public function testFindPersonByFirstAndLastNameWithWildcard()
    {
        $expectedArray = $this->loadJson('search-result-multiple.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&first_name=Test%2A&last_name=User')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByName(['first_name' => 'Test*', 'last_name' => 'User']));
    }

    public function testFindPersonByUwNetIdString()
    {
        $expectedArray = $this->loadJson('search-result-single.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&uwnetid=testuser1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByNetID('testuser1'));
    }

    public function testFindPersonByUwRegId()
    {
        $expectedArray = $this->loadJson('search-result-single.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&uwregid=ADA12DA10F7649B2A8861B14633F0A0A')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByRegID('ADA12DA10F7649B2A8861B14633F0A0A'));
    }

    public function testFindPersonByUwEmployeeId()
    {
        $expectedArray = $this->loadJson('search-result-single.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&employee_id=123456789')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByEmployeeID('123456789'));
    }

    public function testFindPersonByUwStudentNumber()
    {
        $expectedArray = $this->loadJson('search-result-single.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&student_number=1234567')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByStudentNumber('1234567'));
    }

    public function testFindPersonByUwNetIdArray()
    {
        $expectedArray = $this->loadJson('search-result-single.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&uwnetid=testuser1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByNetID(['testuser1']));
    }

    public function testFindPersonByMultipleIds()
    {
        $expectedArray = $this->loadJson('search-result-multiple.json');
        $api = $this->getApiMock();
        $api
            ->expects($this->once())
            ->method('get')
            ->with('/identity/v2/person.json?page_size=10&page_start=1&uwnetid=testuser1%2Ctestuser2')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->findByNetID(['testuser1', 'testuser2']));
    }
}
