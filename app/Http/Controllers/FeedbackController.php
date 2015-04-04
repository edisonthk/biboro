<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class FeedbackController extends BaseController {
				
	public function send(){
		if(\Request::has("message")){
			$feedback = \Request::get("message");
			\Mail::send(['text'=>'emails.feedback'], ['feedback' => $feedback], function($message)
			{
			    $message->to('edisonthk@gmail.com')->from('admin@edisonthk.com','CodeGarage')->subject('Codegarageのフィードバック');
			});
		}
		return \Response::json("thank you");
	}	

	
}