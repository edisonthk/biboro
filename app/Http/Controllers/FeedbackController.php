<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class FeedbackController extends BaseController {

	private $account;

	public function __construct(
		\App\Edisonthk\AccountService $account
	) {
		$this->account = $account;
	}

	public function send(){
		if(\Request::has("message")){
			$feedback = \Request::get("message");

			$user = $this->account->getLoginedUserInfo();

			\Mail::send(['text'=>'emails.feedback'], ['user' => $user ,'feedback' => $feedback], function($message)
			{
			    $message->to('edisonthk@gmail.com')->from('admin@edisonthk.com','CodeGarage')->subject('Codegarageのフィードバック');
			});
		}
		return \Response::json("thank you");
	}


}
