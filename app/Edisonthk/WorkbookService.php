<?php namespace App\Edisonthk;

use App\Model\Snippet;
use App\Model\Workbook;
use App\Model\WorkbookPermission;


class WorkbookService {

    const PERMIT_MANAGE_USER = 1;
    const PERMIT_MODIFY = 2;

    private $account;
    private $snippet;

    public function __construct(AccountService $account, SnippetService $snippet)
    {
        $this->account = $account;
        $this->snippet = $snippet;
    }

    public function getByAccountId($accountId) 
    {
        return Workbook::where("account_id","=",$accountId)->get();
    }

    public function get($workbookId = null)
    {
        $user = $this->account->getLoginedUserInfo();
        
        if(is_null($workbookId)) {
            return Workbook::with("account")->where("account_id","=",$user->id)->get();    
        }

        return Workbook::with("account")->where("id","=",$workbookId)->first();
    }


    public function create($title, $description = "")
    {
        $user = $this->account->getLoginedUserInfo();
        
        $workbook = new Workbook;
        $workbook->account_id = $user->id;
        $workbook->title = $title;
        $workbook->description = $description;
        $workbook->save();

        return $workbook;
    }

    public function update(Workbook &$workbook, $title, $description = null)
    {
        $user = $this->account->getLoginedUserInfo();
        if(!$this->editable($workbook)) {
            throw new Exception\NotAllowedToEdit;
        }

        $workbook->title = $title;
        if(!is_null($description)) {
            $workbook->description = $description;
        }
        $workbook->save();
    }

    public function detachAllWorkbook(Snippet $snippet)
    {
        $user = $this->account->getLoginedUserInfo();

        $snippet->workbooks()->where("account_id","=",$user->id)->detach();
    }

    public function switchSnippet(Workbook $workbook, Snippet $snippet)
    {
        if(!$this->editable($workbook)) {
            throw new Exception\NotAllowedToEdit;
        }

        if(is_null($workbook)) {
            $snippet->workbooks()->sync([]);
        }else{
            $snippet->workbooks()->sync([$workbook->id]);
        }
    }

    public function appendSnippet(Workbook $workbook,Snippet $snippet)
    {
        if(!$this->editable($workbook)) {
            throw new Exception\NotAllowedToEdit;
        }

        if($workbook->snippets()->where("snippet_id","=",$snippet->id)->count() <= 0) {
            $workbook->snippets()->save($snippet);
        }
    }

    public function sliceSnippet(Workbook $workbook = null,Snippet $snippet)
    {
        if(is_null($workbook)) {

            $snippet->workbooks()->detach();
            return;
        }

        if(!$this->editable($workbook)) {
            throw new Exception\NotAllowedToEdit;
        }

        $workbook->snippets()->detach($snippet);
    }


    public function editable(Workbook $workbook)
    {
        $user = $this->account->getLoginedUserInfo();

        if($this->havePermission($workbook, self::PERMIT_MODIFY, $user->id)) {
            return true;
        }

        return false;
    }

    public function getPermission(Workbook $workbook, $accountId = null)
    {
        $model = WorkbookPermission::where("workbook_id","=",$workbook->id);

        if(!is_null($accountId)) {
            $model->where("account_id","=",$accountId);
        }

        return $model->get();
    }

    public function havePermission(Workbook $workbook, $type, $accountId)
    {
        if($workbook->account_id == $accountId) {
            return true;
        }

        $permissions = $this->getPermission($workbook, $accountId);
        foreach ($permissions as $permission) {
            if( $permission->permission_type == $type) {
                return true;
            }
        }
        return false;
    }

    public function grantPermission(Workbook $workbook, $permits, $accountId)
    {
        $user = $this->account->getLoginedUserInfo();   
        if(!$this->havePermission($workbook, self::PERMIT_MANAGE_USER, $user->id)) {
            throw new Exception\PermissionDenied;
        }

        WorkbookPermission::where("workbook_id","=",$workbook->id)->where("target_account_id","=",$accountId)->delete();

        foreach ($permits as $permission_type) {
            $workbookPermission = new WorkbookPermission;
            $workbookPermission->workbook_id         = $workbook->id;
            $workbookPermission->assigner_account_id = $user->id;
            $workbookPermission->permission_type     = $permission_type;
            $workbookPermission->target_account_id   = $accountId;
            $workbookPermission->save();
        }
    }

    public function workbookExists($workbookId) {
        return Workbook::where("id","=",$workbookId)->count() > 0;
    }

}
