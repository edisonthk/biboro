<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    
    public function account()
    {
        return $this->hasOne('App\Model\Account','id','account_id');
    }

    //
    public function snippet()
    {
        return $this->belongsTo('App\Model\Snippet');
    }
}
