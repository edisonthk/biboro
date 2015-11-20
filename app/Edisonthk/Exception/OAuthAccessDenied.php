<?php namespace Biboro\Edisonthk\Exception;

use Exception;

class OAuthAccessDenied extends Exception{

    protected $message = "User denied OAuth permissions.";
}