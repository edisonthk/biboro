<?php namespace App\Edisonthk\Exception;

use Exception;

class SnippetNotFound extends Exception{

    protected $message = "Snippet not found in database. Maybe deleted.";
}