<?php namespace App\Edisonthk;

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
            return Snippet::where("account_id","=",$user["id"])->get();
        }
        return Snippet::find($id);
    }

    public function createAndSave($title, $content, $tags)
    {
        $snippet = new Snippet;
        $snippet->title         = $title;
        $snippet->content       = $content;
        $snippet->timestamps    = true;
        $snippet->lang          = "jp";
        $snippet->account_id    = \Session::get("user")["id"];
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

	public function beautifySnippetObject($snippet){
		$temp = $snippet->toArray();
		$temp["updated_at"] = $this->convertToUserViewTimestamp($temp["updated_at"]);
		$temp["tags"] = $snippet->tags()->getResults()->toArray();
		$temp["creator_name"] = $snippet->getCreatorName();
		$temp["editable"] = (\Session::has("user") && \Session::get("user")["id"] == $snippet->account_id);
        $temp["reference"] = $snippet->reference()->getResults();
		
		return $temp;
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