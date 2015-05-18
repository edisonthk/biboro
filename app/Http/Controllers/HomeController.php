<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Snippet;

class HomeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($a = null, $b = null, $c = null)
	{	
		$url = "http://codegarage.edisonthk.com/";
		$title = "CodeGarage";
		$description = "キーボードだけで検索、選択ができるスニペットの共有サイト";

		if($a === 'snippet' && is_numeric($b)) {
			$snippet = Snippet::find($b);
			if(!is_null($snippet)) {
				$url .= "snippet/{$b}";
				$title = $snippet->title." | ".$title;
				$short_description = preg_replace("/[\n\*\#]+/","", $snippet->content);
				$short_description = preg_replace("/\s\s+/", " ", $short_description);
				$short_description = substr($short_description, 0, 25);
				$description = "{$short_description} ...";
			}
		}

		$data = [
			"url"          => $url,
			"title"        => $title,
			"description"  => $description
		];

		return view("index",$data);
	}
}
