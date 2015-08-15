<?php namespace App\Edisonthk;

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
            return Workbook::where("account_id","=",$user["id"])->get();    
        }

        return Workbook::find($workbookId);
    }


    public function create($title)
    {
        $user = $this->account->getLoginedUserInfo();
        
        $workbook = new Workbook;
        $workbook->account_id = $user["id"];
        $workbook->title = $title;
        $workbook->save();
    }

    public function renameTitle($workbook, $title)
    {
        $user = $this->account->getLoginedUserInfo();
        if(!$this->editable($workbook)) {
            throw new Exception\NotAllowedToEdit;
        }

        $workbook->title = $title;
        $workbook->save();
    }

    public function appendSnippet($workbook, $snippet)
    {
        if(!$this->editable($workbook)) {
            throw new Exception\NotAllowedToEdit;
        }

        if($workbook->snippets()->where("snippet_id","=",$snippet->id)->count() <= 0) {
            $workbook->snippets()->save($snippet);
        }else {
            throw new Exception\SnippetFoundInWorkbook;
        }
    }

    public function sliceSnippet($workbook, $snippet)
    {
        if(!$this->editable($workbook)) {
            throw new Exception\NotAllowedToEdit;
        }

        $workbook->snippets()->detach($snippet);
    }


    public function editable($workbook)
    {
        $user = $this->account->getLoginedUserInfo();

        if($this->havePermission($workbook, self::PERMIT_MODIFY, $user["id"])) {
            return true;
        }

        return false;
    }

    public function getPermission($workbook, $accountId = null)
    {
        $model = WorkbookPermission::where("workbook_id","=",$workbook->id);

        if(!is_null($accountId)) {
            $model->where("account_id","=",$accountId);
        }

        return $model->get();
    }

    public function havePermission($workbook, $type, $accountId)
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

    public function grantPermission($workbook, $permits, $accountId)
    {
        $user = $this->account->getLoginedUserInfo();   
        if(!$this->havePermission($workbook, self::PERMIT_MANAGE_USER, $user["id"])) {
            throw new Exception\PermissionDenied;
        }

        WorkbookPermission::where("workbook_id","=",$workbook->id)->where("target_account_id","=",$accountId)->delete();

        foreach ($permits as $permission_type) {
            $workbookPermission = new WorkbookPermission;
            $workbookPermission->workbook_id         = $workbook->id;
            $workbookPermission->assigner_account_id = $user["id"];
            $workbookPermission->permission_type     = $permission_type;
            $workbookPermission->target_account_id   = $accountId;
            $workbookPermission->save();
        }
    }

    public function workbookExists($workbookId) {
        return Workbook::where("id","=",$workbookId)->count() > 0;
    }

}
