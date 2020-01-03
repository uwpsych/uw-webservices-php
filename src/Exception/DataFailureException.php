<?php

namespace UwPsych\UwWebservices\Exception;

use Exception;

class DataFailureException extends Exception
{
    public function __construct($url, $status, $msg)
    {
        parent::__construct(sprintf("Error fetching %s.  Status code: %s.  Message: %s.", $url, $status, $msg));
    }
}
