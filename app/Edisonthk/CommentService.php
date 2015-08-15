<?php namespace App\Edisonthk;

use App\Model\Comment;

class CommentService {

	private $snippet;
    private $account;
    
    public function __construct(SnippetService $snippet,AccountService $account) 
    {
        $this->snippet = $snippet;
        $this->account = $account;
    }

    public function get($snippet,$id = null)
    {
        if(is_null($id)) {
            return Comment::where("snippet_id","=",$snippet->id)->get();
        }
        return Comment::find($id);
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
        return $commentModel->account_id == $user["id"];
    }

    public function createAndSave($snippet,$comment) 
    {
        $user = $this->account->getLoginedUserInfo();

        $model = new Comment;
        $model->snippet_id  = $snippet->id;
        $model->account_id  = $user["id"];
        $model->comment     = $comment;
        $model->lang        = "";
        $model->save();
    }

    public function updateAndSave($commentModel, $comment)
    {
        $commentModel->comment = $comment;   
        $commentModel->lang        = "";
        $commentModel->save();
    }

    public function delete($commentModel)
    {
        $commentModel->delete();
    }
}