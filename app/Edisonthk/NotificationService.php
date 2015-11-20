<?php namespace Biboro\Edisonthk;

use Illuminate\Http\Request;

use Biboro\Model\Notification;
use Biboro\Model\Snippet;
use Biboro\Model\Comment;

class NotificationService {
    
    private $account;

    public function __construct(AccountService $account)
    {
        $this->account = $account;
    }

    public function markAsRead($ids) {

        $user = $this->account->getLoginedUserInfo();

        $notification = Notification::whereIn("id",$ids)
            ->where("to_user_id","=",$user->id)
            ->get();

        foreach ($notification as $n) {
            $n->read = true;
            $n->save();
        }
    }

    public function get($offset = 0, $limit = 5) {
        $user = $this->account->getLoginedUserInfo();

        $notices = Notification::with("byUser","toUser")
            ->where("to_user_id","=",$user->id)
            ->orderBy("created_at","desc")
            ->take($limit)
            ->get();

        return $this->transformData($notices);
    }

    private function transformData($notices) {
        $commentIds = [];
        $snippetIds = [];

        foreach ($notices as $n) {
            if($n->type == Notification::TYPE_COMMENT || $n->type == Notification::TYPE_REPLY) {
                $commentIds[] = $n->target;
            }else if($n->type == Notification::TYPE_FORKED) {
                $snippetIds[] = $n->target;
            }
        }

        if(count($snippetIds) > 0) {

            $snippets = Snippet::whereIn("id",$snippetIds)->get();

            foreach ($notices as $n) {
                if($n->type == Notification::TYPE_FORKED) {
                    foreach ($snippets as $snippet) {   
                        if($snippet->id == $n->target) {
                            $n->snippet = $snippet;
                            break;
                        }
                    }
                }
            }
        }

        if(count($commentIds) > 0) {

            $comments = Comment::whereIn("id",$commentIds)->get();

            foreach ($notices as $n) {
                if($n->type == Notification::TYPE_COMMENT || $n->type == Notification::TYPE_REPLY) {
                    foreach ($comments as $c) {   
                        if($c->id == $n->target) {
                            $n->comment = $c;
                            break;
                        }
                    }
                }
            }
        }

        return $notices;
    }

    public function noticeComment($toUser, $comment) {

        $user = $this->account->getLoginedUserInfo();

        if($user->id != $toUser) {
            $this->notice($user->id, $toUser, Notification::TYPE_COMMENT, $comment->id, trans("messages.snippet_commented"));
        }

    }

    public function noticeReply($fromUser, $comment) {

        $user = $this->account->getLoginedUserInfo();

        if($user->id != $toUser) {
            $this->notice($fromUser->id, $user->id, Notification::TYPE_REPLY, $comment->id, trans("messages.comment_replied"));
        }

    }

    public function noticeForked($snippet) {

        $user = $this->account->getLoginedUserInfo();

        if($user->id != $snippet->account_id) {
            $this->notice($user->id, $snippet->account_id, Notification::TYPE_FORKED, $snippet->id, trans("messages.snippet_forked"));
        }

    }

    public function notice($fromUser, $toUser, $type, $target, $description = "", $read = false) {
        $n = new Notification;
        $n->by_user_id = $fromUser;
        $n->to_user_id = $toUser;
        $n->type = $type;
        $n->target = $target;
        $n->description = $description;
        $n->read = $read;
        $n->save();
    }

}