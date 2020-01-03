<?php

namespace UwPsych\UwWebservices\Api;

use UwPsych\UwWebservices\Client;
use UwPsych\UwWebservices\HttpClient\Message\ResponseMediator;
use UwPsych\UwWebservices\Exception\InvalidRegIDException;
use UwPsych\UwWebservices\Exception\InvalidNetIDException;
use UwPsych\UwWebservices\Exception\InvalidEmployeeIDException;
use UwPsych\UwWebservices\Exception\InvalidStudentNumberException;
use UwPsych\UwWebservices\Exception\DataFailureException;

class AbstractApi
{
    /** @var Client */
    protected $client;

    /** @var array */
    protected $options;

    /** @var array */
    protected $defaultParams;

    /**
     * @param Client $client
     * @param array $options
     * @param array $defaultParams
     */
    public function __construct(Client $client, $options = [], $defaultParams = [])
    {
        $this->client = $client;

        $_options = ['format' => '.json'];
        $this->options = array_merge($_options, $options);

        $_defaultParams = [
            'page_size' => 10,
            'page_start' => 1,
            'changed_since_date' => null,
            'phone_number' => null,
            'mail_stop' => null,
            'home_dept' => null,
            'department' => null,
            'address' => null,
            'title' => null,
            'email' => null,
            'verbose' => null
        ];
        $this->defaultParams = array_merge($_defaultParams, $defaultParams);
    }

    protected function get(string $path = '/', array $params = [], array $options = [])
    {
        $response = $this->client->get($path);

        if ($response->getStatusCode() != 200) {
            throw new DataFailureException($path, $response->getStatusCode(), $response->getReasonPhrase());
        }

        return ResponseMediator::getContent($response);
    }

    /**
     * Function to validate multiple UW IDs.
     *
     * @param array $ids List of UW IDs.
     * @param string $type The type of ID. Can be any of netid, regid, employee_id, student_number, or prox_rfid.
     *
     * @return array
     */
    protected function validateIDs($ids, $type): array
    {
        $ids = is_string($ids) ? [$ids] : $ids;

        if (0 === count($ids)) {
            throw new InvalidArgumentException("The {$type}s parameter should be a single {$type} or an array of {$type}s");
        }

        foreach ($ids as $idx => $id) {
            $id = $this->validateID($id, $type);
            $ids[$idx] = $id;
        }

        return $ids;
    }

    /**
     * Function to validate a single UW ID.
     *
     * @param string $id UW ID.
     * @param string $type The type of ID. Can be any of netid, regid, employee_id, student_number, or prox_rfid.
     *
     * @return string
     */
    protected function validateID($id, $type): string
    {
        $exception_lookup = [
            'netid' => 'InvalidNetIDException',
            'regid' => 'InvalidRegIDException',
            'employee_id' => 'InvalidEmployeeIDException',
            'student_number' => 'InvalidStudentNumberException',
            'prox_rfid' => 'InvalidProxRFIDException'
        ];

        $regex_lookup = [
            'netid' => '/^[a-z][a-z0-9\-\_\.]{1,127}$/i',
            'regid' => '/^[A-F0-9]{32}$/i',
            'employee_id' => '/^\d{9}$/',
            'student_number' => '/^\d{7}$/',
            'prox_rfid' => '/^\d{16}$/'
        ];

        $regex = $regex_lookup[$type];
        $exception = $exception_lookup[$type];

        // transform netid and regid strings
        if ($type == 'netid') {
            $id = strtolower($id);
        } elseif ($type == 'regid') {
            $id = strtoupper($id);
        }

        if (!$this->isIDValid($id, $regex)) {
            throw new $exception($id);
        }

        return $id;
    }

    /**
     * Helper function to validate UW IDs
     *
     * @param string $id
     * @param string $regex
     *
     * @return bool
     */
    protected function isIDValid($id, $regex): bool
    {
        if (empty($id)) {
            return false;
        }

        return 1 === preg_match($regex, strval($id));
    }
}
