<?php namespace App\Http\Controllers;

use Response;
use App\Account;
use Illuminate\Routing\Controller as BaseController;


class AccountController extends BaseController {


	public $accountServices;

	public function __construct(
		\App\Edisonthk\AccountService $accountServices
	) {
		$this->accountServices = $accountServices;
	}

	public function getSignin()
	{	
		// user already login
		if($this->accountServices->hasLogined()){
			return redirect("/account/success");
		}

		// retrieve authorization uri for login
		$url = $this->accountServices->getOAuthorizationUri();
        $code = $this->accountServices->getAuthorizationCode();
	
		return view("login_wrapper",[
            "action" => "login",
            "auth_url" => $url, 
            "code" => $code,
        ]);
	}

	public function getSuccess() {
		return view("login_wrapper",[
            "action" => "success",
            "requested_uri" => $this->accountServices->getRequestedUri(),
        ]);
	}

	// public function postLogin()
	// {
		// $accounts = Account::all();

		// return View::make('/account/login',[$my_account = $accounts]);
	// }

	public function getDevSignin()
	{
		$result = $this->accountServices->login(1);
		if($result["success"]) {
			return redirect('/account/success');	
		}
        
        return redirect('/account/success?error='.$result["message"]);
	}


	public function getUserinfo()
	{
		if($this->accountServices->hasLogined()){
			$user = $this->accountServices->getLoginedUserInfo();
			$user["admin"] = $this->accountServices->isAdmin();
			return Response::json($user, 200);
		}else{

			$user = $this->accountServices->getUserByRememberToken();
			if(!is_null($user)) {
				$user["admin"] = $this->accountServices->isAdmin();
				return Response::json($user, 200);
			}
			
			return Response::json(null , 403);
		}
	}

	public function getSignout()
	{
		$this->accountServices->logout();

        return redirect("/#/snippets");
	}

	public function getOauth2callback()
	{
		$result = $this->accountServices->login();
		if($result["success"]) {
			return redirect('/account/success');	
		}
        
        return redirect('/account/success?error='.$result["message"]);
	}

}