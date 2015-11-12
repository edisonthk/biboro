<?php namespace App\Edisonthk\Exception;

use Exception;

class UserNotFound extends Exception{

    protected $message = "User not found in database. Maybe deleted.";
}