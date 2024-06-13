<?php

namespace UwPsych\UwWebservices\Exception;

use Exception;

class InvalidProxRFIDException extends Exception
{
    public function __construct($proxRFID)
    {
        parent::__construct(sprintf('The given prox RFID was invalid: ("%s")!', $proxRFID));
    }
}
