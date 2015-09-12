<?php namespace App\Edisonthk;

use App\Model\Profile;

class ProfileService {

    private $account;

    public function __construct(\App\Edisonthk\AccountService $account)
    {
        $this->account = $account;
    }

    public function getMyAccount()
    {
        $user = $this->account->getLoginedUserInfo();
        return Profile::where("account_id","=",$user["id"])->first();
    }

    public function getByAccountId($accountId)
    {
        return Profile::where("account_id","=",$accountId)->first();
    }

    

}