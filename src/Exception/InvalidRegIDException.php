<?php

namespace UwPsych\UwWebservices\Exception;

use Exception;

class InvalidRegIDException extends Exception
{
    public function __construct($regid)
    {
        parent::__construct("The given regid was invalid: {$regid}");
    }
}
