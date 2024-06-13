<?php

namespace UwPsych\UwWebservices\Exception;

use Exception;

class DataFailureException extends Exception
{
    public function __construct($url, $status, $msg)
    {
        parent::__construct("Error fetching {$url},  Status code: {$status},  Message: {$msg}");
    }
}
