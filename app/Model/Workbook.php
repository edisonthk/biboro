<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Workbook extends Model
{
    //
    public function snippets()
    {
        return $this->belongsToMany('App\Model\Snippet','workbook_snippet');
    }


}
