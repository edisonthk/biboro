<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const TYPE_COMMENT = 1;
    const TYPE_REPLY = 2;
    const TYPE_FORKED = 3;

    protected $table = "notification";

    protected $casts = [
        "read" => "boolean",
    ];

    public function byUser()
    {
        return $this->hasOne('App\Model\Account','id','by_user_id');
    }

    public function toUser()
    {
        return $this->hasOne('App\Model\Account','id','to_user_id');
    }
}
