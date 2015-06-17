<?php namespace App;



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

		$created_date = date('Y-m-d G:i:s');
		

		$snippet_id = $this->id;
		
		\DB::delete('delete from snippet_tag where snippet_id = ?',array($snippet_id));
		
		
		foreach($tags as $tag_name){
			$db_tag = \DB::select("select id from tag_table where name=?",array($tag_name));
			

			if(count($db_tag) == 0){
				$tag_id = \DB::table('tag_table')->insertGetId(array("name"=>$tag_name,"created_at"=>$created_date,"updated_at"=>$created_date));
			}else{
				$tag_id = $db_tag[0]->id;
			}
			\DB::insert("insert into snippet_tag (tag_id,snippet_id,created_at,updated_at) values (?,?,?,?)",array($tag_id,$snippet_id,$created_date,$created_date));
		}
		
	}

	public function getUpdatedAtInReadableFormat()
	{
		return $this->convertToUserView($value);
	}
}