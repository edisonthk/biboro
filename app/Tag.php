<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {



	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tag_table';
	
	public $timestamps = true;
	
	public function snippets(){
		return $this->belongsToMany('Snippet','snippet_tag','tag_id','snippet_id');	//‘½‘Î‘½‚Ì‚Æ‚«‚Ég‚¤
	}

	public static function createIfNotExists($name) {
		$tag = Tag::where('name','=',$name)->first();
		if(is_null($tag)){
			// tag not exists, so create it
			$tag = new Tag;
			$tag->name = $name;
			$tag->save();
		}
		
		return $tag;
	}

	public static function onlyName($search = null){

		$results = array();

		if(is_null($search)){
			// search is null, it means searching is not requested. 
			// When searching is not requested, all available tags (tags stored in tag table) is returned
			$tags = self::all(array("name"));
			foreach ($tags as $key => $value) {
				$name = $value->name;
				array_push($results, $name);
			}

		}else{
			// searching is requested
			// get the result from $search
			$results = self::where("name","like","%".$search."%")->get();

		}
		
		return $results;
	}


}