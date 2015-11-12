<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Edisonthk\SnippetService;
use App\Edisonthk\AccountService;
use App\Edisonthk\WorkbookService;
use App\Edisonthk\SnippetReferenceService;

class ExtensionController extends Controller
{
    private $snippet;
    private $account;
    private $workbook;
    private $snippetReference;

    public function __construct(SnippetService $snippet, AccountService $account, WorkbookService $workbook ,SnippetReferenceService $snippetReference) {
        $this->snippet = $snippet;
        $this->account = $account;
        $this->workbook = $workbook;
        $this->snippetReference = $snippetReference;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        //
        $snippet    = $request->get("snippet");
        $ref        = $request->get("ref");

        $workbooks  = $this->workbook->get();

        return view("extension.create", [
                "user"       => $this->account->getLoginedUserInfo(),
                "snippet"    => $snippet,
                "ref"        => $ref,
                "workbooks"  => $workbooks,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $validator = $this->snippet->validate($inputs);

        if ($validator->fails()) {
            return Response::json(["error"=>$validator->messages()],400);
        }

        $title      = $request->get("title");
        $content    = $request->get("content");
        $ref        = $request->get("ref");    
        $tags       = $request->get("tags",[]);
        $workbookId = $request->get("workbook","");

        $snippet = $this->snippet->createAndSave($title, $content, $tags);

        $this->snippetReference->referenceFromWebsite($snippet,$ref);

        $workbook = $this->workbook->get($workbookId);
        if(!is_null($workbook)) {
            $this->workbook->appendSnippet($workbook, $snippet);
        }

        $this->snippet->beautifySnippetObject($snippet);

        return Response::json($snippet);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
    }
}
