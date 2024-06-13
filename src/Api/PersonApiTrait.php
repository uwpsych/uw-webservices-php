<?php

namespace UwPsych\UwWebservices\Api;

use UwPsych\UwWebservices\Exception\InvalidRegIDException;
use UwPsych\UwWebservices\Exception\InvalidNetIDException;
use UwPsych\UwWebservices\Exception\InvalidEmployeeIDException;
use UwPsych\UwWebservices\Exception\InvalidStudentNumberException;
use UwPsych\UwWebservices\Exception\InvalidArgumentException;
use UwPsych\UwWebservices\Exception\InvalidProxRFIDException;

trait PersonApiTrait
{
    /**
     * Set the API options and default query parameters.
     */
    public function setup(array $options, array $defaultParams): void
    {
        $_options = [
            'format' => '.json',
            'full' => true,
            'version' => 'v2'
        ];
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

    /**
     * 
     * Function to validate multiple UW IDs.
     *
     * @param array $ids List of UW IDs.
     * @param string $type The type of ID. Can be any of netid, regid, employee_id, student_number, or prox_rfid.
     */
    protected function validateIDs($ids, $type): array
    {
        $ids = is_string($ids) ? [$ids] : $ids;

        if (0 === count($ids)) {
            throw new InvalidArgumentException("The {$type}s parameter should be a single {$type} or an array of {$type}s");
        }

        foreach ($ids as $idx => $id) {
            $ids[$idx] = $this->validateID($id, $type);
        }

        return $ids;
    }

    /**
     * Function to validate a single UW ID.
     *
     * @param string $id UW ID.
     * @param string $type The type of ID. Can be any of netid, regid, employee_id, student_number, or prox_rfid.
     */
    protected function validateID(string $id, string $type): string
    {
        $exceptionLookup = [
            'netid' => InvalidNetIDException::class,
            'regid' => InvalidRegIDException::class,
            'employee_id' => InvalidEmployeeIDException::class,
            'student_number' => InvalidStudentNumberException::class,
            'prox_rfid' => InvalidProxRFIDException::class
        ];

        $regexLookup = [
            'netid' => '/^[a-z][a-z0-9\-\_\.]{1,127}$/i',
            'regid' => '/^[A-F0-9]{32}$/i',
            'employee_id' => '/^\d{9}$/',
            'student_number' => '/^\d{7}$/',
            'prox_rfid' => '/^\d{16}$/'
        ];

        $regex = $regexLookup[$type];
        $exception = $exceptionLookup[$type];

        // transform netid and regid strings
        if ($type == 'netid') {
            $id = strtolower($id);
        } elseif ($type == 'regid') {
            $id = strtoupper($id);
        }

        if (! $this->isIDValid($id, $regex)) {
            throw new $exception($id);
        }

        return $id;
    }

    /**
     * Helper function to validate UW IDs
     */
    protected function isIDValid(string $id, string $regex): bool
    {
        if (empty($id)) {
            return false;
        }

        return 1 === preg_match($regex, strval($id));
    }
}
