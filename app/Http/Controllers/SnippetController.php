<?php namespace App\Http\Controllers;


use \Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Model\Tag;
use App\Model\Snippet;
use App\Http\Controllers\DraftController;


class SnippetController extends BaseController {

	private $snippet;
    private $follow;
    private $comment;
    private $workbook;
    private $reference;

	public function __construct(
        \App\Edisonthk\SnippetService $snippet, 
        \App\Edisonthk\FollowService $follow, 
        \App\Edisonthk\CommentService $comment,
        \App\Edisonthk\WorkbookService $workbook,
        \App\Edisonthk\SnippetReferenceService $reference
        ) 
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'search']]);

        $this->snippet = $snippet;
        $this->follow  = $follow;
        $this->comment = $comment;
        $this->workbook = $workbook;
        $this->reference = $reference;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){

        $query = function($q) {
            return $q->orderBy("updated_at","desc");
        };


        $snippets = [];
		foreach ($this->follow->getFollowingSnippets(null, $query) as $snippet) {		//条件付けのときは->get()が必要
			$this->snippet->beautifySnippetObject($snippet);
            $snippets[] = $snippet;
		}

        foreach ($this->snippet->get() as $snippet) {       //条件付けのときは->get()が必要
            $this->snippet->beautifySnippetObject($snippet);
            $snippets[] = $snippet;
        }


        // remove duplicate
        $filteredSnippets = [];
        foreach ($snippets as $snippet) {
            $found = false;
            foreach ($filteredSnippets as $filteredSnippet) {
                if($filteredSnippet["id"] == $snippet["id"]) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                $filteredSnippets[] = $snippet;
            }
        }
		
		return \Response::json($snippets);
	}	

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$snippet = DraftController::read();

		if(!is_null($snippet)) {
			$this->snippet->beautifySnippetObject($snippet);	

			if(array_key_exists("snippet_id", $snippet)) {
				$snippet["id"] = $snippet["snippet_id"];
			}
		}
		
		return \Response::json(["snippet" => $snippet]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// process the login
		$inputs = $request->all();
		$validator = $this->snippet->validate($inputs);

		if ($validator->fails()) {
			return \Response::json(["error"=>$validator->messages()],400);
		} else {
			// store
            $title    = $request->get('title');
            $content  = $request->get('content');
            $tags     = $request->get("tags", []);

			$snippet = $this->snippet->createAndSave($title, $content, $tags);

            $workbook = $this->workbook->get($request->get("workbookId"));
            if(!is_null($workbook)) {
                $this->workbook->appendSnippet($workbook, $snippet);    
            }
            
            

			// destroy draft as real data is stored to database
			DraftController::destroy();

		
			$this->snippet->beautifySnippetObject($snippet);
			return \Response::json($snippet);
		}
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function fork(Request $request)
    {
        $inputs = $request->all();
        $validator = $this->snippet->forkValidate($inputs);

        if ($validator->fails()) {
            return \Response::json(["error"=>$validator->messages()],400);
        }

        $workbookId = $request->get("workbookId");
        $workbook = $this->workbook->get($workbookId);
        if(is_null($workbook)) {
            if($workbookId === 0) {
                return response()->json(["error" => ["workbook" => trans("messages.workbook_not_selected")]] , 400);
            }else {
                return response()->json(["error" => ["workbook" => trans("messages.workbook_not_found")]] , 400);
            }
            
        }

        $refSnippetId  = $request->get("refSnippetId");
        $refSnippet = $this->snippet->get($refSnippetId);
        if(is_null($refSnippet)) {
            return response()->json(["error" => ["workbook" => trans("messages.snippet_not_found")]] , 400);
        }        

        $title      = $request->get("title");
        $content    = $request->get("content");
        $tags       = $request->get("tags",[]);

        $snippet = $this->snippet->createAndSave($title, $content, $tags);
        $this->workbook->appendSnippet($workbook, $snippet);
        $this->reference->referenceFromBiboro($snippet, $refSnippet);

        $forkedSnippet = $this->snippet->with()->where("id","=",$snippet->id)->first();
        
        return response()->json($forkedSnippet, 200);
    }


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$snippet= Snippet::find($id);
		if(is_null($snippet)) {
			return Response::json("", 404);
		}

        $snippet->load("comments");

		$this->snippet->beautifySnippetObject($snippet);
		return \Response::json($snippet);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		// get the shop
		$snippet = DraftController::read($id);
		if(is_null($snippet)) {
			$snippet = Snippet::find($id);
		}

		if(!is_null($snippet)) {
			$this->snippet->beautifySnippetObject($snippet);	

			if(array_key_exists('snippet_id', $snippet)){
				$snippet["id"] = $snippet["snippet_id"];
			}
		}

		// show the edit form and pass the shop
		return \Response::json(["snippet" => $snippet]);
	}

	public function saveDraft($id = null)
	{

		$data = [
			"title" => \Request::input('title', ''),
			"content" => \Request::input('content', ''),
			"tags" => \Request::input('tags', []),
		];

		DraftController::save($data, $id);

		return \Response::json(["test"=>\Request::input('tags', [])]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		
		$validator = $this->snippet->validate($request->all());
		if ($validator->fails()) {

			return \Response::json(["error"=>$validator->messages()], 400);
		} else {
			// store
			$snippet = Snippet::find($id);

            $workbook = $this->workbook->get($request->get("workbookId"));
            if(is_null($workbook)) {
                $this->workbook->sliceSnippet(null, $snippet);
            }else{
                $this->workbook->switchSnippet($workbook, $snippet);
            }

            

			$snippet->title       	= $request->get('title');
			$snippet->content    	= $request->get('content');
			$snippet->timestamps 	= true;
			$snippet->lang 			= "jp";
			$snippet->save();

			$snippet->tagsave($request->get('tags'));

			// destroy draft as real data is stored to database
			DraftController::destroy($id);

			// redirect
			\Session::flash('message', 'Successfully created snippet!');

			$this->snippet->beautifySnippetObject($snippet);
			return \Response::json($snippet);
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
		$snippet = Snippet::find($id);
		$snippet->delete();

		DraftController::destroy($id);

		\Session::flash('message', 'Successfully deleted the nerd!');
		return \Response::json('snippet');
	}
    
}