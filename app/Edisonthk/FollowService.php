<?php namespace Biboro\Edisonthk;

use Biboro\Model\Follow;
use Biboro\Model\Snippet;

class FollowService {

    const FOLLOW_USER = 1;
    const FOLLOW_TAG = 2;
    const FOLLOW_WORKBOOK = 3;

    private $account;
    private $tag;
    private $workbook;

    public function __construct(AccountService $account, WorkbookService $workbook, TagService $tag)
    {
        $this->account = $account;
        $this->workbook = $workbook;
        $this->tag = $tag;
    }

    public function get($userId = null)
    {
        if(is_null($userId)) {
            $user = $this->account->getLoginedUserInfo();
            return Follow::where("account_id","=",$user["id"])->get();
        }
        return Follow::where("account_id","=",$userId)->get();
    }

    private function getItemsByModel($model, $userId)
    {
        if(is_null($userId)) {
            $user = $this->account->getLoginedUserInfo();
            return $model->where("account_id","=",$user["id"])->get();
        }
        return $model->where("account_id","=",$userId)->get();   
    }

    private function getItemIdsByModel($model, $userId)
    {
        if(is_null($userId)) {
            $user = $this->account->getLoginedUserInfo();
            return $model->where("account_id","=",$user["id"])->lists("target");
        }
        return $model->where("account_id","=",$userId)->lists("target");   
    }

    public function getFollowingUsers($userId = null)
    {
        $model = Follow::where("type","=",self::FOLLOW_USER);

        return $this->getItemsByModel($model, $userId);
    }

    public function getFollowingTags($userId = null)
    {
        $model = Follow::where("type","=",self::FOLLOW_TAG);

        return $this->getItemsByModel($model, $userId);
    }

    public function getFollowingWorkbooks($userId = null)
    {
        $model = Follow::where("type","=",self::FOLLOW_WORKBOOK);

        return $this->getItemsByModel($model, $userId);
    }

    public function getFollowingUserId($userId = null)
    {
        $model = Follow::where("type","=",self::FOLLOW_USER);

        return $this->getItemIdsByModel($model, $userId);
    }

    public function getFollowingTagId($userId = null)
    {
        $model = Follow::where("type","=",self::FOLLOW_TAG);

        return $this->getItemIdsByModel($model, $userId);
    }

    public function getFollowingWorkbookId($userId = null)
    {
        $model = Follow::where("type","=",self::FOLLOW_WORKBOOK);

        return $this->getItemIdsByModel($model, $userId);
    }

    public function getFollowingSnippets($userId = null, $queryCallback = null)
    {
        $workbookIds = $this->getFollowingWorkbookId();
        $tagIds      = $this->getFollowingTagId();
        $userIds     = $this->getFollowingUserId();
        
        $duplicateSnippetIds = \DB::table("snippet_tag")->whereIn("tag_id",$tagIds)->lists("snippet_id");
        $duplicateSnippetIds = array_merge($duplicateSnippetIds, \DB::table("workbook_snippet")->whereIn("workbook_id",$workbookIds)->lists("snippet_id"));

        // filter new id lists without duplicate
        $snippetIds = [];
        foreach ($duplicateSnippetIds as $value) {
            if(!in_array($value, $snippetIds)) {
                $snippetIds[] = $value;
            }
        }

        $model = Snippet::whereIn("id",$snippetIds)->whereIn("account_id",$userIds,"or");

        if(is_callable($queryCallback)) {
            $model = $queryCallback($model);
        }

        return $this->getItemsByModel($model, $userId);
    }

    public function follow($type, $target)
    {
        if($this->isFollowed($type, $target)) {
            throw new Exception\AlreadyFollowed;
        }

        if($type == self::FOLLOW_USER) {
            $targetUserId = $target;
            if(!$this->account->accountExists($targetUserId)) {
                throw new Exception\UserNotFound;
            }
        }else if($type == self::FOLLOW_TAG) {
            $targetTagId = $target;
            if(!$this->tag->tagExists($targetTagId)) {
                throw new Exception\TagNotFound;
            }
        }else if($type == self::FOLLOW_WORKBOOK) {
            $targetWorkbookId = $target;
            if(!$this->workbook->workbookExists($targetWorkbookId)) {
                throw new Exception\WorkbookNotFound;
            }
        }else {
            $exception = new Exception\UnknownFollowType;
            $exception->setTypeDefined($type);
            throw $exception;
        }

        $user = $this->account->getLoginedUserInfo();

        $follow = new Follow;
        $follow->account_id  = $user["id"];
        $follow->type        = $type;
        $follow->target      = $target;
        $follow->save();

    }

    public function unfollow($type, $target)
    {
        $user = $this->account->getLoginedUserInfo();
        
        Follow::where("account_id","=",$user["id"])->where("type","=",$type)->where("target","=",$target)->delete(); 
    }

    public function isFollowed($type, $target)
    {
        $user = $this->account->getLoginedUserInfo();

        return Follow::where("account_id","=",$user["id"])->where("type","=",$type)->where("target","=",$target)->count() > 0;
    }
}