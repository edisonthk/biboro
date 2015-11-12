<?php namespace App\Edisonthk;

use Auth;
use OAuth;
use Session;
use Cookie;
use Config;
use Validator;
use App\Model\Account;
use Illuminate\Http\Request;

class AccountService {

	const _SERVICE = 'Google';

    const _REQUESTED_URI = "__rqu";
    const _REQUESTED_URI_EXPIRED = 3; // 3 minutes


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
        if(!is_null($uri)) {
            Cookie::queue(self::_REQUESTED_URI, $uri, self::_REQUESTED_URI_EXPIRED);
        }
    }

    public function getRequestedUri() {
        if(Cookie::has(self::_REQUESTED_URI)) {
            return Cookie::get(self::_REQUESTED_URI);    
        }
        return '/';
    }

    public function getByUrlPath($urlPath) {
        $account = Account::where("url_path","=",$urlPath)->first();
        if(is_null($account)) {
            throw new Exception\UserNotFound;    
        }
        
        return $account;
    }

    public function register($name, $email, $password)
    {
        $account = new Account;
        $account->password = bcrypt($password);
        $account->email    = $email;

        $input = [
            'name' => $name,
            'lang' => 'ja',
            'level' => 0,
        ];
        return $this->save($account, $input);
    }

    public function validateRegister($input)
    {
        return Validator::make($input, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:'.with(new Account)->getTable(),
            'password' => 'required|confirmed|min:6',
        ]);
    }

    public function validateLogin($input)
    {
        return Validator::make($input, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    }

	public function hasLogined() {
		return Auth::check();
	}

	public function getLoginedUserInfo() {
        $user = Auth::user();

        // to adapt to last version
        if(!is_null($user) && empty($user->url_path)) {
            $urlPath = $this->generateUniqueUrlPath($user->email);
            $user->url_path = $urlPath;
            $user->save();

        }
		return $user;
	}

    private function generateUniqueUrlPath($email) {
        $urlPath = substr($email, 0,strpos($email,"@"));

        $count = 0;
        $pattern = "/".$urlPath."(.\d+)?$/i";
        $duplicateUrlPathAccounts = Account::where("url_path","like",$urlPath."%")->get();
        foreach ($duplicateUrlPathAccounts as $duplicateUrlPathAccount) {
            if(preg_match($pattern, $duplicateUrlPathAccount->url_path )) {
                $count += 1;
            }
        }

        if($count > 0) {
            $urlPath .= ".".$count;
        }

        return $urlPath;
    }

	public function getOAuthorizationUri() {
		$googleService = OAuth::consumer(self::_SERVICE,'http://'.$_SERVER['HTTP_HOST'].'/account/oauth2callback');
		$url = (String)$googleService->getAuthorizationUri(["response_type"=>"token"]);
		return $url;
	}

    private function copyFileToLocal($fromRemote, $toLocalFolder) {
        $file = file_get_contents($fromRemote);

        if (!is_dir($toLocalFolder)) {
            // dir doesn't exist, make it
            mkdir($toLocalFolder);
        }

        $filename = basename($fromRemote);
        $newFilename = $this->generateFilenameWithoutDuplicateInStoreAtFolder($filename, $toLocalFolder);

        file_put_contents($toLocalFolder."/".$newFilename , $file);

        return $newFilename;
    }

    private function generateFilenameWithoutDuplicateInStoreAtFolder($filename, $storeAtFolder, $offset = 1) {
        if(file_exists($storeAtFolder ."/". $filename)) {
            $fileinfo = pathinfo($filename);
            return $this->generateFilenameWithoutDuplicateInStoreAtFolder($fileinfo["filename"]."-".$offset.".".$fileinfo["extension"], $storeAtFolder, $offset + 1);
        }
        
        return $filename;
    }

	public function handleOauth2callback(Request $request) {

		$googleService = OAuth::consumer(self::_SERVICE);
        $code = null;
        if($request->has("code")) {
            $code = $request->get("code");
            $googleService->requestAccessToken($code);
            
            $result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );

            $path = "/uploads/profiles";
            $picture = $this->copyFileToLocal($result["picture"], public_path().$path);
            $result["picture"] = Config::get("app.url").$path."/".$picture;
            return $result;

        }else if($request->has("error")) {
        	$error_message = $request->get("error");

        	if($error_message === 'access_denied') {
                throw new Exception\OAuthAccessDenied();
            }else {
                throw new Exception\UnknownOAuthError($error_message);
            }
        }

        return null;


        // $result = [];
        // $account = null;

        // if(is_null($account_id)) {
            
        //     $account = $this->getAccountByGoogleId($result["id"]);
        // }else{
        //     $account = Account::find($account_id);
        //     $result = [
        //         "name" => $account->name,
        //         "email" => $account->email,
        //     ];
        // }
        

        // if(is_null($account)){
        // 	// 初めてログインする人はデータベースに保存されます。
        // 	$account = new Account;
        // 	$account->name 		= empty($result["name"]) ? $result["email"]: $result["name"] ;
        // 	$account->google_id = $result["id"];
        // 	$account->email 	= $result["email"];
        // 	$account->level	= false;
        // }else{
        // 	// 初めてのではない人はデータベースのデータを更新
        // 	// Googleアカウントの名前がGoogleの設定で変更された可能性があるので、ログインする都度アカウント名を更新します。
        // 	$account->name 		= empty($result["name"]) ? $result["email"]: $result["name"] ;
        // }

        // if(!is_null($code)) {
        //     $account->authorization_code = $code;
        // }

        // $account->save();
        
        // $result["id"] = $account->id;
        // $result["name"] = $account->name;
        // $result["email"] = $account->email;
        
        // Session::put(self::USER_SESSION, $result);

        // $this->setRememberToken();

        // return [
        // 	"success" => true,
        // 	"message" => "success"
        // ];
	}

    public function generate($input)
    {
        $account = new Account;
        $account->email     = $input["email"];
        $account->google_id = empty($input["google_id"]) ? null : $input["google_id"];

        return $this->save($account, $input);
    }

    public function save($account ,$input)
    {   
        $account->name          = $input["name"];
        $account->gender        = array_get($input,"gender",is_null($account->gender) ? "" : $account->gender);
        $account->profile_image = array_get($input,"profile_image",is_null($account->profile_image) ? "" : $account->profile_image);
        $account->locale        = array_get($input,"locate",is_null($account->locale) ? "" : $account->locale);
        $account->lang          = array_get($input,"lang", "ja");
        $account->level = 0;
        $account->save();

        return $account;
    }


	public function logout() {
        Auth::logout();
	}

    public function getAccountByEmail($email)
    {
        return Account::where("email","=",$email)->first();
    }


	// 権限がないページへ
	private function getAccountByGoogleId($googleAccountId)
	{
		return Account::where("google_id","=",$googleAccountId)->first();
	}

}