<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


use App\Draft;

class DraftController extends Controller {

	public static function read($snippet_id = null)
	{
		if(is_null($snippet_id)) {

			$draft = Draft::where("account_id","=",\Session::get("user")["id"])
				->whereNull("snippet_id")
				->first();
			return $draft;
		}else{

			$draft = Draft::where("account_id","=",\Session::get("user")["id"])
				->where("snippet_id","=",$snippet_id)
				->first();

			return $draft;
		}
	}

	public static function save($data ,$snippet_id = null)
	{
		$draft = Draft::where("snippet_id","=",$snippet_id)->first();
		if(is_null($draft)) {
			$draft = new Draft;
			$draft->account_id 	= \Session::get("user")["id"];
			$draft->snippet_id = $snippet_id;
			$draft->lang = 'jp';
		}

		$draft->title = $data["title"];
		$draft->content = $data["content"];
		
		$draft->save();

		$draft->tagsave($data["tags"]);
	}

	public static function destroy($snippet_id)
	{
		
		$draft = Draft::where("snippet_id","=",$snippet_id)->first();

		if(!self::isEditable($draft)){
			return false;
		}

		$draft->delete();
		return true;
	}

	/** 
	 * Checking if user is available, allowable, or authorized to edit current draft
	 * If user have the permission to modify, return TRUE
	 * if user don't have permission, return FALSE
	 */
	private static function isEditable($draft) {
		if(is_null($draft)){
			return false;
		}else{
			if($draft->account_id != \Session::get("user")["id"]) {
				return false;
			}
		}

		return true;
	}


}
