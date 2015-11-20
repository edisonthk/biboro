<?php

namespace Biboro\Http\Controllers;

use Biboro\Model\Comment;
use Biboro\Model\Snippet;

use Illuminate\Http\Request;

use Biboro\Http\Controllers\Controller;

use Biboro\Edisonthk\Exception\SnippetNotFound;

class CommentController extends Controller
{

    private $snippet;
    private $comment;
    private $notice;
    
    public function __construct(
        \Biboro\Edisonthk\SnippetService $snippet, 
        \Biboro\Edisonthk\CommentService $comment, 
        \Biboro\Edisonthk\NotificationService $notice
    ) 
    {
        $this->middleware('auth', ['only' => ['store', 'update','destroy']]);

        $this->comment = $comment;
        $this->snippet = $snippet;
        $this->notice = $notice;
    }

    public function index($snippetId)
    {
        $snippet = $this->snippet->get($snippetId);
        if(is_null($snippet)) {
            return response()->json("snippet not found", 403);
        }

        return $this->comment->getAndResponse($snippet);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store($snippetId, Request $request)
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
        $modelComment = $this->comment->createAndSave($snippet, $comment);


        $this->notice->noticeComment($snippet->account_id, $modelComment);    
        

        return response()->json($this->comment->makeResponse($modelComment),200);
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
        $commentModel = $this->comment->updateAndSave($commentModel, $comment);

        return response()->json($this->comment->makeResponse($commentModel), 200);
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
