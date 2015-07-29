<?php namespace App\Edisonthk\Exception;

use Exception;

class SnippetFoundInWorkbook extends Exception{

    protected $message = "Snippet found in specified workbook.";
}