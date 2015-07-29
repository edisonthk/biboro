<?php namespace App\Http\Controllers;

use \Response;
use App\Model\Tag;
use App\Model\Snippet;
use App\Http\Controllers\DraftController;
use App\Edisonthk\SnippetService;

use Illuminate\Routing\Controller as BaseController;

class SnippetController extends BaseController {

	private $snippet_services;

	public function __construct(
        SnippetService $snippet_services
	) {
        $this->middleware('auth.login', ['except' => ['index', 'show', 'search']]);

        $this->snippet_services = $snippet_services;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){

		$snippets = array();
		foreach (Snippet::orderBy('updated_at','desc')->get(['id','title','updated_at']) as $snippet) {		//条件付けのときは->get()が必要

			array_push($snippets, $this->snippet_services->beautifySnippetObject($snippet)); 

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
			$snippet = $this->snippet_services->beautifySnippetObject($snippet);	

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
	public function store()
	{
		// process the login
		$inputs = \Request::all();
		$validator = $this->snippet_services->validate($inputs);

		if ($validator->fails()) {
			return \Response::json(["error"=>$validator->messages()],400);
		} else {
			// store
			$snippet = new Snippet;
			$snippet->title       	= \Input::get('title');
			$snippet->content     	= \Input::get('content');
			$snippet->timestamps 	= true;
			$snippet->lang 			= "jp";
			$snippet->account_id 	= \Session::get("user")["id"];
			$snippet->save();

			$snippet->tagsave($inputs["tags"]);

			// destroy draft as real data is stored to database
			DraftController::destroy();

		
			$result = $this->snippet_services->beautifySnippetObject($snippet);
			return \Response::json($result);
		}
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

		$result = $this->snippet_services->beautifySnippetObject($snippet);
		return \Response::json($result);
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
			$snippet = $this->snippet_services->beautifySnippetObject($snippet);	

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
	public function update($id)
	{
		//
		// validate
		// read more on validation at http://laravel.com/docs/validation
		/*
		$rules = array(
			'title'       => 'required',
			'content'      => 'required',
			'tags_id'      => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);
*/
		$validator = $this->snippet_services->validate(\Request::all());
		// process the login
		if ($validator->fails()) {

			return \Response::json(["error"=>$validator->messages()], 400);
		/*
			return Redirect::to('snippet/create')
				->withErrors($validator)
				->withInput(Input::except('password')); */
				
		} else {
			// store
			$snippet = Snippet::find($id);
			$snippet->title       	= \Request::get('title');
			$snippet->content    	= \Request::get('content');
			$snippet->timestamps 	= true;
			$snippet->lang 			= "jp";
			$snippet->save();

			$snippet->tagsave(\Request::get('tags'));

			// destroy draft as real data is stored to database
			DraftController::destroy($id);

			// redirect
			\Session::flash('message', 'Successfully created snippet!');

			$result = $this->snippet_services->beautifySnippetObject($snippet);
			return \Response::json($result);
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