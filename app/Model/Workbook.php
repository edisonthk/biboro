<?php

namespace Biboro\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workbook extends Model
{
    use SoftDeletes;
    //
    public function snippets()
    {
        return $this->belongsToMany('Biboro\Model\Snippet','workbook_snippet');
    }

    public function account()
    {
        return $this->hasOne('Biboro\Model\Account','id','account_id');
    }
}
