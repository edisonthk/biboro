<?php namespace App\Edisonthk\Exception;

use Exception;

class UnknownFollowType extends Exception{
    protected $develop = true;
    protected $message = "Unknown follow type. ";

    public function setTypeDefined($developerDefinedType)
    {
        $this->message .= "Defined: ".$developerDefinedType;
    }
}