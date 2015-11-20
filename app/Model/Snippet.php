<?php namespace Biboro\Model;

use Biboro\Model\Tag;
use Biboro\Model\Snippet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Snippet extends Model {

    use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'snippets';
	
	public $timestamps = true;

	public function tags(){
		return $this->belongsToMany('Biboro\Model\Tag','snippet_tag','snippet_id','tag_id');
	}

    public function reference() {
        return $this->hasOne('Biboro\Model\SnippetReference');
    }

    public function workbooks()
    {
        return $this->belongsToMany('Biboro\Model\Workbook','workbook_snippet');
    }

    public function comments()
    {
        return $this->hasMany("Biboro\Model\Comment");
    }

	public function creator()
    {
        return $this->hasOne('Biboro\Model\Account','id','account_id');
    }

	//$id,$name,$tag_id,$snippet_id
	public function tagsave($tags){
        if(is_null($tags)) {
            return;
        }

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