<?php namespace App\Edisonthk\Exception;

use Exception;

class NotAllowedToEdit extends Exception{

    protected $message = "User don't have permission to edit";
}