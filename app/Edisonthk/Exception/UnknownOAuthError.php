<?php namespace App\Edisonthk\Exception;

use Exception;

class UnknownOAuthError extends Exception{

    protected $message = "Unknown OAuth Error.";
}