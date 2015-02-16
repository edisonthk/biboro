<?php namespace App\Http\Controllers;


use App\Account;
use Illuminate\Routing\Controller as BaseController;


class AccountController extends BaseController {

	const COOKIE_ALREADY_LOGIN_KEY = "already_login";
	const COOKIE_ALREADY_LOGIN_VALUE = "1";
	const COOKIE_ALREADY_LOGIN_TIME = 10080; // unit in minutes, 1440 * 7 = 10080 = one week

				//method+URI
	public function getSignin()
	{	
		
		$google = \OAuth::consumer('Google','http://'.$_SERVER['HTTP_HOST'].'/account/oauth2callback');
		$url = (String)$google->getAuthorizationUri();

		// var_dump($authUrl);
		return \Response::json(["auth_url" => $url]);
	}
	// public function postLogin()
	// {
		// $accounts = Account::all();

		// return View::make('/account/login',[$my_account = $accounts]);
	// }



	public function getSignout()
	{
		\GoogleOAuth::logout();
		\Session::forget("user");
		
        return redirect("/#/snippets");
	}

	public function getOauth2callback()
	{
		$googleService = \GoogleOAuth::consumer();

        if(\Request::has("code")){
            $code = \Request::get("code");
            $googleService->requestAccessToken($code);
            return redirect("/account/oauth2callback");
        }

        if(!\GoogleOAuth::hasAuthorized()){
        	// fail to authorized
            die("Not authorized yet");
        }


        $result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );
		$account = $this->getAccountByGoogleId($result["id"]);

        if(is_null($account)){
        	// 初めてログインする人はデータベースに保存されます。
        	$account = new Account;
        	$account->name 		= $result["name"];
        	$account->google_id = $result["id"];
        	$account->email 	= $result["email"];
        	$account->level	= false;
        	$account->save();

        }else{
        	// 初めてのではない人はデータベースのデータを更新
        	// Googleアカウントの名前がGoogleの設定で変更された可能性があるので、ログインする都度アカウント名を更新します。
        	$account->name 		= $result["name"];
        	$account->save();
        }
        
        $result["account_id"] = $account->id;
        \Cookie::queue(self::COOKIE_ALREADY_LOGIN_KEY,self::COOKIE_ALREADY_LOGIN_VALUE, self::COOKIE_ALREADY_LOGIN_TIME);
        \Session::put('user', $result);
        return redirect('/#/snippets');
	}

	// 権限がないページへ
	private function getAccountByGoogleId($googleAccountId)
	{
		$accounts = Account::all();
		foreach ($accounts as $acc) {
			
			if($acc->google_id == $googleAccountId){
				return $acc;
			}
		}
		return null;
	}

}