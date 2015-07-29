<?php namespace App\Edisonthk;

use App\Model\Profile;

class ProfileService {

    public function getByAccountId($accountId)
    {
        return Profile::where("account_id","=",$accountId)->first();
    }

    

}