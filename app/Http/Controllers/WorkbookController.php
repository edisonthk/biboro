<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Edisonthk\Exception\MissingLoginedUserInfo;
use App\Edisonthk\Exception\NotAllowedToEdit;
use App\Edisonthk\Exception\SnippetFoundInWorkbook;
use App\Edisonthk\Exception\UserNotFound;

class WorkbookController extends Controller
{
    const ACTION_PUSH  = "push";
    const ACTION_SLICE = "slice";

    private $workbook;
    private $snippet;
    private $pagination;
    private $account;
    private $score;

    private $updatedMessage;
    private $notFoundJsonMessage;
    private $permissionDeniedJsonMessage;
    private $noPermission;

    public function __construct(
            \App\Edisonthk\AccountService $account,
            \App\Edisonthk\WorkbookService $workbook,
            \App\Edisonthk\SnippetService $snippet,
            \App\Edisonthk\PaginationService $pagination,
            \App\Edisonthk\ScoreService $score
        ) {

        $this->middleware('auth', ['only' => ['index','store', 'update','destroy']]);

        $this->account = $account;
        $this->workbook = $workbook;
        $this->snippet = $snippet;
        $this->pagination = $pagination;
        $this->score = $score;

        $this->notFoundJsonMessage = ["error" => "not found"];
        $this->permissionDeniedJsonMessage = ["error" => "permission denied"];

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        try{
            $workbooks = $this->workbook->get();
        }catch(NotAllowedToEdit $e) {
            return response()->json(["error"=>"no permission to edit"], 403);
        }
        

        return response()->json($workbooks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $title = $request->get("title");

        $workbook = $this->workbook->create($title);

        return response()->json($workbook,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $workbook = null;

        $pattern = "/^\d+$/";
        if(preg_match($pattern,$id)) {
            // get workbook

            $workbook = $this->workbook->get($id);
            $snippetQuery = $workbook->snippets()->orderBy("workbook_snippet.updated_at","desc");

            // get workbook permission
            $workbook->permissions = $this->workbook->getPermission($workbook);

        }else {
            // get user workbook and $id belongs to $urlPath in this case
            try {
                $urlPath = $id;
                $account = $this->account->getByUrlPath($urlPath);
                $workbook = [
                    "title" => $account->name,
                    "account" => $account,
                ];

                $snippetQuery = $this->snippet->getQueryByUrlPath($urlPath);
                $snippetQuery->orderBy("updated_at","desc");

            }catch(UserNotFound $e) {
                return response()->json("workbook not found",400);
            }
        }
        

        // make pagination
        $pagination = $this->pagination->makeWithQuery($snippetQuery,$request);

        // paginate snippets
        $snippets = $pagination["data"];

        // tidy snippets
        $this->snippet->multipleEagerLoadWithTidy($snippets);

        // remove unnecessary response
        unset($pagination["data"]);

        return response()->json([
            "workbook" => $workbook,
            "snippets" => $snippets,
            "pagination" => $pagination
        ]);
    }

    public function update($id, Request $request)
    {
        $workbook = $this->workbook->get($id);
        if(is_null($workbook)) {
            return response()->json( $this->notFoundJsonMessage , 400);
        }
        
        $title = $request->get("title");
        $description = $request->get("description","");

        
        try {
            $this->workbook->update($workbook, $title, $description);    
        }catch ( NotAllowedToEdit $e) {
            return response()->json( $permissionDeniedJsonMessage , 403);
        }
        
        return response()->json( $workbook ,200);
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
        $workbook = $this->workbook->get($id);
        if(is_null($workbook)) {
            return response()->json( $this->notFoundJsonMessage , 400);
        }

        $workbook->delete();

        return response()->json("workbook deleted",200);
    }


    public function showPermission($workbookId)
    {
        $workbook = $this->workbook->get($workbookId);
        if(is_null($workbook)) {
            return response()->json( $this->notFoundJsonMessage , 400);
        }

        $permissions = $this->workbook->getPermission($workbookId);
        return response()->json($permissions);
    }

    public function grantPermission($workbookId, Request $request)
    {   
        $workbook = $this->workbook->get($workbookId);
        if(is_null($workbook)) {
            return response()->json( $this->notFoundJsonMessage , 400);
        }


        $targetAccountId = $request->input("target");
        $permits = $request->input("permits");

        try {
            $this->workbook->grantPermission($workbook, $permits, $targetAccountId);
        } catch(PermissionDenied $e) {
            return response()->json( $this->permissionDeniedJsonMessage , 403);
        }

        return response()->json("updated",200);
    }

    public function search($workbookId, Request $request) 
    {
        $query = $request->get("q","");
        if(empty($query)) {
            return response()->json("Query cannot be empty.", 400);
        }

        $pattern = "/^\d+$/";
        if(preg_match($pattern,$workbookId)) {
        
            $workbook = $this->workbook->get($workbookId);
            if(is_null($workbook)) {
                return response()->json( $this->notFoundJsonMessage , 400);
            }

            $snippets = $this->workbook->search($workbook ,$query);

        }else {
            $urlPath = $workbookId;
            $account = $this->account->getByUrlPath($urlPath);
            $workbook = [
                "title" => $account->name,
                "account" => $account,
            ];   

            $snippets = $this->snippet->getByUrlPath($urlPath);
            $snippets = $this->workbook->search($snippets, $query);
        }

        

        return response()->json([
            "workbook" => $workbook,
            "snippets" => $snippets
        ]);
    }
}
