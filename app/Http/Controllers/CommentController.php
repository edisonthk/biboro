<?php

namespace App\Http\Controllers;

use App\Model\Comment;
use App\Model\Snippet;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Edisonthk\SnippetService;
use App\Edisonthk\CommentService;
use App\Edisonthk\Exception\SnippetNotFound;

class CommentController extends Controller
{

    private $snippet;
    private $comment;
    
    public function __construct(SnippetService $snippet, CommentService $comment) 
    {
        $this->comment = $comment;
        $this->snippet = $snippet;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store($snippetId,Request $request)
    {
        //
        $snippet = $this->snippet->get($snippetId);
        if(is_null($snippet)) {
            return response()->json("snippet not found", 403);
        }

        $validator = $this->comment->validate($request->all());
        if($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $comment = $request->input("comment");
        $this->comment->createAndSave($snippet, $comment);

        return response()->json("commented",200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($snippetId, Request $request, $id)
    {
        $snippet = $this->snippet->get($snippetId);
        if(is_null($snippet)) {
            return response()->json("snippet not found", 403);
        }

        //
        $commentModel = $this->comment->get($snippet, $id);
        if(is_null($commentModel)) {
            return response()->json("comment not found",403);
        }

        if(!$this->comment->editable($commentModel)) {
            return response()->json("no permission to modify comment.",403);
        }

        $validator = $this->comment->validate($request->all());
        if($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $comment = $request->input("comment");
        $this->comment->updateAndSave($commentModel, $comment);

        return response()->json("comment is updated", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($snippetId, $id)
    {
        //
        $snippet = $this->snippet->get($snippetId);
        if(is_null($snippet)) {
            return response()->json("snippet not found", 403);
        }

        //
        $commentModel = $this->comment->get($snippet, $id);
        if(is_null($commentModel)) {
            return response()->json("comment not found",403);
        }

        if(!$this->comment->editable($commentModel)) {
            return response()->json("no permission to modify comment.",403);
        }

        $this->comment->delete($commentModel);

        return response()->json("deleted",200);
    }
}
