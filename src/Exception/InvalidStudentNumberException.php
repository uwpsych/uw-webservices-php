<?php

namespace UwPsych\UwWebservices\Exception;

use Exception;

class InvalidStudentNumberException extends Exception
{
    public function __construct($student_number)
    {
        parent::__construct("The given student_number was invalid: '{$student_number}'");
    }
}
