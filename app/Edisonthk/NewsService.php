<?php namespace App\Edisonthk;

use App\Model\Snippet;

class NewsService {

    private $snippet;
    private $account;

    public function __construct(
        SnippetService $snippet,
        AccountService $account
    )
    {
        $this->account = $account;
        $this->snippet = $snippet;
    }

    public function getQuery() {
        return $this->snippet->with()->with("workbooks")->orderBy('updated_at', 'desc');
    }

    public function tidy(&$news) {
        foreach($news as $singleNews) {
            $this->snippet->beautifySnippetObject($singleNews);
        }
    }

}