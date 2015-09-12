<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Edisonthk\Exception\MissingLoginedUserInfo;
use App\Edisonthk\Exception\NotAllowedToEdit;
use App\Edisonthk\Exception\SnippetFoundInWorkbook;

class ProfileController extends Controller
{
    private $profile;

    public function __construct(\App\Edisonthk\ProfileService $profile ) {
        $this->profile = $profile;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //

        $profile = $this->profile->getMyAccount();
        return response()->json($profile);
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

        $this->workbook->create($title);

        return response()->json("created",200);
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
        $profile = $this->profile->getByAccountId($id);

        return response()->json($profile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $workbook = $this->workbook->get($id);
        if(is_null($workbook)) {
            return response()->json( $this->notFoundJsonMessage , 400);
        }

        $snippetId  = $request->get("snippetId");
        $action     = $request->get("action");
        $snippet    = $this->snippet->get($snippetId);
        if(is_null($snippet)) {
            return response()->json( $this->notFoundJsonMessage , 400);
        }

        try {
            if ($action == self::ACTION_PUSH) {
                $this->workbook->appendSnippet($workbook, $snippet);    
            } else if ($action == self::ACTION_SLICE) {
                $this->workbook->sliceSnippet($workbook, $snippet);
            }

        } catch(SnippetFoundInWorkbook $e) {
            return response()->json("already updated", 200);
        }
        
        return response()->json("updated", 400);
    }

    public function rename($id, Request $request)
    {
        $workbook = $this->workbook->get($id);
        if(is_null($workbook)) {
            return response()->json( $this->notFoundJsonMessage , 400);
        }
        
        $title = $request->get("title");

        try {
            $this->workbook->renameTitle($workbook, $title);    
        }catch ( NotAllowedToEdit $e) {
            return response()->json( $permissionDeniedJsonMessage , 403);
        }
        
        return response()->json( "updated" ,200);
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
}
