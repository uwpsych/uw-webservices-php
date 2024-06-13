<?php

namespace UwPsych\UwWebservices\Exception;

use Exception;

class InvalidEmployeeIDException extends Exception
{
    public function __construct($employee_id)
    {
        parent::__construct("The given employee_id was invalid: {$employee_id}");
    }
}
