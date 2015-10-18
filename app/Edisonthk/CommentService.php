<?php namespace App\Edisonthk;

use Auth;
use App\Model\Comment;

class CommentService {

	private $snippet;
    private $account;
    
    public function __construct(
        \App\Edisonthk\SnippetService $snippet,
        \App\Edisonthk\AccountService $account) 
    {
        $this->snippet = $snippet;
        $this->account = $account;
    }

    public function get($snippet,$id = null)
    {
        if(is_null($id)) {
            return Comment::with("account")->where("snippet_id","=",$snippet->id)->get();
        }
        return Comment::find($id);
    }

    public function getAndResponse($snippet,$id = null) {
        
        $comment = $this->get($snippet);

        if(is_a($comment, "Illuminate\Support\Collection")) {
            $filteredComment = [];
            foreach ($comment as $c) {
                $filteredComment[] = $this->makeResponse($c);
            }

            return $filteredComment;
        }

        return $this->makeResponse($comment);
    }

    public function validate($inputs)
    {
        $rules = [
            'comment'   => 'required',
        ];  

        return \Validator::make($inputs, $rules);
    }

    public function editable($commentModel)
    {
        $user = $this->account->getLoginedUserInfo();
        return $commentModel->account_id == $user->id;
    }

    public function makeResponse($comment)
    {
        $user = $this->account->getLoginedUserInfo();

        if(is_null($user)) {
            $comment->editable = false;
        }else{
            $comment->editable = $user->id == $comment->account_id;    
        }
        
        $comment->account = $user;

        return $comment;
    }

    public function createAndSave($snippet,$comment) 
    {
        $user = $this->account->getLoginedUserInfo();
        
        $model = new Comment;
        $model->snippet_id  = $snippet->id;
        $model->account_id  = $user->id;
        $model->comment     = $comment;
        $model->lang        = "";
        $model->save();

        return $model;
    }

    public function updateAndSave($commentModel, $comment)
    {
        $commentModel->comment = $comment;   
        $commentModel->lang        = "";
        $commentModel->save();

        return $commentModel;
    }

    public function delete($commentModel)
    {
        $commentModel->delete();
    }
}