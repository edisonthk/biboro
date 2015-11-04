<?php namespace App\Edisonthk\Exception;

use Exception;

class WorkbookOrderWrongFormat extends Exception{

    protected $message = "Workbook order must be unique.";
}