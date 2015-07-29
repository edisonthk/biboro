<?php namespace App\Http\Controllers;

use Response;
use App\Model\Account;
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

        $user = $this->accountServices->getUserByRememberToken();
	
		return view("login_wrapper",[
            "action" => "login",
            "auth_url" => $url, 
            "user" => $user,
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
		$result = $this->accountServices->login(23);
		if($result["success"]) {
			return response()->json("success to login", 200);
		}
        
        return response()->json("fail to login", 403);
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