<?php namespace Biboro\Edisonthk;

use Biboro\Model\Snippet;
use Biboro\Model\Workbook;
use Biboro\Model\WorkbookOrder;
use Biboro\Model\WorkbookPermission;


class WorkbookService {

    const PERMIT_MANAGE_USER = 1;
    const PERMIT_MODIFY = 2;

    private $account;
    private $snippet;
    private $score;

    public function __construct(AccountService $account, SnippetService $snippet, ScoreService $score)
    {
        $this->account = $account;
        $this->snippet = $snippet;
        $this->score = $score;
    }

    public function getByAccountId($accountId)
    {
        return Workbook::where("account_id","=",$accountId)->get();
    }

    public function get($workbookId = null)
    {
        $user = $this->account->getLoginedUserInfo();

        if(is_null($workbookId)) {
            $orderMap = WorkbookOrder::where("account_id","=",$user->id)->lists("order", "workbook_id");
            $workbooks = Workbook::with("account")->where("account_id","=",$user->id)->get();
            foreach ($workbooks as $wb) {


                if($this->editable($wb)) {
                    $wb->editable = true;
                }else {
                    $wb->editable = false;
                }

                if(isset($orderMap[$wb->id])) {
                    $wb->order = $orderMap[$wb->id];
                }
            }
            return $workbooks;
        }

        return Workbook::with("account")->where("id","=",$workbookId)->first();
    }

    public function search($workbook ,$request_keyword)
    {
        $result = [];

        if(is_a($workbook,"Biboro\Model\Workbook")){
            $snippets = $workbook->snippets()->getResults();
        }else{
            $snippets = $workbook;
        }


        foreach ($snippets as $snippet) {
            $score = $this->score->calcScore($snippet, $request_keyword);
            if($score > 0) {
                $snippet->score = $score;
                $result[] = $snippet;
            }
        }

        // sort for score
        usort($result, function($a, $b) {
            return $a->score < $b->score;
        });

        return $result;
    }


    public function create($title, $description = "", $accountId = null)
    {
        $workbook = new Workbook;
        if(is_null($accountId)) {
            $user = $this->account->getLoginedUserInfo();
            $workbook->account_id = $user->id;
        }else {
            $workbook->account_id = $accountId;
        }

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

    public function getPermission(Workbook $workbook, $targetAccountId = null)
    {
        $model = WorkbookPermission::where("workbook_id","=",$workbook->id);

        if(!is_null($targetAccountId)) {
            $model->where("target_account_id","=",$targetAccountId);
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

    public function updateOrder($orders)
    {
        // validate
        $map = [];
        foreach ($orders as $workbookId => $order) {
            if(array_key_exists($order, $map)) {
                throw new Exception\WorkbookOrderWrongFormat;
            }else {
                $map[$order] = true;
            }
        }

        // save
        $user = $this->account->getLoginedUserInfo();
        WorkbookOrder::where("account_id","=",$user->id)->delete();
        foreach ($orders as $workbookId => $order) {
            WorkbookOrder::insert([
                "workbook_id" => $workbookId,
                "account_id" => $user->id,
                "order" => $order,
            ]);
        }
    }

    public function workbookExists($workbookId) {
        return Workbook::where("id","=",$workbookId)->count() > 0;
    }

}
