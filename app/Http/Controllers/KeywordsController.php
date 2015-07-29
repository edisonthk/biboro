<?php namespace App\Http\Controllers;

use \App\Model\Keyword;
use \App\Model\Snippet;
use \Request;
use \Response;
use Illuminate\Routing\Controller as BaseController;

class KeywordsController extends BaseController {

    private $scoreService;
    private $snippetService;

    public function __construct(
        \App\Edisonthk\ScoreService $scoreService,
        \App\Edisonthk\SnippetService $snippetService
    ) {
        $this->scoreService = $scoreService;
        $this->snippetService = $snippetService;
    }
				
	public function index() {

        if(Request::has("kw")){

            $request_keyword = Request::get("kw");
            if(empty($request_keyword)) {
                return Response::json([]);
            }

            // $kw = Keyword::where("name","=",$request_keyword)->first();
            // if(is_null($kw)) {
            //     // $kw = new Keyword;
            //     // $kw->name = $request_keyword;
            //     // $kw->save();

            //     // $
            // }

            $result = [];
            foreach (Snippet::all() as $snippet) {
                $score = $this->scoreService->calcScore($snippet, $request_keyword);
                if($score > 0) {
                    $result[] = [
                        "score"   => $score,
                        "snippet" => $this->snippetService->beautifySnippetObject($snippet),
                    ];
                }
            }

            // sort for score
            usort($result, function($a, $b) {
                return $a["score"] < $b["score"];
            });

            return Response::json($result);
        }
	}
}