<?php namespace App\Http\Controllers;

use Request;
use Response;
use Config;
use App\Http\Controllers\Controller;
use App\Model\Snippet;
use Mobile_Detect;

class HomeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($a = null, $b = null, $c = null)
	{	
		$url = Config::get("app.url")."/";
		$title = Config::get("app.name");
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

		$detectEngine = new Mobile_Detect;
        if($detectEngine->isMobile()) {
        	return view("mobile.index",$data);
        }
		return view("index",$data);
	}

    // This method prepared just for debug & fun
    public function getPlayground()
    {
        $data = ['method'=>'get','data'=>Request::all()];

        return response()->json($data)
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function postPlayground()
    {
        
        $data = ['method' => 'post', 'data'=>Request::all()];

        return response()->json($data)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
