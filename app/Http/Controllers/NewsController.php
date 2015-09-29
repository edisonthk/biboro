<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Edisonthk\Exception\MissingLoginedUserInfo;
use App\Edisonthk\Exception\NotAllowedToEdit;
use App\Edisonthk\Exception\SnippetFoundInWorkbook;

class NewsController extends Controller
{
    private $news;
    private $snippet;
    private $pagination;

    public function __construct(
        \App\Edisonthk\NewsService $news,
        \App\Edisonthk\SnippetService $snippet,
        \App\Edisonthk\PaginationService $pagination 
    ) {
        $this->pagination = $pagination;
        $this->snippet = $snippet;
        $this->news = $news;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $newsQuery = $this->news->getQuery();

        $pagination = $this->pagination->makeWithQuery($newsQuery, $request);

        $news = $pagination["data"];

        $this->news->tidy($news);

        unset($pagination["data"]);

        return response()->json([
                "pagination" => $pagination,
                "news" => $news,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        // $profile = $this->profile->getByAccountId($id);

        return response()->json();
    }
}
