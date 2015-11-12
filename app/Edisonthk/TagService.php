<?php namespace App\Edisonthk;

use App\Model\Tag;

class TagService {

    public function tagExists($tagId) {
        return Tag::where("id","=",$tagId)->count() > 0;
    }
}
