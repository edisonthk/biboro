<?php namespace Biboro\Edisonthk\Exception;

use Exception;

class MissingLoginedUserInfo extends Exception{

    protected $message = "User required logined to get missing logined user info.";
}