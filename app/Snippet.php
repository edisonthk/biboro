<?php namespace App;

use App\Tag;
use App\Snippet;


class Snippet extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'snippets';
	
	public $timestamps = true;

	public function tags(){
		return $this->belongsToMany('App\Tag','snippet_tag','snippet_id','tag_id');
	}

	public function getCreatorName(){
		$acc = Account::find($this->account_id);
		if(is_null($acc)){
			return "null";
		}

		return $acc->name;
	}

	//$id,$name,$tag_id,$snippet_id
	public function tagsave($tags){

		$snippet_id = $this->id;
		
        $tagsId = []; 
		foreach($tags as $tag_name){
            $tag = Tag::where("name","=",$tag_name)->first();
            if(is_null($tag)) {
                $tag = new Tag;
                $tag->name = $tag_name;
                $tag->save();
            }

            $tagsId[] = $tag->id;
		}
		
        Snippet::find($snippet_id)->tags()->sync($tagsId);
	}

	public function getUpdatedAtInReadableFormat()
	{
		return $this->convertToUserView($value);
	}
}