<?php namespace App\Edisonthk;

use App\Model\Follow;

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