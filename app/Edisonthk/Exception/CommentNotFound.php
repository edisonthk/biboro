<?php namespace Biboro\Edisonthk\Exception;

use Exception;

class CommentNotFound extends Exception{

    protected $message = "Comment not found in database.";
}