<?php namespace Biboro\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class NotificationController extends BaseController {

    public $notification;

    public function __construct(\Biboro\Edisonthk\NotificationService $notification) {
        $this->notification = $notification;
    }
				
	public function index() {
		
        $notification = $this->notification->get();

		return response()->json($notification);
	}	
    
    public function read(Request $request) {
        $ids = $request->get("noticeIds", []);
        $this->notification->markAsRead($ids);

        return response()->json();
    }
	
}