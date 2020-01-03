<?php

namespace UwPsych\UwWebservices\Exception;

use Exception;

class InvalidStudentNumberException extends Exception
{
    public function __construct($student_number)
    {
        parent::__construct(sprintf('The given student_number was invalid: ("%s")!', $student_number));
    }
}
