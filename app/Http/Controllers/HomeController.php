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

		if(is_numeric($a) && is_null($b)) {
			$snippet = Snippet::find($a);
			if(!is_null($snippet)) {
				$url .= "snippet/{$a}";
				$title = $snippet->title." | ".$title;
				$short_description = preg_replace("/[\n\*\#]+/","", $snippet->content);
				$short_description = preg_replace("/\s\s+/", " ", $short_description);
				$short_description = substr($short_description, 0, 200);

				$description = "{$short_description} ...";
			}
		}

		$data = [
			"url"          => $url,
			"title"        => $title,
			"description"  => $description,
			"angular_path" => '/_p/',
		];

		return view("index",$data);
	}
}
