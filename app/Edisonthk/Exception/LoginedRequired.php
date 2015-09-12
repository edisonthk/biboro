<?php namespace App\Edisonthk\Exception;

use Exception;

class LoginedRequired extends Exception{

    protected $message = "This action is required to login.";
}