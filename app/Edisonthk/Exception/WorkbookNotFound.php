<?php namespace App\Edisonthk\Exception;

use Exception;

class WorkbookNotFound extends Exception{

    protected $message = "Workbook not found in database. Maybe deleted.";
}