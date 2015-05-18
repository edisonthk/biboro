<?php namespace App\Http\Controllers;

use App\Tag;
use App\Snippet;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\DraftController;

class SnippetController extends BaseController {

	private $snippet_services;

	public function __construct(
		\App\Edisonthk\SnippetService $snippet_services
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

	public function search()
	{
		if(\Request::has("kw")){
			$kw = \Request::get("kw");

			$kw = str_replace("　", " ", $kw);
			$kws = explode(" ", $kw);

			foreach ($kws as $key => $value) {
				
			}
			$snippets = array();
			$tags = array();
			$temptags = array();

			$this->snippet_services->recordKeywords($kw);
			
			//キーワードの整形
			$kw=mb_convert_kana( $kw,"s");
			preg_match_all("|\[(.*?)\]|",$kw,$tagresult);
			$snippetresult=array_values(array_filter(preg_split("/,|\s/",implode(",",preg_split("/\[(.*)\]/",$kw)))));
	
			
			//タグ検索
			foreach($tagresult[1] as $t){
				foreach (Tag::where("name","=",$t)->get() as $tag) {
					array_push($tags, $tag); 
				}
			}
			foreach($tags as $tag){
				$temp_snippets=$tag->snippets()->getResults();
				array_push($temptags,$temp_snippets);
			}
			foreach($temptags as $item){
				foreach ($item as $snippet) {
					$temp = $snippet->toArray();
					$temp["tags"] = $snippet->tags()->getResults()->toArray();
					array_push($snippets, $temp); 
				}
			}
			//タイトル・コンテンツ検索
			foreach($snippetresult as $t){
				if($t != ''){
					foreach (Snippet::where("title","like","%".$t."%")->orWhere("content","like","%".$t."%")->get() as $snippet) {
						$temp = $snippet->toArray();
						$temp["tags"] = $snippet->tags()->getResults()->toArray();
						array_push($snippets, $temp); 
					}
				}else{
					continue;
				}
			}
			
			//重複の削除
			$tmp = array();
			$snippets_result = array();
			foreach( $snippets as $key => $value ){
				if( !in_array( $value['id'], $tmp ) ) {
					$tmp[] = $value['id'];
					$snippets_result[] = $value;
				}
			}
			//ソート
			// $updated_at=array();
			// foreach($snippets_result as $key=>$value){
			// 	$updated_at[$key]=$value["updated_at"];    
			// }
			usort($snippets_result, function($item1, $item2) {
				$ts1 = strtotime($item1["updated_at"]);
				$ts2 = strtotime($item2["updated_at"]);

				return $ts2 - $ts1;
			});

			$new_snippet_result = [];
			foreach ($snippets_result as $value) {
				$value["updated_at"] = $this->snippet_services->convertToUserViewTimestamp($value["updated_at"]);
				array_push($new_snippet_result, $value);
			}
			// array_multisort($updated_at,SORT_ASC,SORT_NATURAL,$snippets_result);
			return \Response::json($new_snippet_result);
		}

		\App::abort(404);
	}

}