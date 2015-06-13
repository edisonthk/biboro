<?php namespace App\Http\Controllers;

use Response;
use App\Account;
use Illuminate\Routing\Controller as BaseController;


class AccountController extends BaseController {


	public $account_services;

	public function __construct(
		\App\Edisonthk\AccountService $account_services
	) {
		$this->account_services = $account_services;
	}

	public function getSignin()
	{	
		// user already login
		if($this->account_services->hasLogined()){
			return redirect("/account/success");
		}

		// retrieve authorization uri for login
		$url = $this->account_services->getOAuthorizationUri();
	
		return view("login_wrapper",["auth_url" => $url]);
	}

	public function getSuccess() {
		return "success to login";
	}

	// public function postLogin()
	// {
		// $accounts = Account::all();

		// return View::make('/account/login',[$my_account = $accounts]);
	// }

	public function getDevSignin()
	{
		$result = $this->account_services->login(1);
		if($result["success"]) {
			return redirect('/account/success');	
		}
        
        return redirect('/account/success?error='.$result["message"]);
	}


	public function getUserinfo()
	{
		if($this->account_services->hasLogined()){
			$user = $this->account_services->getLoginedUserInfo();
			$user["admin"] = $this->account_services->isAdmin();
			return Response::json($user, 200);
		}else{

			$user = $this->account_services->getUserFromRememberToken();
			if(!is_null($user)) {
				$user["admin"] = $this->account_services->isAdmin();
				return Response::json($user, 200);
			}
			
			return Response::json(null , 403);
		}
	}

	public function getSignout()
	{
		$this->account_services->logout();

        return redirect("/#/snippets");
	}

	public function getOauth2callback()
	{
		$result = $this->account_services->login();
		if($result["success"]) {
			return redirect('/account/success');	
		}
        
        return redirect('/account/success?error='.$result["message"]);
	}

}