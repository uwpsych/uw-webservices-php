<?php

namespace UwPsych\UwWebservices\Exception;

use Exception;

class InvalidNetIDException extends Exception
{
    public function __construct($netid)
    {
        parent::__construct("The given netid was invalid: {$netid}");
    }
}
