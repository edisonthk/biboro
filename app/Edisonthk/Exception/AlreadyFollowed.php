<?php namespace App\Edisonthk\Exception;

use Exception;

class AlreadyFollowed extends Exception{

    protected $message = "Already followed";
}