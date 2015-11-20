<?php namespace Biboro\Edisonthk;

use Biboro\Model\Tag;

class TagService {

    public function tagExists($tagId) {
        return Tag::where("id","=",$tagId)->count() > 0;
    }
}
