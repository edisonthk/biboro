<?php namespace App\Edisonthk;

use Auth;
use App\Model\Snippet;

class SnippetService {

    private $account;

    public function __construct(AccountService $account) {
        $this->account = $account;
    }

    public function get($id = null)
    {
        if(is_null($id)) {
            $user = $this->account->getLoginedUserInfo();
            return Snippet::where("account_id","=",$user->id)->get();
        }
        return Snippet::find($id);
    }

    public function getMy()
    {
        return $this->getMyWith();
    }

    public function getMyWith($loader = [])
    {
        $user = $this->account->getLoginedUserInfo();
        return Snippet::with($loader)->where("account_id","=",$user->id)->get();
    }

    public function createAndSave($title, $content, $tags)
    {
        $user = $this->account->getLoginedUserInfo();

        $snippet = new Snippet;
        $snippet->title         = $title;
        $snippet->content       = $content;
        $snippet->timestamps    = true;
        $snippet->lang          = "jp";
        $snippet->account_id    = $user->id;
        $snippet->save();

        $snippet->tagsave($tags);

        return $snippet;
    }


	
	public function recordKeywords($kw) {
		// ファイルの出力

		//ファイル出力
		$fileName = storage_path("kw") . "/" .date('Y-m-d').".csv";
		$date=date('Y-m-d H:i:s');
		$outputkw =  $date.','.\UserAgent::device().','.\UserAgent::platform().','.\UserAgent::browser().','.$kw.','.PHP_EOL;
		file_put_contents($fileName,$outputkw,FILE_APPEND | LOCK_EX);
	}

    public function multipleEagerLoadWithTidy(&$snippets) {

        $snippetsId = [];
        foreach ($snippets as $snippet) {
            $snippetsId[] = $snippet->id;
        }

        $snippets = [];

        $filteredSnippets = Snippet::with("tags","creator","reference")->whereIn("id",$snippetsId)->get();
        foreach ($filteredSnippets as $snippet) {
            $this->beautifySnippetObject($snippet, false);
            $snippets[] = $snippet;
        }

    }

	public function beautifySnippetObject(&$snippet, $load = true){

        if($load) {
            $snippet->load("tags","creator","reference");    
        }
        
		$snippet->readable_updated_at   = $this->convertToUserViewTimestamp($snippet->updated_at);
		$snippet->editable              = (Auth::check() && Auth::id() == $snippet->account_id);
        
	}

	public function convertToUserViewTimestamp($timestamp){
	    $d1 = new \DateTime($timestamp);
	    $n = new \DateTime("now");
	    $diff = $d1->diff($n);

	    if($diff->y > 0) { 
	        return $diff->format("%y年前");
	    } 
	    if($diff->m > 0) { 
	        return $diff->format("%m月前");
	    } 
	    if($diff->d > 0) { 
	        return $diff->format("%d日前");
	    } 
	    if($diff->h > 0) { 
	        return $diff->format("%h時間前");
	    } 
	    if($diff->i > 0) { 
	        return $diff->format("%i分前");
	    } 

	    return "１分前";
	}

	public function validate($inputs)
	{
		\Validator::extend('tag_exists', function($attribute, $value, $parameters)
		{
			if(is_array($value)){
				foreach ($value as $key => $single) {
					$count = Tag::where("name","=",$single)->count();
					if($count <= 0){
						break;
					}
				}
			}else{
				$count = Tag::where("name","=",$value)->count();	
			}
			

		    return $count > 0;
		});

		$rules = array(
			'title'       => 'required',
			'content'      => 'required',

            // From v1, tags are not more required input anymore
			// 'tags'      => 'required'
		);

		return \Validator::make($inputs, $rules);
	}

	public function search($kw)
	{
		
	}
}