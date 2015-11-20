<?php namespace Biboro\Edisonthk\Exception;

use Exception;

class TagNotFound extends Exception{

    protected $message = "Tag not found in database.";
}