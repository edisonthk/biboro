<?php namespace App\Edisonthk;

use Session;
use Cookie;
use App\Model\Account;

class AccountService {

    const USER_SESSION = "user";

	const _SERVICE = 'Google';

    const _REQUESTED_URI = "__rqu";
    const _REQUESTED_URI_EXPIRED = 3; // 3 minutes

    const _REMEMBER_TOKEN_LENGTH = 20;
    const _REMEMBER_TOKEN_KEY = "__tm1";
    const _REMEMBER_TOKEN_EXPIRED = 5760; // 60 minutes * 24hours * 4days = 5760 minutes

    public function isAdmin() {

        $admins = [
            'edisonthk@gmail.com',
            'likwee@iroya.jp',
        ];

        if($this->hasLogined()) {
            $user = $this->getLoginedUserInfo();
            foreach ($admins as $value) {
                if($value == $user["email"]) {
                    return true;
                }
            }
        }
        return false;
    }

    public function setRequestUri($uri) {
        Cookie::queue(self::_REQUESTED_URI, $uri, self::_REQUESTED_URI_EXPIRED);
    }

    public function getRequestedUri() {
        if(Cookie::has(self::_REQUESTED_URI)) {
            return Cookie::get(self::_REQUESTED_URI);    
        }
        return '/';
    }

	public function hasLogined() {
		return Session::has(self::USER_SESSION);
	}

	public function getLoginedUserInfo() {
		return Session::get(self::USER_SESSION);
	}

    public function getUserByRememberToken() {
        if(Cookie::has(self::_REMEMBER_TOKEN_KEY)) {
            $token = Cookie::get(self::_REMEMBER_TOKEN_KEY);

            $user = Account::where("remember_token","=",$token)->first();
            if(!is_null($user)) {
                return $user;
            }
        }

        return null;
    }

    public function accountExists($userId) {
        return Account::where("id","=",$userId)->count() > 0;
    }

    public function setRememberToken() {
        

        $user = $this->getLoginedUserInfo();

        if(is_null($user)) {
            throw new Exception("set remember token after user has logined");
        }

        

        $user = Account::find($user["id"]);

        if(is_null($user->remember_token)) {
            $token = substr(base64_encode(md5( mt_rand() )), 0, self::_REMEMBER_TOKEN_LENGTH);    
            $user->remember_token = $token;
            $user->save();
        }

        $token = $user->remember_token;

        Cookie::queue(self::_REMEMBER_TOKEN_KEY, $token, self::_REMEMBER_TOKEN_EXPIRED);
    }

	public function getOAuthorizationUri() {
		$googleService = \OAuth::consumer(self::_SERVICE,'http://'.$_SERVER['HTTP_HOST'].'/account/oauth2callback');
		$url = (String)$googleService->getAuthorizationUri(["response_type"=>"token"]);
		return $url;
	}

	public function login($account_id = null) {

        if(!is_null($account_id)) {

        }

		$googleService = \OAuth::consumer(self::_SERVICE);
        $code = null;
        if(\Request::has("code")) {
            $code = \Request::get("code");
            $googleService->requestAccessToken($code);
            // return redirect("/account/oauth2callback");
        }else if(\Request::has("error")) {
        	$error_message = \Request::get("error");

        	return [
        		"success" => false,
        		"message" => $error_message
        	];
        }

        $result = [];
        $account = null;

        if(is_null($account_id)) {
            $result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );
            $account = $this->getAccountByGoogleId($result["id"]);
        }else{
            $account = Account::find($account_id);
            $result = [
                "name" => $account->name,
                "email" => $account->email,
            ];
        }
        

        if(is_null($account)){
        	// 初めてログインする人はデータベースに保存されます。
        	$account = new Account;
        	$account->name 		= empty($result["name"]) ? $result["email"]: $result["name"] ;
        	$account->google_id = $result["id"];
        	$account->email 	= $result["email"];
        	$account->level	= false;
        }else{
        	// 初めてのではない人はデータベースのデータを更新
        	// Googleアカウントの名前がGoogleの設定で変更された可能性があるので、ログインする都度アカウント名を更新します。
        	$account->name 		= empty($result["name"]) ? $result["email"]: $result["name"] ;
        }

        if(!is_null($code)) {
            $account->authorization_code = $code;
        }

        $account->save();
        
        $result["id"] = $account->id;
        $result["name"] = $account->name;
        $result["email"] = $account->email;
        
        Session::put(self::USER_SESSION, $result);

        $this->setRememberToken();

        return [
        	"success" => true,
        	"message" => "success"
        ];
	}

	public function logout() {
        Cookie::queue(self::_REMEMBER_TOKEN_KEY, null, -1);
		Session::forget(self::USER_SESSION);
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