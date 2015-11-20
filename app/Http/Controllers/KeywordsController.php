<?php namespace Biboro\Http\Controllers;

use \Biboro\Model\Keyword;
use \Biboro\Model\Snippet;
use \Request;
use \Response;
use Illuminate\Routing\Controller as BaseController;

class KeywordsController extends BaseController {

    private $scoreService;
    private $snippetService;
    private $newsService;

    public function __construct(
        \Biboro\Edisonthk\ScoreService $scoreService,
        \Biboro\Edisonthk\SnippetService $snippetService,
        \Biboro\Edisonthk\NewsService $newsService
    ) {
        $this->scoreService = $scoreService;
        $this->snippetService = $snippetService;
        $this->newsService = $newsService;
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
            foreach (Snippet::with("workbooks")->get() as $snippet) {
                $score = $this->scoreService->calcScore($snippet, $request_keyword);
                if($score > 0) {
                    $snippet->score = $score;
                    $result[] = $snippet;
                }
            }

            // sort for score
            usort($result, function($a, $b) {
                return $a->score < $b->score;
            });

            $this->newsService->tidy($result);

            return Response::json($result);
        }
	}
}