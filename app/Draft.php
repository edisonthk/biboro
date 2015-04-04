<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'drafts';
	
	public $timestamps = true;

	public function tags(){
		return $this->belongsToMany('App\Tag','draft_tag','draft_id','tag_id');
	}

	public function getCreatorName(){
		$acc = Account::find($this->account_id);
		if(is_null($acc)){
			return "null";
		}

		return $acc->name;
	}

	public function tagsave($tags) {

		\DB::delete('delete from draft_tag where draft_id = ?',array($this->id));
		
		$new_tags = [];
		foreach ($tags as $tag_name) {
			$tag = Tag::createIfNotExists($tag_name);
			array_push($new_tags, $tag->id);
		}

		$this->tags()->sync($new_tags);
	}

}
