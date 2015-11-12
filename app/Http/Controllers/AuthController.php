<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Config;
use Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    const TYPE_REGISTER = "register";
    const TYPE_USER_CREATED = "created";
    const TYPE_OAUTH_DENIED_ACCESS = "denied";
    const TYPE_UNKNOWN_OAUTH_ERROR = "oauth";

    private $account;
    private $workbook;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(
        \App\Edisonthk\AccountService $account,
        \App\Edisonthk\WorkbookService $workbook
    )
    {
        $this->middleware('guest', ['except' => 'getLogout']);

        $this->account = $account;
        $this->workbook = $workbook;
        
    }

    public function getLogin(Request $request) 
    {
        $oauthUrl = $this->account->getOAuthorizationUri();

        // if success logined, redirect user to origin
        $origin = $request->input("origin");
        $this->account->setRequestUri($origin);

        return view("auth.login", ["oauthUrl" => $oauthUrl]);
    }

    public function postLogin(Request $request)
    {
        $email = $request->get("email");
        $password = $request->get("password");

        $validator = $this->account->validateLogin($request->all());
        if($validator->fails()) {
            return Response::json($validator->messages(), 403);
        }

        if (Auth::attempt(['email' => $email, 'password' => $password], true)) {
            // login success
            
            $requestedUrl = $this->account->getRequestedUri();

            return Response::json("Redirect to: ".Config::get("app.app_url")."/#".$requestedUrl);
        }

        return Response::json(["メールアドレスもしくはパスワードを間違った"],403);
    }

    public function getRegister()
    {
        return view("auth.register");
    }

    public function getLogout()
    {
        $this->account->logout();
        return redirect(action("AuthController@getLogin"));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function postRegister(Request $request)
    {
        $validator = $this->account->validateRegister($request->all());
        if($validator->fails()) {
            return Response::json($validator->messages(), 403);
        }

        $name = $request->get("name");
        $email = $request->get("email");
        $password = $request->get("password");

        $user = $this->account->register($name, $email, $password);

        $this->workbook->create($user->name, "", $user->id);

        Auth::attempt(["email" => $email, "password" => $password], true);

        return Response::json("Redirect to: ".Config::get("app.app_url"));
    }

    public function oauth2callback(Request $request)
    {
        $validator = $this->account->handleOauth2callback($request);
        if(!$validator["success"]) {
            
        }

        $user = $this->account->getAccountByEmail($email);
    }

    public function getErrorAuth(Request $request) 
    {
        $type = $request->get("type","");
        $email = $request->get("email","");

        return view("auth.errors", ["type" => $type, "email" => $email]);
    }

}
